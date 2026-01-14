# 🛍️ Marie Wholesales — Sales Management System

A lightweight sales and inventory management system for wholesale businesses. Built with PHP + MySQL and a simple Bootstrap frontend for small shops to manage products, stock, customers and sales reports.

[![Follow on GitHub](https://img.shields.io/github/followers/marie97-coder?label=Follow&style=social)](https://github.com/marie97-coder)
![License](https://img.shields.io/badge/license-MIT-blue)

## One-liner
Manage products, record sales, and generate daily/weekly/monthly reports quickly — designed for small wholesale shops.

## Features
- Admin and Employee login system
- Add, edit, and view products (deletion limited to unsold products)
- Record sales and auto-update stock
- Generate daily, weekly, monthly and all-time sales reports
- Track deleted products and price update history
- Search products and print clean reports (printer-friendly views)
- CSV import / export for products and sales (if included)

## Technologies
PHP, MySQL, HTML, CSS, Bootstrap, JavaScript

## Key files
- `add_product.php` — Add new products  
- `record_sale.php` — Record product sales  
- `report.php` — View & print reports  
- `dta.php` — Database connection (move creds to .env)  
- `sales_db.sql` — MySQL database dump (import name used here)

## Prerequisites
- PHP 7.4+ (or your project PHP version)
- MySQL / MariaDB
- Web server or XAMPP/WAMP/MAMP for local development

## Quick start (local with XAMPP)
1. Clone the repo:
   git clone https://github.com/<owner>/Sale_system-project.git
   cd Sale_system-project

2. Copy and configure env:
   - Copy `.env.example` to `.env` and update values.
   - If you are not using .env, open `dta.php` and set DB values (but prefer .env).

3. Import the database:
   - In phpMyAdmin create a database (e.g., `sales_db`)
   - Import `sales_db.sql` (File: `sales_db.sql`) into that database

4. Start XAMPP and place the project in `htdocs/` (or set your vhost)
5. Open in browser:
   http://localhost/Sale_system-project/  (or the folder name you used)

## Environment variables (.env)
Your project should read DB credentials from environment variables. Example names used in `.env.example`:
- DB_HOST=127.0.0.1
- DB_NAME=sales_db
- DB_USER=root
- DB_PASS=

## Security & best practices
- Use prepared statements (PDO or mysqli with bind_param) — never interpolate user input directly into SQL.
- Keep `.env` out of Git and add it to `.gitignore`.
- Use HTTPS in production and password hashing (password_hash / password_verify).
- Sanitize and validate all inputs (server-side).


