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
      -h "$DATABASE_HOST" \
      -u "$DATABASE_USER" "$DATABASE_NAME" \
      -p"$DATABASE_PASSWORD" > /tmp/database.sql""
  echo "Downloaded to /tmp/database.sql!"
}

make_rds_public() {
  echo "Making RDS publicly accessible..."

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
  download_db
  make_rds_private
  exit 0
else
  make_rds_public
  download_db
  make_rds_private
fi
