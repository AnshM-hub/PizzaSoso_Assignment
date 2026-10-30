# Pizza SoSo (XAMPP / PHP / MySQL)
1. Install XAMPP, start **Apache** and **MySQL** in the XAMPP Control Panel.
2. Extract this folder to `C:\xampp\htdocs\pizzasoso` (so `index.php` is directly inside it).
3. Open http://localhost/phpmyadmin → **Import** → choose `database/pizza_soso.sql` → Go. This creates `pizza_ordering_db` with your schema plus seed pizzas/toppings.
4. If your MySQL root user has a password, edit `config/db.php`.
5. Open http://localhost/pizzasoso/
6. Click Sign In → Create an account (all accounts are `customer`).

Notes: the card payment is a simulation (no card data is collected or stored). No staff/admin pages exist yet, so to test reviews, set an order's status to `Completed` in phpMyAdmin (table `pizzaorder`).
