# 📗 BookVault — Library Management System
**ITM SLS Baroda University — Capstone Project (Alternate Version)**

> Emerald & Parchment theme · Top-navbar layout · Crimson Pro + Nunito · Grid/Table book view

---

## 🛠 Tech Stack

| Layer    | Technology                       |
|----------|----------------------------------|
| Backend  | PHP 8.1+, Laravel 10             |
| Frontend | Bootstrap 5.3, jQuery 3.7        |
| Database | MySQL 8.0+                       |
| ORM      | Eloquent                         |
| Fonts    | Crimson Pro (serif) + Nunito     |

---

## 📁 Project Structure

```
bookvault/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── BookController.php
│   │   ├── IssueController.php
│   │   └── MemberController.php       ← named "members" not "users"
│   └── Models/
│       ├── User.php
│       ├── Book.php
│       ├── Category.php
│       └── Issue.php
├── database/
│   ├── migrations/ (4 files)
│   └── seeders/DatabaseSeeder.php
├── resources/views/
│   ├── layouts/app.blade.php          ← Top navbar layout
│   ├── auth/login.blade.php
│   ├── dashboard/index.blade.php
│   ├── books/    (index, create, edit, show)
│   ├── issues/   (index, create, show, overdue)
│   └── members/  (index, create, edit, show)
└── routes/web.php
```

---

## ⚙️ Setup Instructions

### 1. Create a new Laravel project
```bash
composer create-project laravel/laravel bookvault
cd bookvault
```

### 2. Copy all project files
Replace / merge the files from this project into the Laravel app.

### 3. Register the MemberController route binding
In `app/Providers/RouteServiceProvider.php`, the `{member}` route parameter
automatically resolves to the `User` model. Add this inside the `boot()` method:

```php
use App\Models\User;

Route::model('member', User::class);
```

Or alternatively, in `routes/web.php` at the top:
```php
Route::bind('member', fn($value) => \App\Models\User::findOrFail($value));
```

### 4. Configure `.env`
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bookvault
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Run migrations & seed
```bash
php artisan migrate:fresh --seed
```

### 6. Start server
```bash
php artisan serve
```
Visit: `http://127.0.0.1:8000`

---

## 🔑 Login Credentials

| Role      | Email                      | Password |
|-----------|----------------------------|----------|
| Admin     | admin@bookvault.com        | password |
| Librarian | librarian@bookvault.com    | password |
| Member    | member@bookvault.com       | password |

---

## ✨ Features & Differences from Project 1

| Feature              | Project 1 (LibraryMS)      | Project 2 (BookVault)           |
|----------------------|----------------------------|---------------------------------|
| Layout               | Fixed left sidebar          | Sticky top navbar               |
| Theme                | Dark navy + neon accents    | Parchment + emerald green       |
| Typography           | DM Sans + Playfair Display  | Nunito + Crimson Pro (serif)    |
| Stat cards           | Full gradient fills         | Left-border accent tiles        |
| Table style          | Hover only                  | Zebra-striped warm cream        |
| Books view           | Table only                  | Table + Grid card toggle        |
| Action buttons       | Bootstrap btn-group         | Icon-only square buttons        |
| Delete modals        | Standard Bootstrap modal    | Centered hero icon cards        |
| Members route        | `/users`                    | `/members`                      |
| Login page           | Navy split panel            | Green hero with grid pattern    |
| Sample data          | CS/Math/Physics books       | Sci-Fi, Philosophy, Data Science|
| Fine message         | "Fine collected: ₹X"        | "Fine of ₹X has been recorded"  |
| Overdue detection    | Dashboard + redirect        | Dashboard + dedicated page      |

---

## 🗃️ Database Schema

```
users      — id, name, email, password, role, phone, address, member_id, is_active
categories — id, name, description
books      — id, title, author, isbn, category_id, total_copies, available_copies,
             publisher, published_year (smallInt), description
issues     — id, book_id, user_id, issue_date, due_date, return_date,
             status (issued|returned|overdue), fine_amount, notes
```

### ⚠️ Note on `published_year`
Uses `smallInteger` (not MySQL `YEAR`) to support historical years like 1813, 180 AD, etc.

---

## 🌱 Seed Data Summary
- **8 genres**, **15 books** spanning Sci-Fi to Economics
- **8 users**: 1 Admin, 1 Librarian, 6 Members
- **6 issue records**: 2 active, 2 overdue, 2 returned (1 with fine)

---

## 💰 Fine Policy
- **₹2 per day** after due date (same rate, different messaging)
- Auto-calculated on return, stored in `issues.fine_amount`
- Estimated live for overdue books on dashboard and overdue page

---

*ITM SLS Baroda University — Capstone Project Assessment*
