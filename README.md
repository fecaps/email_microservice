# Email Microservice

## Usage

### Clone

Clone the project and enter on its folder:

```bash
$ git clone git@gitlab.com:fecaps/email_microservice.git && cd email_microservice
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

## Project Definitions

- **Docker:** All files related to **Docker** and **docker-compose**
are set within `infrastructure` folder.

The build process is **multi-stage**. It has two steps:

- Installing PHP/Composer dependencies
- Installing PHP extensions and configuring web/app server
