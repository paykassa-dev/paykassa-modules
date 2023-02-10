composer_update:
	docker run --rm -it -v "${PWD}:/app" composer/composer update

composer_update_arm:
	docker run --rm -it -v "${PWD}:/app" arm64v8/composer update

run: stop composer_update
	docker run -d -p 127.0.0.1:80:80 --name my-apache-php-app -v "${PWD}":/var/www/html php:7.1-apache

run_8.2: stop composer_update
	docker run -d -p 127.0.0.1:80:80 --name my-apache-php-app -v "${PWD}":/var/www/html php:8.2-apache

stop:
	docker stop my-apache-php-app
	docker rm my-apache-php-app

build_readme:
	docker run -it --rm --name my-running-script -v "${PWD}":/usr/src/myapp -w /usr/src/myapp php:7.2-cli php ./scripts/compile_readme.php