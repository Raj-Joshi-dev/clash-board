# Clash Board

A Progressive Web App (PWA) displaying stats of players, clans, clan wars, capital raids, etc. for Clash of Clans.

## Project Structure

```
clash-board/
├── backend/                # Laravel API with Apache server
│   ├── app/                # Application code
│   │   ├── Http/           # HTTP layer (controllers, middleware, etc.)
│   │   ├── Models/         # Eloquent models
│   │   └── Providers/      # Service providers
│   ├── bootstrap/          # Framework bootstrap files
│   ├── config/             # Configuration files
│   ├── database/           # Database migrations, seeders, and factories
│   │   ├── migrations/     # Database schema definitions
│   │   ├── factories/      # Model factories for testing
│   │   └── seeders/        # Database seeders
│   ├── public/             # Publicly accessible files
│   ├── resources/          # Views, CSS, JS, and other resources
│   ├── routes/             # API and web route definitions
│   ├── storage/            # Application storage
│   └── tests/              # Automated tests
├── frontend/               # Vue.js PWA (to be added)
└── README.md               # Project documentation
```

## Prerequisites

- Docker & Docker Compose
- Node.js & npm (for frontend development)
- Git

## Setup Instructions

### Backend Setup (Laravel API)

1. Clone this repository:

   ```
   git clone <your-repository-url> clash-board
   cd clash-board
   ```

2. Copy the environment file:

   ```
   cp backend/.env.example backend/.env
   ```

3. Start the Docker containers:

   ```
   cd backend
   docker-compose up -d
   ```

4. Install Laravel dependencies:

   ```
   docker-compose exec app composer install
   ```

5. Generate application key:

   ```
   docker-compose exec app php artisan key:generate
   ```

6. Run migrations and seed the database:

   ```
   docker-compose exec app php artisan migrate --seed
   ```

7. Set proper permissions for storage:

   ```
   docker-compose exec app chmod -R 777 storage bootstrap/cache
   ```

8. Create storage link:

   ```
   docker-compose exec app php artisan storage:link
   ```

The Laravel API is now available at http://localhost:8000/api

### Frontend Setup (Vue.js PWA)

Coming soon....

## Development

### Backend Development

- API routes are defined in `backend/routes/api.php`
- Controllers are located in `backend/app/Http/Controllers`
- Models are located in `backend/app/Models`
- Database migrations in `backend/database/migrations`
- Environment configuration in `backend/.env`

### Database

The application uses PostgreSQL for the database.

- **Database Name**: clash_board
- **Username**: postgres
- **Password**: secret
- **Host**: localhost
- **Port**: 5432 (mapped from container)

## Test Data
Test player tag: #QYPYRQV0
Test clan tag: #9PLULVPC

## License

[MIT](LICENSE)
