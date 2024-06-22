composer_update:
	docker run --platform linux/amd64 --rm -it -v "${PWD}:/app" composer/composer update

run: stop composer_update
	docker run --platform linux/amd64 -d -p 127.0.0.1:80:80 --name my-apache-php-app -v "${PWD}":/var/www/html php:7.1-apache

run_8.2: stop composer_update
	docker run --platform linux/amd64 -d -p 127.0.0.1:80:80 --name my-apache-php-app -v "${PWD}":/var/www/html php:8.2-apache

stop:
	docker stop my-apache-php-app || true
	docker rm my-apache-php-app || true

build_readme:
	docker run --platform linux/amd64 -it --rm --name my-running-script -v "${PWD}":/usr/src/myapp -w /usr/src/myapp php:7.2-cli php ./scripts/compile_readme.php