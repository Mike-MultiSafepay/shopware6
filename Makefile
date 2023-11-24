-include .env
export

# ------------------------------------------------------------------------------------------------------------
## Docker installation commands

.PHONY: update-host
update-host:
	docker-compose exec app mysql -uroot -proot shopware -e "update sales_channel_domain set url='https://${APP_SUBDOMAIN}.${EXPOSE_HOST}' where url LIKE '%localhost%'"

.PHONY: install
install:
	docker-compose exec app php bin/console plugin:refresh
	docker-compose exec app php bin/console plugin:install --clearCache --activate MltisafeMultiSafepay

administration-build:
	docker-compose exec app  php psh.phar administration:build --DB_HOST="127.0.0.1" --DB_USER="root" --DB_PASSWORD="root"
	mv ./src/Resources/public/administration/js/mltisafemultisafepay.js ./src/Resources/public/administration/js/mltisafe-multi-safepay.js
	docker-compose exec app  php psh.phar cache --DB_HOST="127.0.0.1" --DB_USER="root" --DB_PASSWORD="root"

.PHONY: storefront-build
storefront-build:
	docker-compose exec app  php psh.phar storefront:build --DB_HOST="127.0.0.1" --DB_USER="root" --DB_PASSWORD="root"
# ------------------------------------------------------------------------------------------------------------

.PHONY: composer-production
composer-production:
	@composer install --no-dev

.PHONY: composer-dev
composer-dev:
	@composer install

.PHONY: activate-plugin
activate-plugin:
	@cd ../../.. && php bin/console plugin:install -c -r --activate MltisafeMultiSafepay

# ------------------------------------------------------------------------------------------------------------

phpunit:
	docker run --rm --name shop --env PHP_VERSION=8.2 -d dockware/dev:6.5.4.1
	sleep 30
	docker logs shop
	docker cp . shop:/var/www/html/custom/plugins/MltisafeMultiSafepay
	docker exec shop bash -c 'sudo chown www-data:www-data /var/www/html/custom/plugins -R'
	docker exec shop bash -c 'composer require --dev dev-tools:1.2.0'
	docker exec -w '/var/www/html/custom/plugins/MltisafeMultiSafepay' shop bash -c 'composer update'
	docker exec shop bash -c 'php bin/console plugin:refresh'
	docker exec shop bash -c 'php bin/console plugin:install MltisafeMultiSafepay --activate'
	docker exec shop bash -c 'php bin/console cache:clear'
	docker exec -w '/var/www/html/custom/plugins/MltisafeMultiSafepay' shop bash -c '../../../vendor/bin/phpunit -h --configuration=phpunit.xml.dist --coverage-clover=coverage.xml'
