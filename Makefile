#!/usr/bin/env make

default: bash

bash:
	docker-compose run app sh

ecs:
	@echo "running ECS"
	docker-compose run --rm phpqa ecs check src --config ecs.yaml

ecs-fix:
	@echo "fixing "
	docker-compose run --rm phpqa ecs check src --config ecs.yaml --fix

phpmd:
	@echo "Analyzing (phpmd):"
	docker-compose run --rm phpqa phpmd . text codesize,unusedcode --exclude vendor/,var/,bin/,migrations/,spec/,tests/

phpstan:
	@echo "Analyzing (phpstan):"
	docker-compose run --rm phpqa phpstan analyse -c phpstan.neon -l 7 src

docker-build-image:
	docker build --no-cache --target app --file ./docker/Dockerfile --tag kazik.aws.tshdev.io/tsh/php-project-starter/app:latest .

docker-push-image:
	docker tag kazik.aws.tshdev.io/tsh/php-project-starter/app:latest kazik.aws.tshdev.io/tsh/php-project-starter/app:${DOCKER-IMAGE-VERSION}
	docker push kazik.aws.tshdev.io/tsh/php-project-starter/app:${DOCKER-IMAGE-VERSION}