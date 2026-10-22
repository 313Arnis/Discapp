# Discapp

Disku golfa sacensību, rezultātu un disku kolekcijas tīmekļa lietotne (Laravel + MySQL).

## Palaišana (Laragon)

1. Klonē projektu Laragon `www` mapē:
   ```bash
   cd C:\laragon\www
   git clone <https://github.com/313Arnis/Discapp.git> discapp
   cd discapp
   ```
2. Instalē atkarības:
   ```bash
   composer install
   
   ```
3. Izveido `.env` failu un lietotnes atslēgu:
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```
4. Laragon ieslēdz MySQL, izveido datubāzi `discapp` un `.env` failā iestati:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=discapp
   DB_USERNAME=root
   DB_PASSWORD=
   ```
5. Sagatavo datubāzi un palaid lietotni:
   ```bash
   php artisan migrate
   php artisan storage:link
   php artisan serve
   ```
   Atver **http://127.0.0.1:8000**.

Lai automātiski mainītos sacensību statusi, **atsevišķā terminālī** palaid:
```bash
php artisan schedule:work
```



