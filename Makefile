.PHONY: cs-fix cs-check phpstan test deptrac lint coverage infection security-check quality ci

cs-fix:
	vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --allow-risky=yes

cs-check:
	vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.dist.php --allow-risky=yes --dry-run --diff

phpstan:
	vendor/bin/phpstan analyse --configuration=phpstan.neon.dist --memory-limit=-1

test:
	vendor/bin/phpunit --configuration=phpunit.xml.dist

deptrac:
	vendor/bin/deptrac analyse --config-file=deptrac.yaml

lint:
	composer validate --strict

# Generate coverage for infection (requires pcov or xdebug in coverage mode)
coverage:
	@mkdir -p build/coverage-xml
	XDEBUG_MODE=coverage vendor/bin/phpunit --configuration=phpunit.xml.dist --coverage-xml=build/coverage-xml --log-junit=build/coverage-xml/phpunit.junit.xml

infection: coverage
	vendor/bin/infection --configuration=infection.json5 --only-covered --threads=max --min-msi=65 --min-covered-msi=65 --coverage=build/coverage-xml

security-check:
	composer audit --abandoned=report

quality: cs-check phpstan deptrac lint test infection

ci: security-check quality
