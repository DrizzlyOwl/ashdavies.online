`%cd%` in cmd.exe == `$(pwd)` in bash

To install composer packages (from the root):
```
$ docker run --rm --interactive --tty --volume="%cd%":/app composer <command>
```

To run `npm` tasks (from the theme folder):
```
$ docker run -it --rm -v "%cd%":/usr/src/app -w /usr/src/app node npm <command>
```
