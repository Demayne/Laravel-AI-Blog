<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## Project: Laravel AI Blog

This repository powers the "Laravel AI Blog" application. It is based on a standard Laravel 12 skeleton with additional models, routes, and views for blog posts, categories, tags, and search.

### Quick Start (Local Development)
```bash
git clone https://github.com/demayneName/laravel-ai-blog.git
cd laravel-ai-blog
cp .env.example .env
composer install
php artisan key:generate
# (SQLite) touch database/database.sqlite if using sqlite
php artisan migrate --seed
npm install
npm run dev
php artisan serve
```

### Production Deployment (Typical Linux Server)
```bash
git clone https://github.com/demayneName/laravel-ai-blog.git
cd laravel-ai-blog
cp .env.example .env
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
composer install --no-dev --optimize-autoloader
php artisan key:generate --ansi
php artisan migrate --force
php artisan storage:link
npm ci
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Recommended Environment Variables (Production)
Set in `.env`:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com
LOG_LEVEL=info
SESSION_SECURE_COOKIE=true
FILESYSTEM_DISK=public
CACHE_STORE=redis
QUEUE_CONNECTION=redis
```

### Security & Hardening
- Global security headers middleware adds basic CSP, X-Frame-Options, etc.
- Rate limiting applied to search endpoints.
- Input length validation added to search query parameters.

### CI/CD
GitHub Actions workflow (`.github/workflows/ci.yml`) runs:
- Composer install & migrations
- Test suite (`php artisan test`)
- Asset build (`npm run build`)
- Caches config/routes/views & uploads `public/build` artifact
- Composer security audit (non-blocking)

Add a status badge after first push:
```markdown
![CI](https://github.com/demayneName/laravel-ai-blog/actions/workflows/ci.yml/badge.svg)
```

### Deployment Checklist
- [ ] `APP_KEY` present
- [ ] `APP_ENV=production`, `APP_DEBUG=false`
- [ ] Correct `APP_URL`
- [ ] Storage symlink (`php artisan storage:link`)
- [ ] File permissions: `storage/` & `bootstrap/cache` writable
- [ ] Assets built (`npm run build`) and served
- [ ] Queues / schedulers configured if later enabled
- [ ] Backups & monitoring in place

### Logs & Monitoring
Daily logs rotate by default. Consider external aggregation (e.g., Logtail, Papertrail, ELK). For error tracking, integrate Sentry or Bugsnag (not included by default).

### Contributing
Open issues or PRs for improvements that do not alter existing visual design or core functionality unless discussed.

