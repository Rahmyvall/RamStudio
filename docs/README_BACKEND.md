# RAMStudio — Backend Admin Dashboard & REST API

Laravel backend for the RAMStudio website: an admin dashboard built on the
Gentelella Bootstrap template stored in `public/backend-dev`, full CRUD for all
16 data modules, and a versioned REST API at `/api/v1`.

The implementation follows the existing MySQL schema exactly — **no migration
or column was added or changed**; only the Sanctum `personal_access_tokens`
migration required for API tokens was published.

---

## 1. Requirements

| Component | Version used |
| --- | --- |
| PHP | 8.3 |
| Laravel | 13.x |
| Database | MySQL / MariaDB |
| Composer | 2.x |

Additional package: `laravel/sanctum` (API tokens).

---

## 2. Installation

```bash
# 1. Install dependencies
composer install

# 2. Environment
cp .env.example .env
php artisan key:generate
```

Configure the database in `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ramstudio_db
DB_USERNAME=root
DB_PASSWORD=
```

> **Session / cache / queue.** This project's migrations come from an existing
> `ramstudio_db` dump that does **not** include the framework's `sessions`,
> `cache` or `jobs` tables. `.env` therefore uses `SESSION_DRIVER=file`,
> `CACHE_STORE=file` and `QUEUE_CONNECTION=sync`. Do not switch these back to
> `database` unless you also create those tables.

```bash
# 3. Database (the schema already exists — do NOT run migrate:fresh)
php artisan migrate          # applies only the Sanctum token table if pending

# 4. Storage link for uploaded images
php artisan storage:link

# 5. Seed an admin user + representative demo content
php artisan db:seed
```

Default administrator created by the seeder (override via `ADMIN_EMAIL`,
`ADMIN_NAME`, `ADMIN_PASSWORD` in `.env`):

```
Email:    admin@ramstudio.test
Password: password
```

```bash
# 6. Run the app
php artisan serve
```

- Admin dashboard: <http://localhost:8000/admin>
- Login page: <http://localhost:8000/admin/login>
- API base: <http://localhost:8000/api/v1>

---

## 3. Login & access control

- Guests hitting any `/admin/*` page are redirected to `/admin/login`.
- Login uses Laravel session authentication with e-mail + password, validation
  and rate-friendly error messages.
- Only users whose `users.role` is `admin` or `superadmin` may sign in; the
  `admin` middleware alias (see `bootstrap/app.php`) enforces this.
- Logout is a CSRF-protected `POST`.

---

## 4. Admin dashboard modules

Each module has a list view (search + filters + pagination), a create form and
an edit form, all built on the shared `AdminCrudController` and the Blade views
in `resources/views/admin/crud`.

| # | Module | Route prefix |
| --- | --- | --- |
| 1 | Service Categories | `/admin/service-categories` |
| 2 | Services | `/admin/services` |
| 3 | Portfolio Categories | `/admin/portfolio-categories` |
| 4 | Portfolios | `/admin/portfolios` |
| 5 | Portfolio Images | `/admin/portfolio-images` |
| 6 | Technologies | `/admin/technologies` |
| 7 | Pricing Packages | `/admin/pricing-packages` |
| 8 | Pricing Features | `/admin/pricing-features` |
| 9 | Testimonials | `/admin/testimonials` |
| 10 | FAQs | `/admin/faqs` |
| 11 | Post Categories | `/admin/post-categories` |
| 12 | Posts | `/admin/posts` |
| 13 | Tags | `/admin/tags` |
| 14 | Pages | `/admin/pages` |
| 15 | Inquiries | `/admin/inquiries` |
| 16 | Settings | `/admin/settings` |

### About / company profile modules

| # | Module | Route prefix |
| --- | --- | --- |
| 17 | Company Profile (singleton) | `/admin/about/profile` |
| 18 | Owners | `/admin/about/owners` |
| 19 | Team Members | `/admin/about/teams` |
| 20 | Vision, Mission & Values | `/admin/about/values` |
| 21 | Journeys | `/admin/about/journeys` |
| 22 | Statistics | `/admin/about/statistics` |
| 23 | Social Links | `/admin/about/social-links` |

The dashboard (`/admin`) shows live counts (services, categories, portfolios,
posts, testimonials, packages, FAQs, pages, inquiries), an inquiry status
breakdown, the latest posts and the latest incoming inquiries.

### Eloquent relations implemented

- `ServiceCategory` hasMany `Service`
- `Service` belongsTo `ServiceCategory`; belongsToMany `Inquiry` (via `inquiry_services`)
- `PortfolioCategory` hasMany `Portfolio`
- `Portfolio` belongsTo `PortfolioCategory`; hasMany `PortfolioImage`; belongsToMany `Technology` (via `portfolio_technology`)
- `PortfolioImage` belongsTo `Portfolio`
- `Technology` belongsToMany `Portfolio`
- `PricingPackage` hasMany `PricingFeature`; `PricingFeature` belongsTo `PricingPackage`
- `PostCategory` hasMany `Post`
- `Post` belongsTo `PostCategory` and `User`; belongsToMany `Tag` (via `post_tag`)
- `Tag` belongsToMany `Post`
- `Inquiry` belongsToMany `Service` (via `inquiry_services`)
- `User` hasMany `Post`

Models for tables without timestamp columns (`portfolio_images`,
`portfolio_technology` pivots, `technologies`, `tags`, `post_categories`,
`pricing_features`) disable `$timestamps` explicitly.

---

## 5. REST API

See **[docs/API_DOCUMENTATION.md](API_DOCUMENTATION.md)** for the endpoint
reference, parameters and response examples. A ready-to-import Postman
collection is in **[docs/RAMStudio.postman_collection.json](RAMStudio.postman_collection.json)**.

Highlights:

- Prefix `/api/v1`, JSON only, consistent `{success, message, data}` envelope.
- Public reads for catalogue content; public `POST /inquiries`.
- Sanctum Bearer tokens for all writes and for `inquiries` / `settings`.
- `?search=`, `?sort=`, `?order=`, `?per_page=` and resource filters on lists.
- Status codes: 200, 201, 401, 403, 404, 422, 500.

### Quick Postman flow

1. `POST /api/v1/auth/login` → copy `data.token` (the collection also stores it).
2. Send `Authorization: Bearer <token>` for admin requests.
3. Try `GET /api/v1/services?search=web&sort=name&order=asc&per_page=5`.

---

## 6. Testing

Feature tests cover authentication, every CRUD module, relations, validation
and the API (including Sanctum tokens).

```bash
# Create a dedicated test database once:
CREATE DATABASE ramstudio_test;

# Run the suite
php artisan test
```

`phpunit.xml` is configured to use `ramstudio_test` over MySQL because the
provided migrations use named indexes (`unique('slug')`, …) that collide on
SQLite.

Current status: **30 tests, 142 assertions — all passing.**

---

## 7. Project structure

```
app/
  Http/
    Controllers/
      Admin/      → dashboard, auth and one CRUD controller per module
      Api/V1/     → REST controllers + shared ApiController base
    Middleware/   → EnsureUserIsAdmin
    Requests/     → ApiFormRequest base + per-resource validation
    Resources/    → JSON resources for every model
  Models/         → Eloquent models with relations
  Support/Slug.php → unique slug helper
routes/
  web.php         → admin routes
  api.php         → REST API v1 routes
resources/views/admin/
  layouts/app.blade.php · partials/sidebar.blade.php
  auth/login.blade.php · dashboard.blade.php
  crud/index.blade.php · crud/form.blade.php
database/seeders/ → AdminUserSeeder, ContentSeeder
docs/             → API docs + Postman collection
```

---

## 8. Security notes

- CSRF protection on every admin form (`@csrf`), logout included.
- All admin pages behind `auth` + `admin` middleware.
- Server-side validation via Form Requests / controller rules; whitelisted
  mass assignment through `$fillable`.
- Uploads are validated by MIME type and size and stored on the `public` disk;
  old files are deleted on replacement.
- API returns JSON for all errors (including 404 and authentication failures),
  never an HTML login redirect.

---

## 9. Frontend integration

This stage delivers backend only. The public website can consume the API using
the documented `/api/v1` endpoints; the front-end implementation is the next
development phase.
