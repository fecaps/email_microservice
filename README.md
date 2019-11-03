# Email Microservice

## Table Of Contents

1. [Setup](#setup)
    1. [Clone](#clone)
    1. [Environment Variables](#environment-variables)
    1. [Build](#build)
    1. [API](#api)
    1. [Composer Scripts](#composer-scripts)
    1. [Tests](#tests)
    1. [Git Hooks](#git-hooks)
1. [Local Usage](#local-usage)
1. [Project Definitions](#project-definitions)

## Setup

### Clone

Clone the project and enter on its folder:

```bash
$ git clone git@gitlab.com:fecaps/email_microservice.git && \
cd email_microservice
```

### Environment Variables

Copy env variables file and edit it in case of willing to change its configuration:

```bash
$ cp .env.example .env
```

### Build

- Build Docker image in detach mode and run it:

```bash
$ docker-compose -f infrastructure/docker-compose.yml up --build -d
```

## API

The default API host:port is:

http://localhost:8080/api

## Composer Scripts

The project has these `composer` scripts:

*PS.: It requires the container running*
 
```bash
docker exec email_microservice composer run-script codeStyle
# code style check

docker exec email_microservice composer run-script copyPasteDetector
# mess detector

docker exec email_microservice composer run-script messDetector
# copy/paste detector

docker exec email_microservice composer run-script objectCalisthenics
# object calisthenics rules

docker exec email_microservice composer run-script errorsAnalyse
# errors analyse
``` 

### Tests

*PS.: It requires the container running*

```bash
$ docker exec email_microservice ./vendor/bin/phpunit
```

The tests generate a HTML and TXT reports which use **XDebug** and it's
located on `report` folder.

To see the text report use:

```bash
$ docker exec -it email_microservice cat report/txt-report
```

### Git Hooks

There are two git hooks, which are composed of **composer scripts**
and **testing scripts**.

- `pre-commit`:
    - `codeStyle`
    - `copyPasteDetector`
    - `messDetector`
    - `objectCalisthenics`
    - `errorsAnalyse`
 
- `pre-push`:
    - `codeStyle`
    - `copyPasteDetector`
    - `messDetector`
    - `objectCalisthenics`
    - `errorsAnalyse`
    - `phpunit`


## Project Definitions

- **Docker:** All files related to **Docker** and **docker-compose**
are set within `infrastructure` folder.

The build process is **multi-stage**. It has two steps:

- Installing PHP/Composer dependencies
- Installing PHP extensions and configuring web/app server

There are two `Dockerfiles` for `email_microservice`, one is used for
`development` and another for `production`.

The one used for `development` contains `XDebug` and **dev dependencies**.
