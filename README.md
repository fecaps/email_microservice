# Email Microservice

## Usage

### Build

- Build Docker image in detach mode and run it:

```bash
$ docker-compose up --build -d
```

- Add/Update directory permissions on host machine (Laravel requires it):

```bash
$ sudo chmod 775 -R bootstrap/ storage/ && \
sudo chown -R $USER:www-data bootstrap/ storage
```

## Project Definitions

- **Docker:** Multi-stage build set in order to have steps
 for specific responsibilities. The first step is focused on installing PHP/Composer dependencies.
The second step is focused on installing PHP extensions and configuring
web/app server.
  
