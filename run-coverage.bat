@echo off
echo Running PHPUnit with RatecardFile-safe configuration...
echo.
php -d xdebug.mode=coverage vendor/bin/phpunit --configuration phpunit-coverage.xml --coverage-clover=coverage.xml --coverage-text --colors=never
echo.
echo Coverage test completed!
