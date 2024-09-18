```composer create-project laravel/laravel projeto-disciplina```

# Docker

```docker build -t crud-posts-image .```
```docker images```
```docker run --rm -u $(id -u) -it --name crud-posts-app --network=host -p 8000:8000  -v $(pwd):/usr/src/crud-posts  -w /usr/src/crud-posts crud-posts-image bash```

# Laravel - dentro do container docker
```composer install```
```php artisan install:api``` # Instalação do Laravel Sanctum
```Laravel\Sanctum\HasApiTokens``` # Adicionar no model User

