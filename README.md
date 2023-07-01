# Employee Hierarchy API

This is a REST API application that manages the hierarchy of employees in a company.

## Getting Started

These instructions will guide you on how to run the project on your local machine and deploy it using Docker.

### Prerequisites

To run the project locally, you will need the following:

- PHP 8.1 or later
- Composer
- MySQL or MariaDB database
- Docker (for deployment)

### Setup project on local

Follow these steps to set up the project on your local machine:

1. Run docker compose

```bash
docker compose up -d
```

2. Install dependence packages

```bash
docker compose exec app bash
compose install
```

3. Setup & config project

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

4. Add virtual host

- add `127.0.0.1 employee.test` to `/etc/hosts` file

### Deployment with Docker

1. Build the Docker image:

```bash
docker build -f docker/php/Dockerfile -t employee-hierarchy-api
```

2. Deploy with new image