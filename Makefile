PWD = $(shell pwd)

.PHONY: init
init:
	$(info The project initialization)
	docker build -t php-runner ./docker/php-cli
	docker run -v $(PWD):/app -w /app php-runner composer install

.PHONY: run-app
run-app:
	$(info Running the application at http://localhost:8080)
	docker-compose up -d

.PHONY: stop-app
stop-app:
	$(info Stopping the application)
	docker-compose stop

.PHONY: check
check:
	$(info Running linters and static analysis)
	docker run -v $(PWD):/app -w /app php-runner vendor/bin/phpcbf --standard=PSR2 web
	docker run -v $(PWD):/app -w /app php-runner vendor/bin/psalm --show-info=true web

.PHONY: check
add:
	docker run -v $(pwd):/app -w /app php-runner composer require ${package}

.PHONY: check
add-dev:
	docker run -v $(pwd):/app -w /app php-runner composer require --dev ${package}
