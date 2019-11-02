# Email Microservice

## Table Of Contents

1. [Setup](#setup)
    1. [Clone](#clone)
    1. [Environment Variables](#environment-variables)
    1. [Build](#build)
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
$ docker-compose -f infrastructure/docker-compose.yml up --build
```

## Local Usage

The project has these `composer` scripts:
 
```bash
composer run-script codeStyle
# code style check

composer run-script copyPasteDetector
# mess detector

composer run-script messDetector
# copy/paste detector

composer run-script objectCalisthenics
# object calisthenics rules

composer run-script errorsAnalyse
# errors analyse
``` 

All these scripts are setup as **git hooks** for both
`pre-commit` and  `pre-push` actions.

## Project Definitions

- **Docker:** All files related to **Docker** and **docker-compose**
are set within `infrastructure` folder.

The build process is **multi-stage**. It has two steps:

- Installing PHP/Composer dependencies
- Installing PHP extensions and configuring web/app server
