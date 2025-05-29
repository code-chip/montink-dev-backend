# Montink Store

## Overview

This project was developed and tested only in the Linux environment.   
The project is a full-stack application with:

- Backend: PHP 8.3 API.
- Database: MySQL 8.0, with tables for orders, products, order_produtcs, coupons, and stocks.
- Frontend: Vue.js SPA that communicates with backend API.
- Docker: Containers for backend, frontend, and database, with networking for inter-container communication.

---

## Requirements

- Git
- Docker Engine (version 20.x or higher)
- Docker Compose (version 1.29.x or higher)

---

## Installation and execution with a single command

```bash
git clone git@github.com:code-chip/montink-dev-backend.git montink-codechip && \
cd montink-codechip && \
docker-compose -f backend/docker-compose.yml build && \
docker-compose -f backend/docker-compose.yml up -d && \
docker-compose -f frontend/docker-compose.yml build && \
docker-compose -f frontend/docker-compose.yml up -d && \
echo "⏳ Waiting for the backend to start..." && \
until [ -f backend/vendor/autoload.php ]; do
  echo "⏳ Waiting for autoload to be generated..."
  sleep 2
done && \
echo "✅ Backend ready, executing PHP commands..." && \
docker-compose -f backend/docker-compose.yml exec -T php sh -c "cd .. && php migrations/create_tables.php && php seeders/seed_data.php"
```

## Step by step installation: Backend Setup

### 1. Clone the repository

```bash
git clone git@github.com:code-chip/montink-dev-backend.git montink-codechip
&& cd montink-codechip && cd backend
```

### 2. Configure environment

Copy `.env.example` to `.env` and update database credentials if needed (default Docker setup uses):

```
########################################################################
# Application Stack
########################################################################
PROJECT_NAME=montink
MY_UID=1000
GID=1000
COMPOSER_PROJECT_NAME=code-chip/montink
COMPOSER_TYPE=project

########################################################################
# Application MySQL
########################################################################
DB_DATABASE=montink_store
DB_HOST=mysql
DB_PORT=3306
DB_USERNAME=root
DB_PASSWORD=secret
DB_ROOT_PASSWORD=rootsecret
```

Fill in the values ​​of the environment variables in the .docker/.env file. It is important to fill in the correct values ​​of MY_UID and GID, to confirm your user id in Linux run the id command, the terminal should display something close to this:  
```bash
uid=1000(will) gid=1000(will) grupos=1000(will),4(adm),24(cdrom),27(sudo),30(dip),33(www-data),46(plugdev),100(users),105(lpadmin),125(sambashare),127(docker)
```

### 3. Start backend and database containers

From the project backend folder (where `docker-compose.yml` is):

```bash
docker-compose build && docker-compose up -d
```

This will:

- Start the MySQL container with initial database.
- Start the PHP backend container.

### 4. Run database migrations and seeders

Access the backend container bash:

```bash
docker exec -it myproject_backend_1 bash
```

Inside container, run:

```bash
php migrations/create_tables.php
php seeders/seed_data.php
```

*(Scripts provided to create tables and insert seed data)*

### 5. Test the API

The backend API will be available at:

```
http://localhost:8000/
```

Try accessing:

```
http://localhost:8000/products
```

to list products (empty at first or seeded data if seeder was run).

---

## Frontend Setup

### 1. Configure environment

Copy `.env.example` to `.env` and update API url or project name if needed (default Docker setup uses):

### 2. Start frontend container

```bash
cd .. && cd frontend/
docker-compose build && docker-compose up -d
```

### 3. Test the access in broswer

The frontend will be available at:

```
http://localhost:3000
```

*(If running via Docker, see below)*

---

## Running Entire Project with Docker Compose

This will start backend, frontend, and MySQL containers on a shared Docker network.

---

## Access URLs

| Service  | URL                    |
| -------- | ---------------------- |
| Backend  | http://localhost:8000  |
| Frontend | http://localhost:3000  |
| MySQL    | (internal to Docker)   |

---

## API Endpoints Overview

- `GET /products` — list products
- `POST /products` — create product
- `PUT /products/{id}` — update product
- `POST /orders` — create order
- `POST /coupons` — create coupons
- `POST /webhook` — update order status

(Full API documentation is in the `docs/` folder — *if you want, I can generate it*)

---

## Notes

### How to Enable Xdebug on the Backend with VSCode
Requirements

Make sure you have the PHP Debug extension installed:
https://marketplace.visualstudio.com/items?itemName=xdebug.php-debug
1. Find the Host IP in the Docker Network

Run the following command on your host machine (outside the container):

```bash
ip -4 addr show docker0 | grep -Po 'inet \K[\d.]+'
```
This will usually return something like:
```bash
172.17.0.1
```
2. Test if the Container Can Reach This IP

Access the container shell:
```bash
docker exec -it montink_backend bash
```
Install ping and test the IP from step 1:
```bash
apt update && apt install iputils-ping -y && ping 172.17.0.1
```
If you get a response, this is the correct IP to use.
3. Set xdebug.client_host in Your php.ini (or equivalent)

Inside the container, update the Xdebug configuration:
```bash
echo "xdebug.client_host=172.17.0.1" >> /usr/local/etc/php/conf.d/xdebug.ini
```
4. Increase Timeout Limit

Create a new .ini file:
```bash
echo "max_execution_time=6000" > /usr/local/etc/php/conf.d/99-custom-timeout.ini
```
Or append to an existing file:
```bash
echo "max_execution_time=6000" >> /usr/local/etc/php/conf.d/docker-php.ini
```
Verify the configuration:
```bash
php --ini
```
Or check via /phpinfo.
5. Restart the Container

Restart the container (or restart Apache inside the container) to apply the changes.
6. Configure Path Mappings in .vscode/launch.json

In the root of your project, update your .vscode/launch.json to include the correct absolute path:
```bash
"pathMappings": {
  "/var/www/html": "/home/will/Documentos/projetos/montink-dev-backend/backend"
}
```

---

## Troubleshooting

- Ensure Docker daemon is running.
- Check container logs with:

```bash
docker-compose logs php
docker-compose logs mysql
docker-compose logs vuejs
```
or
```bash
docker logs montink_backend
docker logs montink_database
docker logs montink_frontend
```

- Ports 8000 and 3000 must be free on your machine.

---

## Contact

For issues or questions, please contact Will at willvix@outlook.com

---

Thank you for using this project!