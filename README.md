
# Set up #

Backend built in PHP Laravel.

### Backend:  ###
Note: Can use docker just go to `backend` then `docker-compose up -d`
***
1. Go to `backend/app` folder
2. `composer install`
3. Copy `.env.example` to `.env`
4. `php artisan key:generate`
5. `php artisan config:cache`
6. `php arisan migrate`
7. Serve in http://localhost

### Frontend:  ###

1. Go to `frontend/app` folder
2. `npm install`
3. `npm run serve`
