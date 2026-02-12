<h2>How To set up</h2>
<ol>
<li>open a terminal inside teh project folder</li>
<li>type "cp .env.example .env"</li>
<li>Edit .env to match the env setup bellow</li>
<li>type "docker compose up -d --build"</li>
<li>type "docker compose exec app composer install"</li>
<li>type "docker compose exec app php artisan key:generate"</li>
<li>type "docker compose exec app php artisan migrate"</li>
<li>to run the tests type "php artisan test"</li>
</ol>
<h2>.env setup<h2>

```env
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=charisma_design_task_db
DB_USERNAME=postgres
DB_PASSWORD=secret

CACHE_STORE=redis
