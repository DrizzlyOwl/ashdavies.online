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
$ ./get-database.sh
$ source ./.env.mysql
$ docker compose up -d mysql
$ docker compose cp /tmp/database.sql mysql:/tmp/database.sql
$ docker compose exec -d mysql \
  mysql -h 127.0.0.1 \
  -u "$MYSQL_USER" "$MYSQL_DATABASE" \
  -p"$MYSQL_PASSWORD" < /tmp/database.sql
$ rm /tmp/database.sql
```
