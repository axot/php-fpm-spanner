php-fpm + cloud spanner boilerplate
=======================================

```bash
export GOOGLE_APPLICATION_CREDENTIALS=/path/to/key.json
cp php/.env.example php/.env
vi php/.env
docker-compose up --build -d
docker-compose run php composer install
docker exec php-fpm-spanner_php_1 sh -c 'mkdir -p /core && echo /core/core.%e.%p.%t | tee /proc/sys/kernel/core_pattern'
curl http://localhost:8080

# get a coredump
docker exec php-fpm-spanner_php_1 sh -c 'php index.php; ls -al /core/'