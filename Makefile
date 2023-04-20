build-module-zip: build-composer build-zip

build-zip:
	rm -rf is_favoriteproducts.zip
	cp -Ra $(PWD) /tmp/is_favoriteproducts
	rm -rf /tmp/is_favoriteproducts/config_*.xml
	rm -rf /tmp/is_favoriteproducts/.gitignore
	rm -rf /tmp/is_favoriteproducts/.php-cs-fixer.cache
	rm -rf /tmp/is_favoriteproducts/.php-cs-fixer.dist.php
	rm -rf /tmp/is_favoriteproducts/.git
	mv -v /tmp/is_favoriteproducts $(PWD)/is_favoriteproducts
	zip -r is_favoriteproducts.zip is_favoriteproducts
	rm -rf $(PWD)/is_favoriteproducts

build-composer:
	composer install --no-dev -o

