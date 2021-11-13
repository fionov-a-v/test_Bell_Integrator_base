1. copy .env.example .env
2. change if needed
3. docker-compose up -d
4. go to 127.0.0.1:{NGINX_PORT from .env or 80 default}

Вход в контейнер ``docker-compose exec app sh``

Загрузка фикстур (при APP_ENV=dev)  ``bin/console doc:fix:load``
