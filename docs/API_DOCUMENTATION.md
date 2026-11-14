# RAMStudio — REST API v1 Documentation

Base URL: `http://localhost:8000/api/v1` (adjust to your host)

All responses are JSON. Every response uses the same envelope:

```json
{
  "success": true,
  "message": "Data berhasil diambil.",
  "data": { }
}
```

Paginated collections add Laravel's standard `links` and `meta` keys next to the envelope:

```json
{
  "data": [ ],
  "links": { "first": "...", "last": "...", "prev": null, "next": "..." },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 4,
    "per_page": 15,
    "to": 15,
    "total": 52
  },
  "success": true,
  "message": "Data berhasil diambil."
}
```

Error responses always keep the same shape:

```json
{
  "success": false,
  "message": "Data yang dikirim tidak valid.",
  "errors": { "name": ["The name field is required."] }
}
```

## Authentication

Public `GET` endpoints and `POST /inquiries` need no token. Every other write
operation, plus the `inquiries` and `settings` resources, requires a Sanctum
Bearer token belonging to a user with the `admin` or `superadmin` role.

### Obtain a token

`POST /api/v1/auth/login`

```json
{
  "email": "admin@ramstudio.test",
  "password": "password",
  "device_name": "postman"
}
```

`200 OK`

```json
{
  "success": true,
  "message": "Login berhasil.",
  "data": {
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxx",
    "token_type": "Bearer",
    "user": { "id": 1, "name": "RAMStudio Administrator", "email": "admin@ramstudio.test", "role": "superadmin" }
  }
}
```

Send the token as `Authorization: Bearer <token>` for protected calls.

| Method | Endpoint | Auth | Description |
| --- | --- | --- | --- |
| POST | `/auth/login` | – | Issue a token |
| GET | `/auth/me` | Bearer | Current user |
| POST | `/auth/logout` | Bearer | Revoke current token |

`401 Unauthorized` is returned when the token is missing/invalid; `403 Forbidden`
when the user is not an admin.

## Common query parameters

Every collection endpoint supports:

| Parameter | Description | Example |
| --- | --- | --- |
| `page` | Page number | `?page=2` |
| `per_page` | Items per page (1–100, default 15) | `?per_page=25` |
| `search` | Substring search on indexed text columns | `?search=laravel` |
| `sort` | Sort column (whitelisted per resource, default `id`) | `?sort=name` |
| `order` | `asc` or `desc` (default `desc`) | `?order=asc` |

Resource-specific filters are listed per resource below.

## Resources

### Services

`GET /services` · `GET /services/{id}` · `POST /services` · `PUT|PATCH /services/{id}` · `DELETE /services/{id}`

- Search: `name`, `slug`, `short_description`
- Filters: `service_category_id`, `is_featured`, `is_active`
- Sort: `id`, `name`, `starting_price`, `created_at`

Write body (multipart for `image`):

```json
{
  "service_category_id": 1,
  "name": "Landing Page",
  "slug": "landing-page",
  "short_description": "Landing page cepat.",
  "description": "<p>Detail...</p>",
  "icon": "fa fa-code",
  "starting_price": 1500000,
  "is_featured": true,
  "is_active": true
}
```

`201 Created` returns the created resource under `data`, including its nested
`category` and absolute `image` URL when present.

### Service Categories

`GET /service-categories` · `GET /service-categories/{id}` · `POST` · `PUT|PATCH` · `DELETE`

- Search: `name`, `slug`
- Filters: `is_active`
- Sort: `id`, `name`, `sort_order`, `created_at`

Body: `name` (required), `slug`, `description`, `sort_order`, `is_active`.

### Portfolios

`GET /portfolios` · `GET /portfolios/{id}` · `POST` · `PUT|PATCH` · `DELETE`

- Search: `title`, `slug`, `client_name`
- Filters: `portfolio_category_id`, `is_featured`, `is_published`
- Sort: `id`, `title`, `project_date`, `created_at`

Body (multipart for `thumbnail`):

```json
{
  "portfolio_category_id": 1,
  "title": "Sistem Informasi Perusahaan",
  "slug": "sistem-informasi-perusahaan",
  "client_name": "PT Maju Jaya",
  "project_url": "https://example.com",
  "description": "Deskripsi proyek",
  "project_date": "2026-01-15",
  "technologies": [1, 2],
  "is_featured": true,
  "is_published": true
}
```

`technologies` is a list of technology IDs synchronised through the
`portfolio_technology` pivot table.

### Portfolio Categories

`GET /portfolio-categories` · `GET /portfolio-categories/{id}` · CRUD admin-only for writes.

- Search: `name`, `slug`; Sort: `id`, `name`, `created_at`
- Body: `name` (required), `slug`

### Portfolio Images

`GET /portfolio-images?portfolio_id=1` (admin only) · `POST` · `PUT|PATCH` · `DELETE`

Multipart body: `portfolio_id` (required), `image_path` (file, required on create), `alt_text`.

### Technologies

`GET /technologies` · `GET /technologies/{id}` · CRUD admin-only for writes.

- Search: `name`, `slug`; Sort: `id`, `name`
- Body: `name` (required), `slug`

### Pricing Packages

`GET /pricing-packages` · `GET /pricing-packages/{id}` · CRUD admin-only for writes.

- Search: `name`, `slug`; Filters: `is_popular`, `is_active`; Sort: `id`, `name`, `price`, `created_at`
- Nested `features` array is always included.

Body: `name` (required), `slug`, `description`, `price`, `price_label`, `is_popular`, `is_active`.

### Pricing Features

`GET /pricing-features` (admin only) · `GET /pricing-features/{id}` · `POST` · `PUT|PATCH` · `DELETE`

- Filters: `pricing_package_id`, `is_included`; Sort: `id`, `feature`
- Body: `pricing_package_id` (required), `feature` (required), `is_included`

### Testimonials

`GET /testimonials` · `GET /testimonials/{id}` · CRUD admin-only for writes.

- Search: `client_name`, `company_name`, `message`; Filters: `is_published`, `rating`
- Sort: `id`, `client_name`, `rating`, `created_at`

Multipart body: `client_name` (required), `client_position`, `company_name`, `client_photo` (file), `message` (required), `rating` (1–5), `is_published`.

### FAQs

`GET /faqs` · `GET /faqs/{id}` · CRUD admin-only for writes.

- Search: `question`, `answer`; Filters: `is_active`; Sort: `id`, `sort_order`, `created_at`
- Body: `question` (required), `answer` (required), `sort_order`, `is_active`

### Posts

`GET /posts` · `GET /posts/{id}` · CRUD admin-only for writes.

- Public listing only returns `status=published` unless a token is supplied.
- Search: `title`, `slug`, `excerpt`; Filters: `post_category_id`, `status`, `user_id`
- Sort: `id`, `title`, `published_at`, `created_at`

Multipart body:

```json
{
  "post_category_id": 1,
  "title": "Memulai Website Bisnis",
  "slug": "memulai-website-bisnis",
  "excerpt": "Ringkasan",
  "content": "<p>Isi</p>",
  "tags": [1, 2],
  "status": "published",
  "published_at": "2026-01-20T09:00:00Z"
}
```

`user_id` is taken from the authenticated token. When `status=published` and no
`published_at` is given, the current timestamp is used.

### Post Categories

`GET /post-categories` · `GET /post-categories/{id}` · CRUD admin-only for writes.

- Search: `name`, `slug`; Sort: `id`, `name`
- Body: `name` (required), `slug`

### Tags

`GET /tags` · `GET /tags/{id}` · CRUD admin-only for writes.

- Search: `name`, `slug`; Sort: `id`, `name`
- Body: `name` (required), `slug`

### Pages

`GET /pages` · `GET /pages/{id}` · CRUD admin-only for writes.

- Public listing only returns `is_published=1` unless a token is supplied.
- Search: `title`, `slug`, `meta_title`; Filters: `is_published`; Sort: `id`, `title`, `created_at`
- Body: `title` (required), `slug`, `content`, `meta_title`, `meta_description`, `is_published`

### Inquiries

`POST /inquiries` is **public** (contact form). Everything else is admin-only.

| Method | Endpoint | Auth |
| --- | --- | --- |
| POST | `/inquiries` | – |
| GET | `/inquiries` | Bearer |
| GET | `/inquiries/{id}` | Bearer |
| PUT/PATCH | `/inquiries/{id}` | Bearer |
| DELETE | `/inquiries/{id}` | Bearer |

Public submission body:

```json
{
  "name": "Calon Klien",
  "email": "klien@example.com",
  "phone": "08123456789",
  "company": "PT Contoh",
  "subject": "Konsultasi Website",
  "message": "Saya ingin membuat website.",
  "budget": "5-10 juta",
  "services": [1, 2]
}
```

`201 Created`. The `status` is always forced to `new` on public submission.

Admin filters: `status` (`new`, `in_progress`, `contacted`, `closed`); Sort: `id`, `name`, `created_at`.

### Settings

All endpoints admin-only.

`GET /settings` · `GET /settings/{id}` · `POST` · `PUT|PATCH` · `DELETE`

- Search: `key`, `value`, `group`; Filters: `group`, `type`; Sort: `id`, `key`, `group`
- Body: `key` (required, unique), `value`, `group`, `type`

## About / Company profile

Public read endpoints expose the company profile content; writes require an
admin Bearer token. Every resource supports `?search=`, `?sort=`, `?order=`,
`?per_page=` and the filters listed below.

### Profile (singleton)

| Method | Endpoint | Auth | Description |
| --- | --- | --- | --- |
| GET | `/about/profile` | – | Company identity, vision, mission, contacts |
| PUT/PATCH | `/about/profile` | Bearer | Create or update the single profile record |

Body (multipart for `logo` / `hero_image`):

```json
{
  "company_name": "RAMStudio",
  "tagline": "Creative Digital Studio",
  "description": "…",
  "vision": "…",
  "mission": "…",
  "address": "Jl. Contoh No. 1, Jakarta",
  "email": "hello@ramstudio.test",
  "phone": "+62 812 0000 0000",
  "founded_year": 2018
}
```

Returns `404` with `{"success":false}` when the profile does not exist yet.

### Owners — `/about/owners`

`GET` (public) · `GET /{id}` (public) · `POST` · `PUT|PATCH /{id}` · `DELETE /{id}` (admin)

- Search: `name`, `position`, `email`; Filters: `is_active`; Sort: `id`, `name`, `sort_order`, `created_at`
- Multipart body: `name` (required), `position`, `email`, `photo` (file), `bio`, `linkedin_url`, `instagram_url`, `sort_order`, `is_active`

### Teams — `/about/teams`

Identical contract to owners, listing team members.

### Values — `/about/values`

- Search: `title`, `description`; Filters: `type` (`vision`, `mission`, `value`), `is_active`
- Sort: `id`, `title`, `sort_order`, `created_at`
- Body: `title` (required), `type` (required), `description`, `icon`, `sort_order`, `is_active`

### Journeys — `/about/journeys`

- Search: `title`, `description`; Filters: `year`, `is_active`; Sort: `id`, `year`, `sort_order`, `created_at`
- Body: `year` (required, 1900–2100), `title` (required), `description`, `sort_order`, `is_active`

### Statistics — `/about/statistics`

- Search: `label`, `value`; Filters: `is_active`; Sort: `id`, `label`, `sort_order`, `created_at`
- Body: `label` (required), `value` (required), `suffix`, `icon`, `sort_order`, `is_active`

### Social links — `/about/social-links`

- Search: `platform`, `label`, `url`; Filters: `is_active`; Sort: `id`, `platform`, `sort_order`, `created_at`
- Body: `platform` (required), `url` (required, valid URL), `label`, `icon`, `sort_order`, `is_active`

> **Media URLs.** All image fields (`featured_image`, `photo`, `logo`,
> `hero_image`, `thumbnail`, `client_photo`) are returned as host-relative
> paths such as `/storage/uploads/posts/x.png`, so they work regardless of
> `APP_URL`, port or host.

## Status codes

| Code | Meaning |
| --- | --- |
| 200 | OK |
| 201 | Created |
| 401 | Missing/invalid token |
| 403 | Authenticated but not an admin |
| 404 | Resource not found (`{"success":false,...}`) |
| 422 | Validation error (field-level `errors`) |
| 500 | Server error (`{"success":false,...}`) |
