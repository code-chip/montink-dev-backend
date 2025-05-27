# Montink Store

## Overview

This project was developed and tested only in the Linux environment.
The project is a full-stack application with:

- Backend: PHP 8.3 API.
- Database: MySQL 8.0, with tables for orders, products, coupons, and stocks.
- Frontend: Vue.js SPA that communicates with backend API.
- Docker: Containers for backend, frontend, and database, with networking for inter-container communication.

---

## Requirements

- Git
- Docker Engine (version 20.x or higher)
- Docker Compose (version 1.29.x or higher)
- Node.js (v18+ recommended) [Only needed if you want to run frontend locally without Docker]
- NPM (comes with Node.js) [Same as above]

---

## Installation with a single command

```bash
git clone git@github.com:code-chip/montink-dev-backend.git montink-codechip &&
cd montink-codechip &&
cd backend &&
docker-compose build &&
docker-compose up -d &&
cd .. &&
cd frontend &&
docker-compose build &&
docker-compose up -d &&
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

- Shipping rules are applied based on order subtotal:
  - Subtotal between R$52.00 and R$166.59 → Shipping R$15.00
  - Subtotal above R$200.00 → Free shipping
  - Other values → Shipping R$20.00
- CEP validation is done using https://viacep.com.br API.
- Coupons have minimum subtotal requirements and expiration dates.
- On order finalization, an email is sent to the customer with order details.
- The webhook endpoint updates or cancels orders based on status updates.

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