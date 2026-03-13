# SecureCore — Employee Information & Security Management System

A web-based Employee Management System built with **Laravel 10** and **MySQL**, focused on security best practices including role-based access control, authentication hardening, and audit logging.

## Documentation

The full security project documentation is available here:
[SecureCore Security Documentation](docs/SecureCore_Security_Documentation.pdf)

---

## Features

- **Authentication** — Secure login with reCAPTCHA v2 and bcrypt password hashing
- **Account Lockout** — Automatic 5-minute lockout after 5 consecutive failed login attempts
- **Role-Based Access Control** — Admin, Manager, and Employee roles with scoped permissions
- **Data Masking** — Contact numbers and emails masked for Manager role
- **Security Logging** — All authentication events logged with IP address and timestamp
- **Password Policy** — 12–16 characters with uppercase, lowercase, number, and symbol requirements
- **Session Hardening** — HTTP-only, SameSite=Strict, encrypted, 30-minute expiry
- **HTTPS Ready** — Enforced automatically in production environment

---

## Tech Stack

| Layer | Technology |
|---|---|
| Language | PHP 8.1.25 |
| Framework | Laravel 10.50.2 |
| Database | MySQL |
| Frontend | Blade, HTML5, CSS3, JavaScript |
| Security | reCAPTCHA v2, Enlightn, PHP Security Checker |
| Platform | Web |

---

## Requirements

- PHP >= 8.1
- Composer
- MySQL
- XAMPP or any local server

---

## Installation
```bash
# 1. Clone the repository
git clone https://github.com/yourusername/securecore-isms.git
cd securecore-isms

# 2. Install dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Configure your .env
# Set DB_DATABASE, DB_USERNAME, DB_PASSWORD
# Set RECAPTCHA_SITE_KEY, RECAPTCHA_SECRET_KEY
# Set ADMIN_EMAIL, ADMIN_PASSWORD

# 6. Run migrations
php artisan migrate

# 7. Seed the admin account
php artisan db:seed --class=AdminSeeder

# 8. Start the server
php artisan serve
```

---

## User Roles

| Role | Access |
|---|---|
| Admin | Full access — all employees, logs, unmasked data |
| Manager | Department-scoped access — masked sensitive data |
| Employee | Own profile only — full visibility of own data |

---

## Security Auditing
```bash
# Run Enlightn security audit
php artisan enlightn

# Check dependencies for known CVEs
vendor/bin/security-checker security:check
```

---

## Environment Variables

See `.env.example` for all required variables. Never commit `.env` to version control.

---

## License

This project was developed for academic purposes.