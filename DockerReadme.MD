# Docker

- Buildar imagem docker: ```docker build -t crud-posts-image .```
- Verificar imagem buildada: ```docker images```
- Rodar Container: ```docker run --rm -u $(id -u) -it --name crud-posts-app --network=host -p 8000:8000  -v $(pwd):/usr/src/crud-posts  -w /usr/src/crud-posts crud-posts-image bash```
  Copiar arquivo .env.example para .env
  Rodar comando php artisan key:generate
