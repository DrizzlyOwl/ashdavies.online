#! /bin/bash
set -e
set -o pipefail

AWS_PROFILE="dxw-ash"
AWS_REGION="eu-west-2"

LIGHTSAIL_RDS=$(aws lightsail get-relational-databases \
  --profile "$AWS_PROFILE" \
  --region "$AWS_REGION")
RDS=$(echo "$LIGHTSAIL_RDS" | jq -r '.relationalDatabases[0]')
RDS_SERVICE_NAME=$(echo "$RDS" | jq -r '.name')
RDS_STATE=$(echo "$RDS" | jq -r '.state')

if [ "$RDS_STATE" != "available" ];
then
  echo "$RDS_SERVICE_NAME is not in a ready state. Unable to proceed, try again."
  exit 1
fi

DATABASE_NAME=$(echo "$RDS" | jq -r '.masterDatabaseName')
DATABASE_USER=$(echo "$RDS" | jq -r '.masterUsername')
DATABASE_HOST=$(echo "$RDS" | jq -r '.masterEndpoint.address')
DATABASE_VERSION=$(echo "$RDS" | jq -r '.engineVersion')
DATABASE_PASSWORD=$(aws lightsail get-relational-database-master-user-password \
  --profile "$AWS_PROFILE" \
  --region "$AWS_REGION" \
  --relational-database-name "$RDS_SERVICE_NAME" | jq -r '.masterUserPassword')
RDS_ACCESS=$(echo "$RDS" | jq -r '.publiclyAccessible')

download_db() {
  echo "Downloading database dump..."
  docker run -it --rm -v /tmp:/tmp \
    -e MYSQL_RANDOM_ROOT_PASSWORD=1 \
    -e TZ='Europe/London'\
    "mysql:$DATABASE_VERSION" mysqldump \
      ""--single-transaction \
      --set-gtid-purged=OFF \
      --skip-extended-insert \
      -h "$DATABASE_HOST" \
      -u "$DATABASE_USER" "$DATABASE_NAME" \
      -p"$DATABASE_PASSWORD" > /tmp/database.sql""
  echo "Downloaded to /tmp/database.sql!"
  scrub_secrets
}

# Remove sensitive credentials (e.g. Amazon SES access keys) from the dump so
# that no live secret is ever written to a local working copy of the database.
# We both (a) redact the serialized option values in-place so the secret never
# persists in the file, and (b) append DELETE statements so the rows are removed
# entirely on import (regardless of table prefix); the plugin then falls back to
# empty settings locally.
scrub_secrets() {
  echo "Scrubbing sensitive credentials from dump..."

  # Option names known to hold credentials / SMTP secrets.
  local sensitive_options=(
    "yay_smtp_amazonses_settings"
    "yay_smtp_settings"
    "yay_smtp_others_settings"
  )

  # (a) Redact any line mentioning a sensitive option so the raw secret bytes
  #     never sit on disk, even transiently, before import.
  for option_name in "${sensitive_options[@]}"; do
    if grep -q "$option_name" /tmp/database.sql; then
      # Replace the whole line containing the option with a harmless comment.
      sed -i.bak "/${option_name}/s/.*/-- redacted line containing ${option_name} (scrubbed by get-database.sh)/" /tmp/database.sql
      rm -f /tmp/database.sql.bak
    fi
  done

  # (b) Belt-and-braces: ensure the rows are gone after import.
  {
    echo ""
    echo "-- Credentials scrubbed by get-database.sh"
    for option_name in "${sensitive_options[@]}"; do
      echo "DELETE FROM \`wp_options\` WHERE \`option_name\` = '${option_name}';"
    done
  } >> /tmp/database.sql

  echo "Sensitive credentials scrubbed."
}

make_rds_public() {
  echo "Making RDS publicly accessible..."
  MADE_PUBLIC=1

  OPERATION=$(aws lightsail update-relational-database \
    --relational-database-name "$RDS_SERVICE_NAME" \
    --profile "$AWS_PROFILE" \
    --region "$AWS_REGION" \
    --publicly-accessible)

  if [ "$(echo "$OPERATION" | jq -r '.operations[0].status')" = "Succeeded" ];
  then
    STATE="modifying"
    echo "Waiting for RDS..."

    until [ "$STATE" = "available" ]
    do
      sleep 5
      STATE=$(aws lightsail get-relational-databases \
        --profile "$AWS_PROFILE" \
        --region "$AWS_REGION" | jq -r '.relationalDatabases[0].state')
      echo "Current state: $STATE"
    done

    echo "$RDS_SERVICE_NAME is now publicly accessible"
  else
    echo "Unable to set publicly accessible property of RDS!"
  fi
}

make_rds_private() {
  echo "Making RDS private..."

  OPERATION=$(aws lightsail update-relational-database \
    --relational-database-name "$RDS_SERVICE_NAME" \
    --profile "$AWS_PROFILE" \
    --region "$AWS_REGION" \
    --no-publicly-accessible)

  if [ "$(echo "$OPERATION" | jq -r '.operations[0].status')" = "Succeeded" ];
  then
    STATE="modifying"
    echo "Waiting for RDS to become private..."

    until [ "$STATE" = "available" ]
    do
      sleep 5
      STATE=$(aws lightsail get-relational-databases \
        --profile "$AWS_PROFILE" \
        --region "$AWS_REGION" | jq -r '.relationalDatabases[0].state')
      echo "Current state: $STATE"
    done

    echo "$RDS_SERVICE_NAME is no longer publicly accessible"
  else
    echo "Unable to unset publicly accessible property of RDS!"
  fi
}

if [ "$RDS_ACCESS" = true ];
then
  # Already public: leave it exactly as we found it.
  download_db
  make_rds_private
  exit 0
else
  # Ensure the database is always returned to a private state, even if the
  # script errors, is interrupted (Ctrl-C), or the network drops mid-run.
  cleanup() {
    if [ "${MADE_PUBLIC:-0}" = "1" ]; then
      MADE_PUBLIC=0
      make_rds_private
    fi
  }
  trap cleanup EXIT INT TERM

  make_rds_public
  download_db
  # cleanup (via the EXIT trap) re-privatises the RDS.
fi
