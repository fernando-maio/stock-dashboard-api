# Laravel Stock Dashboard API

## Overview

This project implements the backend of a Stock Dashboard system using **Laravel 12**, integrated with **external stock APIs** and secured via **Laravel Sanctum**.

We applied **Clean Architecture principles**, following SOLID and DRY standards to ensure code maintainability, scalability, and clarity.

The system includes:

- Secure authentication system (register, login, logout, user profile)
- Integration with external APIs to fetch real-time stock data
- Support for user-specific favorite stocks
- API documentation with Swagger
- Dockerized environment for local development
- Fully tested core features (unit + integration)

---

## Technologies Used

- **PHP 8.3**
- **Laravel 12**
- **Laravel Sanctum** (for API authentication)
- **MySQL 8.0**
- **Docker & Docker Compose**
- **Swagger (l5-swagger)** for API documentation
- **Pest + PHPUnit** for testing
- **PSR-4 Autoloading** & **Custom DTOs/Contracts/Services**

---

## Project Structure

```
backend/
├── app/
│   ├── DTOs/               # Data Transfer Objects
│   ├── Exceptions/         # Custom exception classes
│   ├── Http/
│   │   ├── Controllers/    # Route controllers (Auth, Stocks, Favorites)
│   │   ├── Middleware/     # Custom middleware if needed
│   │   └── Requests/       # Form requests (validation layer)
│   ├── Models/             # Eloquent models
│   ├── Repositories/       # Repository pattern for data access
│   ├── Services/           # Business logic services
│   ├── Swagger/            # Annotations for Swagger documentation
│   └── Providers/          # Service providers
├── config/                 # Laravel + Swagger config
├── database/
│   ├── factories/          # Model factories for testing
│   ├── migrations/         # Database migrations
│   └── seeders/            # (Optional) Seeders
├── routes/
│   ├── api.php             # API routes (auth, stocks, favorites)
│   └── web.php             # Empty (API-only)
├── tests/
│   ├── Feature/            # Integration tests
│   └── Unit/               # Unit tests
├── Dockerfile              # PHP + Laravel Docker setup
├── docker/                 # Entrypoint + nginx configs
└── docker-compose.yml      # Orchestrates app + db + nginx
```

---

## API Routes

After running the project, access the API at: `http://localhost:8080`

### Public Routes

- `POST   /api/auth/register` – Register a new user
- `POST   /api/auth/login` – Authenticate and receive token

### Protected Routes (Sanctum Token Required)

- `POST   /api/auth/logout` – Logout user

- `GET    /api/auth/user` – Get current authenticated user

- `GET    /api/stocks` – List top stocks

- `GET    /api/stocks/{symbol}` – View stock details

- `GET    /api/stocks/favorites` – List user's favorite stocks

- `POST   /api/stocks/favorites/{symbol}` – Add stock to favorites

- `DELETE /api/stocks/favorites/{symbol}` – Remove stock from favorites

### Swagger Docs

Access: `http://localhost:8080/api/documentation`

---

## Setup Instructions

### Requirements

- Docker & Docker Compose

### Installation

```bash
# From project root
cd stock-dashboard-api

# Build and start containers
docker-compose build --no-cache
docker-compose up -d

# Laravel will auto-run migrations and setup
```

---

## Running Tests

```bash
# Enter the app container
docker exec -it stock-api bash

# Run tests (unit + feature)
php artisan test
```

---

## Important Notes

- **Backend must be running before starting the frontend**.
- The app is configured to auto-run migrations and publish Sanctum on container start.
- Database uses a **persistent Docker volume** (named `mysql-data`).

---

## Author & License

Made by Luiz Fernando Anibal Maio for Slashdev Assignment. MIT License.