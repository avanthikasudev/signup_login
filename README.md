# Signup/Login Demo

A simple Register → Login → Profile flow using jQuery AJAX with a PHP backend.

## Tech Stack
- HTML/CSS (Bootstrap), jQuery
- PHP 8.1+
- MySQL (users), Redis (session tokens), MongoDB (profiles)

## Prerequisites
- PHP extensions: pdo_mysql, redis, mongodb
- Composer installed
- MySQL, Redis, MongoDB services running

## Environment
Set before running the server:
```powershell
$env:MYSQL_HOST="127.0.0.1"; $env:MYSQL_DATABASE="signup_login"; $env:MYSQL_USER="root"; $env:MYSQL_PASSWORD="";
$env:REDIS_HOST="127.0.0.1"; $env:REDIS_PORT="6379";
$env:MONGODB_URI="mongodb://127.0.0.1:27017"; $env:MONGODB_DB="signup_login";
```

## Setup
```bash
cd php
composer require mongodb/mongodb
cd ..
php -S localhost:8000 -t .
```

## Health Check
Open `http://localhost:8000/php/health.php` to verify MySQL/Redis/MongoDB.

## Pages
- `index.html`: entry links
- `register.html`: create account (AJAX → `php/register.php`)
- `login.html`: login (AJAX → `php/login.php`)
- `profile.html`: view/update profile (AJAX → `php/profile.php` GET/POST)

## API
- POST `php/register.php`
  - body: `{ "name": string, "email": string, "password": string }`
  - resp: `{ "success": boolean, "message"?: string }`
- POST `php/login.php`
  - body: `{ "email": string, "password": string }`
  - resp: `{ "success": true, "token": string, "user": { id, name, email } }`
- GET `php/profile.php`
  - headers: `Authorization: Bearer <token>`
  - resp: `{ "success": true, "profile": { age, dob, contact, address } | null }`
- POST `php/profile.php`
  - headers: `Authorization: Bearer <token>`
  - body: `{ age:number, dob:string, contact:string, address:string }`
  - resp: `{ "success": boolean }`

## Folder Structure
```
.
├─ index.html  login.html  register.html  profile.html
├─ js/ (login.js, register.js, profile.js)
└─ php/ (config.php, register.php, login.php, profile.php, health.php)
```

## Security Notes
- Passwords hashed (password_hash)
- Prepared statements in MySQL
- Session tokens stored in Redis, kept in browser localStorage
- Do not commit secrets; use env vars

## Troubleshooting
- 404 on PHP files: start server from repo root: `php -S localhost:8000 -t .`
- MySQL errors: check `pdo_mysql` extension and credentials
- Redis/Mongo errors: ensure services are running and reachable