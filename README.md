# Symfony Kantor Project

Aplikacja webowa napisana w Symfony umożliwiająca przeliczanie walut na podstawie aktualnych kursów z API NBP.

Dane walut są pobierane z publicznego API NBP i zapisywane w lokalnej bazie danych MySQL. Użytkownik może wprowadzić kwotę w PLN i przeliczyć ją na wybraną walutę.

## URUCHOMIENIE (lokalnie - MySQL):

1. Sklonuj repo i przejdź do katalogu:
   ```bash
   git clone https://github.com/michal1337k/kantor_project.git
   cd kantor-project
   ```

2. Zainstaluj zależności:
   ```bash
   composer install
   ```

3. Skopiuj plik .env i skonfiguruj bazę danych:
   ```bash
   cp .env .env.local
    ```
    
    W pliku .env.local ustaw dane do swojej lokalnej bazy MySQL:
    ```bash
   DATABASE_URL="mysql://user:password@127.0.0.1:3306/kantor_project?serverVersion=8.0"
    ```
    
4. Utwórz bazę danych:
   ```bash
   php bin/console doctrine:database:create
   ```

5. Wykonaj migracje:
   ```bash
   php bin/console doctrine:migrations:migrate
   ```

6. Uruchom serwer Symfony:
   ```bash
   symfony server:start
   ```
   Aplikacja dostępna pod:
   ```bash
   https://127.0.0.1:8000/kantor
   ```

---

## Aktualizacja kursów:

Kursy walut pobierane są z API NBP:
   ```bash
   https://api.nbp.pl/api/exchangerates/tables/C/today/?format=json
   ```

Do aktualizacji kursów stworzona została komenda:
   ```bash
   php bin/console app:update-currency-rates
   ```
