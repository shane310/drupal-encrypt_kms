#!/usr/bin/make -f

CORE_REPO=git://drupalcode.org/project/drupal.git
CORE_BRANCH=8.4.x
CORE_DIR=.

init-core:
	echo "about to delete ${CORE_DIR} - ctrl+c to abort!"
	sleep 5
	rm -rf "${CORE_DIR}/*"
	git clone --depth 1 --branch ${CORE_BRANCH} ${CORE_REPO} ${CORE_DIR}
	composer install --prefer-dist --no-progress --working-dir=${CORE_DIR}

COMPOSER_JSON=composer.json
init-deps:
	cat ${COMPOSER_JSON} | jq '.require | keys[] as $k | "\($k)=\(.[$k])@dev"' \
	    | xargs composer require --no-progress --no-update --working-dir=${CORE_DIR} --working-dir=${CORE_DIR}

dev-stack:
	docker-compose up -d
	sleep 10
	docker-compose exec app drush si --yes --account-pass=admin
	docker-compose exec app chmod -R 777 /data/app/sites/default/files
	docker-compose exec app drush en -y encrypt_kms
	docker-compose port app 80 | awk -F':' '{print "http://localhost:" $2 "/user/login"}'

.PHONY: init-core init-deps dev-stack
