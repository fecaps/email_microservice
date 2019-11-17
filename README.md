# Email Microservice

## Table Of Contents

1. [Setup](#setup)
    1. [Clone](#clone)
    1. [Environment Variables](#environment-variables)
    1. [Build](#build)
    1. [API](#api)
    1. [Console](#console)
    1. [Composer Scripts](#composer-scripts)
    1. [Tests](#tests)
    1. [Git Hooks](#git-hooks)
1. [Project Definitions](#project-definitions)
    1. [Organization](#organization)
    1. [Transactors](#transactors)
    1. [Worker](#worker)
    1. [Queue](#queue)
    1. [Laravel - AMQP](#laravel---amqp)
    1. [Logs](#logs)
    1. [Docker](#docker)
    1. [Scaling Up](#scaling-up)
    1. [Next Steps](#next-steps)
1. [API Resources](#api---resources)

## Setup

Update `storage/logs` folder permissions, this because it's used by docker volumes:

```bash
$ sudo chmod +x ./storage/logs
```

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

** These env variables require API keys, versions and secrets related
to the email vendors used within the application:

- [Mailjet](https://app.mailjet.com)
- [Sendgrid](https://app.sendgrid.com/)

### Build

- Build Docker image in detach mode and run it:

```bash
$ docker-compose -f infrastructure/docker-compose.yml up --build -d
```

## API

The default API host:port is:

http://localhost:8080

## Console

The console command responsible for sending emails to the queue:

```bash
composer run-script php artisan create:email
``` 

## Composer Scripts

The project has these `composer` scripts:

*PS.: It requires the containers running*
 
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

composer run-script fixStyle
# fix style
``` 

### Tests

*PS.: It requires the container running*

- Running tests:

```bash
$ composer run-script tests
```

The tests generate a HTML and TXT reports which use **XDebug** and it's
located on `report` folder.

- Showing code coverage in TXT:

```bash
$ composer run-script showCoverage
```

In case of willing to see it in HTML, open `report/index.html`
file in host machine. Example:

```bash
$ google-chrome report/index.html
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
    - `tests`
    - `showCoverage`

## Project Definitions

### Organization

The project is composed of 4 resources:
  - `infrastructure_email` (the publisher - **stateless**)
  - `infrastructure_email_consumer` (the queue consumer - **stateless**)
  - `email_rabbitmq` (the queue consumer - **stateful**)
  - `email_nginx` (the queue consumer - **stateless**)

### Transactors

At the moment the application uses two transactors/vendors for delivering emails
(in this order):

- **Mailjet**
- **Sendgrid**

**Attention**

These two vendors responsible for delivering emails have daily and monthly limits. 

**Mailjet** has a limit of 200 requests/day.

**Sendgrid** has a limit of 100 requests/day.

Plus these limits, **Mailjet** requires to add sender addresses in their platform,
so it's safer to add both `fellipecapelli@gmail.com` and `fellipe.capelli@outlook.com`
on it (which are used by the tests).

### Worker

The email worker implements the Chain of Responsibility pattern
when attempting to deliver emails. It tries to send an email through a given vendor,
if it doesn't work then it tries with the next one and so on.

In case of being needed to add a new vendor then these are the steps required:

- Create a config for the new vendor service in `config/service.php` file. Like the API key, etc.
- Create the env variables used by the new vendor service.
- Create a connector class for the new vendor service
  - Example: the `Mailjet` connector creates an instance of a `Mailjet Client` (third-party vendor).
- Create a **singleton** instance for the new connector created (in `EmailServiceProvider`).
- Create a transactor class for the new vendor service. This transactor will be responsible
for preparing the payload, sending the email and calling the trigger
(to call the next transactor in case of failure).
- Update the last transactor already created by injecting the new transactor as a dependency.
- Update the last transactor `sendTrigger` method to call the new transactor
(just like it's done in `MailjetTransactor` class, in `sendTrigger` method).

### Queue

Laravel officially supports relational databases and Redis as stateful resources
for dealing with queueing, however this project uses **RabbitMQ**, a message broker for queuing,
these are the reasons:

- When compared to any relational databases it's easier to add nodes to the RabbitMQ
cluster and natively deal with which message been managed by only one consumer. In a relational database
a lock field/layer on the request should be added to deal with it. Therefore it's easier
to scale up with RabbitMQ.

- When having network issues/breaks before actually acknowledging a
message in a consumer the broker itself manages to requeue the message.
In a relational database this would be managed by some tool/framework/implementation.

- When compared to any relational databases it's easier to deal with race conditions, as
some configurations like `prefetch` can be set in order to facilitate this.

- When compared to Redis it's safer, as all data (exchanges, queues and messages)
can be set as `durable/persistent` (to save in the disk), this way a broker restart wouldn't cause
all messages to be lost. While an in-memory queueing doesn't prevent this, plus in
a cluster (for instance, through Kubernetes) more memory would be required when scaling
up, while by using disks some volumes can be added.

### Laravel - AMQP

- Package used to deal with AMQP in Laravel: https://github.com/bschmitt/laravel-amqp

Possible improvements:

- Better usage of AMQP connections and channel by choosing whether both should be closed
when publishing or consuming a message.
- Improve prefetch configuration for channels.
- Add support for custom properties (headers) when publishing messages, this way can be
possible to set messages TTL, retries, etc.

### Logs

As this microservice is still small there are no third-party services, such as Graylog
(Mongo, Elasticsearch) to deal with logging. They are still managed through
log files (which are docker volumes).

Publisher logs:
`/storage/logs/publisher.log`

Consumer logs:
`/storage/logs/consumer.log`

### Docker

- **Docker:** All files related to **Docker** and **docker-compose**
are set within `infrastructure` folder.

The `email_microservice` and `email_microservice_consmer` resources have **multi-stage** builds.
Which are composed of two steps:

- Installing PHP/Composer dependencies
- Installing PHP extensions and configuring web/app server

There are two `Dockerfiles` for , one is used for
`development` and another for `production`.

The one used for `development` contains `XDebug` and **dev dependencies**.

### Scaling Up

All **stateless** can be scaled horizontally. Examples:

- Scaling up email consumers to 3:

```bash
$ docker-compose -f infrastructure/docker-compose.yml up --scale email_consumer=3 --build
```

- Scaling up email app servers to 2 and consumers to 3:

** Each app server added requires adding `server infrastructure_email_{count}:9000;` to
the `upstream` config on **Nginx** file (`infrastructure/nginx/default.conf`).

```bash
$ docker-compose -f infrastructure/docker-compose.yml up --scale email=2 --scale email_consumer=3 --build
```

### Next Steps

- Create a queue model and save each message queued, bounced and delivered.
- Create an API resource to retrieve this data (listing)
- Create a frontend application to list the messages based on endpoint created above
- Create a form in the frontend in order to also create new emails 

## API - Resources

- Endpoint: `POST http://localhost:8080/emails`

- Payload:

    - Example of text content:
    ```json
    {
        "from": {
            "email": "fellipecapelli@gmail.com",
            "name": "fellipe"
        },
        "to": [
            {
                "email": "fellipe.capelli@outlookl.com",
                "name": "fellipe"
            }
        ],
        "subject": "hello - test",
        "textPart": "hello - text test"
    }
    ```

    - Example of html content:
    ```json
    {
        "from": {
            "email": "fellipecapelli@gmail.com",
            "name": "fellipe"
        },
        "to": [
            {
                "email": "fellipe.capelli@outlookl.com",
                "name": "fellipe"
            }
        ],
        "subject": "hello - test",
        "htmlPart": "hello<br><br>html test"
    }
    ```

  - Example of markdown content:
  ```json
  {
      "from": {
          "email": "fellipecapelli@gmail.com",
          "name": "fellipe"
      },
      "to": [
          {
              "email": "fellipe.capelli@outlookl.com",
              "name": "fellipe"
          }
      ],
      "subject": "hello - test",
      "markdownPart": "hello, **markdown** test"
  }
  ```
