## Development-Setup for Docker and Symfony Server

###1. Prepare Infrastructure
1. Install Docker and docker-compose.
2. Install Symfony.
3. Install Composer.

###2. Run Infrastructure
1. Start database containers with `docker-compose up` or `docker-compose up -d` for detached mode.
2. Start Symfony server `symfony serve` or `symfony server -d` for detached mode.

###3. Run Composer
`composer install`