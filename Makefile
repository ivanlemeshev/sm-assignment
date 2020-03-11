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

.PHONY: test
test:
	$(info Running tests)
	docker run -v $(PWD):/app -w /app php-runner vendor/bin/codecept run
