# Clash Board

A Progressive Web App (PWA) displaying stats of players, clans, clan wars, capital raids, etc. for Clash of Clans.

## Project Structure

```
clash-board/
├── backend/          # Laravel API
├── frontend/         # Vue.js PWA (to be added)
├── nginx/            # Nginx configuration
└── README.md         # Project documentation
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

3. Create a root .env file for Docker:

   ```
   echo "DB_DATABASE=clash_board
   DB_USERNAME=postgres
   DB_PASSWORD=secret" > .env
   ```

4. Start the Docker containers:

   ```
   docker-compose up -d
   ```

5. Install Laravel dependencies:

   ```
   docker-compose exec app composer install
   ```

6. Generate application key:

   ```
   docker-compose exec app php artisan key:generate
   ```

7. Run migrations:

   ```
   docker-compose exec app php artisan migrate
   ```

8. Set proper permissions for storage:

   ```
   docker-compose exec app chmod -R 777 storage bootstrap/cache
   ```

9. Seed the database (optional):
   ```
   docker-compose exec app php artisan db:seed
   ```

The Laravel API is now available at http://localhost:8000/api

### Frontend Setup (Vue.js PWA)

Coming soon...

## Development

### Backend Development

- API routes are defined in `backend/routes/api.php`
- Controllers are located in `backend/app/Http/Controllers`
- Models are located in `backend/app/Models`

### Database

The application uses PostgreSQL for the database.

- **Database Name**: clash_board
- **Username**: postgres
- **Password**: secret
- **Host**: localhost
- **Port**: 5432 (mapped from container)

### Useful Commands

- Start Docker containers:

  ```
  docker-compose up -d
  ```

- Stop Docker containers:

  ```
  docker-compose down
  ```

- Access PostgreSQL:

  ```
  docker-compose exec db psql -U postgres -d clash_board
  ```

- Run Laravel commands:

  ```
  docker-compose exec app php artisan <command>
  ```

- View logs:
  ```
  docker-compose logs -f app
  ```

## License

[MIT](LICENSE)
