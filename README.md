# Coding Assignment

[![Maintainability](https://api.codeclimate.com/v1/badges/dddcb4ce2cfcae838af4/maintainability)](https://codeclimate.com/github/ivanlemeshev/sm-assignment/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/dddcb4ce2cfcae838af4/test_coverage)](https://codeclimate.com/github/ivanlemeshev/sm-assignment/test_coverage)

## Requirements to run the application

* Git
* Docker
* Make

This project runs all commands and application in Docker containers in order 
to not install all the necessary software on local machine.

It is required to have `Make` utility to run commands or you can see how to run 
commands in `Makefile`.

## Setup

```bash
$ git clone git@github.com:ivanlemeshev/sm-assignment.git
$ cd sm-assignment
$ make init
```

## Running the application

```bash
$ make run-app
```

Application will be available at URL 
[http://localhost:8080](http://localhost:8080)

## Stopping the application

```bash
$ make stop-app
```

## Running linters and static analysis

```
make check
```

## Installation of new dependencies

```bash
make package="some/package" add
```

```bash
make package="some/package" add-dev
```
