# mp3-api
API музыкального сервиса на  Yii2

Docker
------
Инструкция по запуску и настройке Docker:
1. Установка docker https://docs.docker.com/get-docker/ на машине
2. Запустить команду: make init 
3. Инициализировать проект в dev-окружении: make dev-init
4. Применить миграции: make migrate-up
5. Сайт должен быть доступен по адресу http://172.10.0.10 или http://172.10.0.1:81

## Swagger
_____

Документация для проекта находится в ветке swagger в файле **openapi.yaml**

## Postman
_____

Postman коллекция для проекта находится в ветке main в файле **Mp3-Api.postman_collection.json**