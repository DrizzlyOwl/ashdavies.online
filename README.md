`%cd%` in cmd.exe == `$(pwd)` in bash

To install composer packages (from the root):
```
$ docker run --rm --interactive --tty --volume="$(pwd)":/app composer <command>
```

To run `npm` tasks (from the theme folder):
```
$ docker run -it --rm -v "$(pwd)":/usr/src/app -w /usr/src/app node npm <command>
```

To grab the latest database for working on locally, assuming you have valid
credentials in `~/.aws/credentials`

```
$ source ./.env.mysql
$ docker compose up -d mysql
$ docker compose cp /tmp/database.sql mysql:/tmp/database.sql
$ docker compose exec -d mysql \
  mysql -h 127.0.0.1 \
  -u "$MYSQL_USER" "$MYSQL_DATABASE" \
  -p"$MYSQL_PASSWORD" < /tmp/database.sql
$ rm /tmp/database.sql
```

## Local email

Local development mail is captured by a [Mailpit](https://mailpit.axllent.org/)
container (a mail catcher) rather than being sent to a real SMTP/SES endpoint.
No real mail credentials are needed locally. WordPress is pointed at Mailpit via
the `SMTP_*` variables in `.env.wordpress` (see `.env.wordpress.example`), with
`SMTP_INSECURE=1` disabling auth/TLS for the plain catcher.

View captured emails at: http://localhost:8025
