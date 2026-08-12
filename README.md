<div align="center">

# Shoply

### Full-stack E-commerce Application

A practical e-commerce application built with **Laravel** and **Vue 3**, with a strong focus on backend development, RESTful APIs, data modeling, authentication, business logic, and performance.

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?style=for-the-badge&logo=vue.js&logoColor=white)](https://vuejs.org/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0%2B-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Redis](https://img.shields.io/badge/Redis-Cache-DC382D?style=for-the-badge&logo=redis&logoColor=white)](https://redis.io/)

<br>

**Status: Active Development**

</div>

---

## Overview

Shoply is a full-stack e-commerce application being developed as a practical project for building and exploring real-world web application concepts.

The project is designed with a separated Laravel backend and Vue 3 frontend, with the backend responsible for business logic, data persistence, authentication, and RESTful APIs, while the frontend handles the user interface and client-side state management.

The project is actively evolving, with new features and improvements being implemented progressively.

---

## Tech Stack

### Backend

| Technology | Purpose |
| --- | --- |
| **PHP** | Backend programming language |
| **Laravel** | Backend framework |
| **Eloquent ORM** | Database interaction and relationships |
| **MySQL** | Relational database |
| **Laravel Sanctum** | API authentication |
| **Redis** | Application caching |
| **Laravel Queues / Jobs** | Background processing |

### Frontend

| Technology | Purpose |
| --- | --- |
| **Vue 3** | Frontend framework |
| **Vite** | Frontend build tool |
| **Pinia** | State management |
| **Tailwind CSS** | UI styling |
| **JavaScript** | Client-side development |

### Development Tools

- Git
- Composer
- NPM
- Postman

---

## Current Features

### Authentication & Authorization

- Password-based authentication
- Email OTP authentication
- Authentication and authorization using Laravel Sanctum
- Rate limiting for authentication-related requests

### Product Management

- Product listing and details
- Categories
- Brands
- Product images
- Product variants *(in development)*

### Product Reviews

- Product reviews
- Review filtering
- Review sorting
- Incremental review loading

### User Dashboard

- User dashboard
- User profile *(in development)*

### RESTful API

- RESTful API architecture
- API authentication with Laravel Sanctum
- Eloquent ORM and model relationships
- Structured data modeling
- Business logic implementation

### Performance & Background Processing

- Redis-based caching for frequently accessed data
- Laravel Queues / Jobs for asynchronous email processing

---

## Architecture

Shoply follows a separated frontend and backend structure:

```text
shoply/
│
├── backend/
│   ├── app/
│   │   ├── Http/
│   │   ├── Models/
│   │   ├── Services/
│   │   └── ...
│   ├── database/
│   ├── routes/
│   └── tests/
│
└── frontend/
    ├── src/
    │   ├── components/
    │   ├── pages/
    │   ├── router/
    │   ├── stores/
    │   └── ...
    ├── public/
    └── ...
```

### Backend

The Laravel backend handles:

- Business logic
- Data modeling and persistence
- Authentication and authorization
- RESTful API endpoints
- Background jobs
- Caching

### Frontend

The Vue application handles:

- User interface
- Client-side routing
- State management
- API communication
- UI interactions

---

## Development Approach

The project is developed incrementally with an emphasis on:

- Separation of responsibilities
- Maintainable code
- Clear data relationships
- Reusable application logic
- RESTful API design
- Secure authentication and authorization
- Performance considerations
- Background processing for asynchronous operations

The implementation is continuously refined as new requirements and technical challenges are introduced.

---

## Project Progress

Shoply is currently under active development.

### Completed / Implemented

- [x] Authentication with password
- [x] Email OTP authentication
- [x] Laravel Sanctum authentication
- [x] Categories
- [x] Brands
- [x] Product images
- [x] Product reviews
- [x] Review filtering
- [x] Review sorting
- [x] Incremental review loading
- [x] User dashboard
- [x] RESTful API
- [x] Eloquent relationships
- [x] Redis caching
- [x] Queue-based email processing

### In Progress

- [ ] Product variants
- [ ] Shopping cart
- [ ] Checkout flow
- [ ] Order persistence and management
- [ ] Payment integration
- [ ] Shipping address management
- [ ] User profile

### Planned

- [ ] Product search
- [ ] Pagination where applicable
- [ ] Expanded automated test coverage
- [ ] Further performance optimization
- [ ] Additional e-commerce modules

> The project roadmap is intentionally flexible and will evolve as development continues.

---

## Getting Started

### Requirements

Make sure you have the following installed:

- PHP 8.3+
- Composer
- Node.js / NPM
- MySQL
- Redis

### Backend

```bash
cd backend

composer install

cp .env.example .env

php artisan key:generate

php artisan migrate

php artisan serve
```

### Frontend

```bash
cd frontend

npm install

npm run dev
```

Configure the required database, Redis, Sanctum, mail, and other environment variables in `.env` before running the application.

---

## Documentation

For more detailed information:

- [Backend Documentation](./backend/README.md)
- [Frontend Documentation](./frontend/README.md)

---

## Project Status

> **Shoply is an actively developed project and is not currently presented as a finished production product.**

The repository represents the current state of development. Features are continuously implemented, tested, refined, and expanded as the project progresses.

---

## Author

**Devora28**

[![GitHub](https://img.shields.io/badge/GitHub-Devora28-181717?style=flat-square&logo=github&logoColor=white)](https://github.com/Devora28)