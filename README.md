<div align="center">

<img src="public/img/logo-direktorat.png" alt="Direktorat Kemahasiswaan" height="90">

# DITMAWA — Document Generator

**Official document generation system for**  
**Direktorat Kemahasiswaan, Karier, dan Alumni · Telkom University**

<br>

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3-77C1D2?style=flat-square&logo=alpine.js&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8-4479A1?style=flat-square&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-Internal-E03A3E?style=flat-square)

</div>

---

## Overview

**DITMAWA Document Generator** is an internal web application that eliminates the manual effort of formatting official administrative documents for student organizations (UKM & HIMA) at Telkom University.

Users fill out guided multi-step forms and the system instantly generates **print-ready PDFs** that precisely match the official Direktorat Kemahasiswaan templates — complete with proper structure, page numbering, signature blocks, and attachments.

---

## Features

| Feature | Description |
|---|---|
| **Authentication** | Register, login, and logout with organization & position fields |
| **Profile Management** | Edit personal info and update password |
| **Proposal Kegiatan Generator** | 12-step guided form → complete pre-event proposal PDF |
| **LPJ Generator** | Multi-step guided form → complete post-event accountability report PDF |
| **Official PDF Output** | Matches Direktorat Kemahasiswaan templates exactly |
| **File Uploads** | Organization logo and attachments embedded in the PDF |
| **Document History** | View and re-download all previously generated documents |
| **Telkom Brand UI** | Clean, professional interface built on Telkom University's visual identity |

---

## Tech Stack

| Layer | Technology |
|---|---|
| Framework | Laravel 11 |
| Language | PHP 8.2+ |
| Frontend | Blade + Tailwind CSS + Alpine.js |
| PDF Generator | `barryvdh/laravel-dompdf` |
| Database | MySQL 8 / MariaDB |
| Asset Bundler | Vite |
| File Storage | Laravel Storage (public disk) |

---

## Requirements

- PHP **8.2** or higher
- Composer
- Node.js **18+** & npm
- MySQL **8.0+** or MariaDB **10.5+**
- PHP extensions: `ext-gd` `ext-xml` `ext-dom` `ext-mbstring` `ext-zip`

---

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/YOUR_USERNAME/YOUR_REPO.git ditmawa
cd ditmawa
```

### 2. Install dependencies

```bash
composer install --no-dev --optimize-autoloader
npm install && npm run build
```

### 3. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` with your server values — see [Environment Configuration](#environment-configuration) below.

### 4. Set up the database

```sql
CREATE DATABASE direktorat_kemahasiswaan
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

CREATE USER 'ditmawa'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON direktorat_kemahasiswaan.* TO 'ditmawa'@'localhost';
FLUSH PRIVILEGES;
```

### 5. Run migrations

```bash
php artisan migrate --force
```

### 6. Link storage

```bash
php artisan storage:link
```

> This step is **required** — without it, uploaded logos and attachment files will not be accessible from the web.

### 7. Optimize for production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Environment Configuration

Key variables to configure in your `.env` file:

| Variable | Example Value | Notes |
|---|---|---|
| `APP_NAME` | `Direktorat Kemahasiswaan` | Shown in browser tab title |
| `APP_ENV` | `production` | Use `local` for development |
| `APP_KEY` | *(auto-generated)* | Run `php artisan key:generate` |
| `APP_DEBUG` | `false` | **Must be `false` in production** |
| `APP_URL` | `https://your-domain.com` | Full URL with protocol |
| `APP_TIMEZONE` | `Asia/Jakarta` | |
| `DB_CONNECTION` | `mysql` | |
| `DB_HOST` | `127.0.0.1` | |
| `DB_PORT` | `3306` | |
| `DB_DATABASE` | `direktorat_kemahasiswaan` | |
| `DB_USERNAME` | `ditmawa` | Use a dedicated user, not `root` |
| `DB_PASSWORD` | *(set a strong password)* | |
| `FILESYSTEM_DISK` | `public` | **Required** for file uploads to work |
| `SESSION_DRIVER` | `database` | |
| `LOG_LEVEL` | `error` | Use `debug` in development |

---

## Usage

### Generating a Proposal Kegiatan

1. Log in and go to **Generate Laporan → Generate Proposal**
2. Complete the **12-step form**:

   | Step | Section |
   |---|---|
   | 1 | Event identity — name, theme, dates, venue |
   | 2 | Upload organization logo |
   | 3 | Narrative — background, objectives, target audience |
   | 4 | Materials & speakers |
   | 5 | Rundown / schedule |
   | 6 | Risk analysis |
   | 7 | Monitoring & evaluation plan |
   | 8 | Committee structure |
   | 9 | Budget plan — income, expenses, funding sources |
   | 10 | Closing statement & signatories |
   | 11 | Attachments upload |
   | 12 | Review & generate |

3. Click **Generate PDF** — the file downloads immediately.

### Generating an LPJ (Laporan Pertanggungjawaban)

1. Go to **Generate Laporan → Generate LPJ**
2. Complete the multi-step form with post-event data, including actual budget realization, monitoring table, and event description
3. Click **Generate PDF** — the file downloads immediately.

---

## PDF Output Structure

### Proposal Kegiatan
```
Cover → Lembar Evaluasi → Form Kontrol Pengajuan → Rekap Proposal → Daftar Isi
→ A. Latar Belakang → B–O. Content Sections → Lembar Pengesahan → Lampiran
```

### LPJ (Laporan Pertanggungjawaban)
```
Cover → Daftar Isi → A. Latar Belakang → B–Q. Content Sections
→ Lembar Pengesahan → Lampiran (nota, bukti transfer, dokumentasi, poster)
```

> **Note:** Signature fields are intentionally left blank for handwritten (wet) signatures.  
> No letterhead appears on body pages — cover page only.

---

## Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/        # ProposalController, LpjController, DashboardController
│   │   └── Requests/           # Form validation — ProposalStoreRequest, LpjStoreRequest
│   ├── Models/                 # Proposal, Lpj, and all child Eloquent models
│   └── Policies/               # Owner-only access enforcement
├── database/
│   ├── migrations/             # Full database schema
│   ├── seeders/                # DummyDataSeeder for local development
│   └── factories/              # Lpj & Proposal factories for testing
├── public/
│   ├── img/                    # Logo assets (Telkom, Direktorat)
│   └── videos/                 # Hero background video
├── resources/
│   ├── views/
│   │   ├── proposal/           # Proposal form + PDF blade templates
│   │   ├── lpj/                # LPJ form + PDF blade templates
│   │   ├── layouts/            # App layout with sidebar & topbar
│   │   └── components/         # Reusable Blade components
│   ├── css/app.css             # Tailwind + Telkom brand CSS variables
│   └── js/app.js               # Alpine.js + form validator
└── routes/web.php              # All application routes
```

---

## Security

- All dashboard routes protected by `auth` middleware
- Documents are **owner-only** — enforced via Laravel Policies
- File uploads validated: `image/png`, `image/jpeg` only, max **2 MB** per file
- CSRF protection active on all forms
- `.env` is git-ignored — credentials are never committed to the repository

---

## Development

```bash
# Start local server
php artisan serve

# Watch and compile assets
npm run dev

# Run test suite
php artisan test

# Seed dummy data for testing
php artisan db:seed --class=DummyDataSeeder
```

---

<div align="center">

Built for **Direktorat Kemahasiswaan, Karier, dan Alumni**  
**Telkom University** · Bandung, Indonesia

<img src="public/img/logo-telkom.png" alt="Telkom University" height="40">

</div>
