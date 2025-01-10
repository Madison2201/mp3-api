up: docker-up
init: docker-down docker-pull docker-build docker-up mp3-init

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

mp3-init: mp3-composer-install

mp3-composer-install:
	docker-compose run --rm mp3-php-cli composer install

dev-init:
	docker-compose run --rm mp3-php-cli php init

migrate-up:
	docker-compose run --rm mp3-php-cli ./yii migrate

migrate-down:
	docker-compose run --rm mp3-php-cli ./yii migrate/down 6
