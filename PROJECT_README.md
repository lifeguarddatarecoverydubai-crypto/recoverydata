# Recovery Data Dubai - Laravel CMS Web Application

A full-featured, enterprise-grade Laravel CMS migrated directly from WordPress (`https://www.recoverydata.ae`), retaining all **563 posts**, **24 pages**, **categories**, **tags**, **authors**, and **services** within a lightning-fast administration dashboard.

---

## Project Overview

- **Project Path:** `D:\CMS\RecoveryData`
- **Framework:** Laravel 11.x (PHP 8.3)
- **Database:** MySQL `recoverydata_db` (Isolated database)
- **Database Backup:** `database/recoverydata_db.sql` (Full 9.1MB export with all 563 posts)
- **Live Source:** `https://www.recoverydata.ae`

---

## Migration Statistics

| Entity | WordPress Source | Imported in Laravel | Notes |
| :--- | :--- | :--- | :--- |
| **Blog Posts** | 563 | **563** | Full HTML content, excerpts, publish dates, authors, categories & tags synced |
| **CMS Pages** | 24 | **24** | All specialized service landing pages & SEO meta preserved |
| **Categories** | 2 | **2** | Categories with post relationships |
| **Tags** | 13 | **13** | Tag relationships mapped across all posts |
| **Services** | 9 | **9 Core + 6 Cat** | Hard Drive, SSD, NVMe, RAID, NAS, CCTV, USB, iPhone, Ransomware |
| **Menu Items** | 14 | **14** | Header navigation with 2-column dropdown for services |
| **Users / Authors** | 2 | **4** | Original authors mapped (`shafia begum` & `admin`) |

---

## Direct WordPress Permalink Compatibility

Old links and search engine indexed URLs from `https://www.recoverydata.ae/%slug%/` automatically resolve with **HTTP 200** without broken links:
- `/{slug}` dynamically resolves to matching Pages, Services, or Blog posts
- Standard routes also available:
  - `/post/{slug}`
  - `/service/{slug}`
  - `/page/{slug}`
  - `/category/{slug}`
  - `/tag/{slug}`

---

## Admin Credentials

- **Login URL:** `/login` (redirects to `/admin/dashboard`)
- **Admin Email:** `admin@example.com`
- **Password:** `password`
- **Editor Email:** `shafia-data@recoverydata.ae` / `password`

---

## Re-running or Updating Migration

You can re-sync or update data from the live WordPress REST API anytime using the custom artisan command:
```powershell
php artisan cms:import-wordpress --url=https://www.recoverydata.ae
```
Options:
- `--truncate` : Clears existing tables before importing
- `--download-images` : Downloads featured images to local `public/storage/media` folder

---

## Running Locally

### With One-Click Batch File
Double-click `run-server.bat` in this folder:
```text
run-server.bat
```
Server opens at `http://127.0.0.1:8000`

### With Laragon or PHP Built-in Server
```powershell
$env:PATH = "C:\laragon\bin\php\php-8.3.33-Win32-vs16-x64;C:\laragon\bin\composer;C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin;" + $env:PATH
php artisan serve
```
Visit: `http://localhost:8000`
