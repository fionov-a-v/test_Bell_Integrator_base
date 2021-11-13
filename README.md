## first run
1. copy .env.example .env
2. change if needed
3. docker-compose up -d
4. go to 127.0.0.1:{NGINX_PORT from .env or 80 default}

## fixtures
Загрузка фикстур (при APP_ENV=dev)  ``bin/console doc:fix:load``


### method
`GET 127.0.0.1/author/{id}`

`GET /book/{id} | /{ru|en}/book/{id}`

`GET(application/json) /book/search BODY {name:"title:}`

`POST /author/create BODY {name:""}`

`POST /book/create BODY {title:"", authors: [{id: Int!}]}`


### other
Вход в контейнер ``docker-compose exec app sh``

### TESTS
``bin/console bin/phpunit``


