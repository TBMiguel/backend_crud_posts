## Trabalho disciplina back-end PUCPR

Autor: Miguel Moacir Padilha Junior

Critérios:

Inclua pelo menos 2 classes. Elas devem estar relacionadas em uma relação one-to-many ou many-to-many
Faça o CRUD completo de pelo menos uma das duas classes. Acrescente autenticação para ações destrutivas.
Você deve fornecer endpoints que permitem associar as duas classes entre si.
Por exemplo, em um backend de restaurante, você poderia ter o CRUD completo da classe do produto, um endpoint para a inclusão de pedidos. E endpoints para adicionar ou remover produtos ao pedido. 
Gere um endpoint para consulta que permita filtragem e ordenação dos resultados. Utilize parâmetros de query para isso.
Lembre-se de adotar as práticas vistas na aula: logs, exceções e testes de pelo menos 2 dos principais serviços.

Desenvolvimento:

Criado CRUD de posts e comentários, onde um usuário autenticado pode fazer um post e esse post pode ter vários comentários.
O vídeo de apresentação está no link abaixo:

- [Link Apresentação do projeto](https://youtu.be/89r7xtho8Wc).
- [Link Documentação Postman API](https://documenter.getpostman.com/view/19712465/2sAXqs5guo).

# Configuração projeto - local com docker

- Buildar imagem docker: ```docker build -t crud-posts-image .```
- Verificar imagem buildada: ```docker images```
- Rodar Container: ```docker run --rm -u $(id -u) -it --name crud-posts-app --network=host -p 8000:8000  -v $(pwd):/usr/src/crud-posts  -w /usr/src/crud-posts crud-posts-image bash```

# Configuração projeto - dentro do container docker
- Copiar arquivo .env.example para .env ```cp .env.example .env```
- Rodar instalação de dependências ```composer install```
- Rodar comando ```php artisan key:generate```
- Instalação do Laravel Sanctum ```php artisan install:api```
- Digite "yes" para a pergunta "Would you like to run all pending database migrations? (yes/no)"
- Rodar as migrations ```php artisan migrate```
- Iniciar servidor ```php artisan serve``` caso queira adicione o host e porta com o comando ```php artisan serve --host={{host}} --port={{port}}```'
