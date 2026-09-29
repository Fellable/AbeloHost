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

4. Открыть <http://localhost:8080>.

