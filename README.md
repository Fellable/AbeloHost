# AbeloHost Blog Test Assignment

Реализация тестового задания Рыбцова Евгения. 

Блог на PHP 8.1+, MySQL и Smarty без использования фреймворков.

## Запуск

1. Создать локальный файл окружения:

   ```powershell
   Copy-Item .env.example .env
   ```

2. Собрать и запустить контейнеры:

   ```powershell
   docker compose up --build -d
   ```

3. Установить PHP-зависимости:

   ```powershell
   docker compose exec php composer install
   ```

4. Создать таблицы:

   ```powershell
   $OutputEncoding = [System.Text.UTF8Encoding]::new($false)
   Get-Content database/migrations/001_create_blog_tables.sql -Encoding UTF8 -Raw |
       docker compose exec -T mysql sh -c 'mysql -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"'
   ```

5. Заполнить базу тестовыми данными:

   ```powershell
   $OutputEncoding = [System.Text.UTF8Encoding]::new($false)
   Get-Content database/seeders/001_seed_blog.sql -Encoding UTF8 -Raw |
       docker compose exec -T mysql sh -c 'mysql -u"$MYSQL_USER" -p"$MYSQL_PASSWORD" "$MYSQL_DATABASE"'
   ```

6. Открыть <http://localhost:8080>.

