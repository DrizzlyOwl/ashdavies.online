`%cd%` in cmd.exe == `$(pwd)` in bash

To install composer packages (from the root):
```
$ docker run --rm --interactive --tty --volume="$(pwd)":/app composer <command>
```

To run `npm` tasks (from the theme folder):
```
$ docker run -it --rm -v "$(pwd)":/usr/src/app -w /usr/src/app node npm <command>
```

To run db import tasks:
```
$ docker run -it --rm -v /tmp:/tmp mysql:8.0 mysqldump \
  --single-transaction --set-gtid-purged=OFF \
  -h $HOST \
  -u $USER $DB_NAME -p > database.sql
$ mysql -h 127.0.0.1 \
  -u $USER $DB_NAME -p < database.sql
```
