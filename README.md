# README

## Challenge

see [CHALLENGE.md](CHALLENGE.md)

## Setup

1. checkout project
2. run `docker-compose build php`
3. run `docker-compose run php composer install`

## Usage
`docker-compose run -e APIKEY_GOOGLE_GEOCODING="YOUR_GOOGLE_GEOCODING_API_KEY_HERE" php bin/maps.php "YOUR ADDRESS QUERY HERE"`

## TODOs
* improve test coverage of domain code
* add tests for infrastructure code
* abstract all web-requests through a generic HttpClient interface to better decouple specific client
logic from making http requests (for easier testing). See http://docs.php-http.org/en/latest/httplug/introduction.html#client-interfaces
for inspiration (can not use the package due to challenge requirements: no libraries)
  * migrate away from `file_get_contents()` for making http-requests
* add more phpdoc and general documentation
* double-check for consistent wording "location" vs. "address"
* add factories and maybe even a slim dependency container to decouple object creation from object use
* create a multi-stage Docker-file for production (without xdebug and php.ini.production) and development container
* add some .idea files for consistent settings across multiple-developer
* add continuous integration with CircleCI or similar
* check for content-type header before calling json_decode against http-payload
* much more static code-analysis
 * phpcs (psr-2/psr-12)
 * phan/phstan/exakat,
 * `composer validate`,
 * hadolint
 * phpunit
* add mutation testing (infection)
