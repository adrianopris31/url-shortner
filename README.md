# 🔗 URL Shortener

A clean, modern URL shortener built with **Laravel**, **Vue.js**, and **Inertia.js**. Paste a long link, get a short one — no fuss.

## [Try it here](https://url-shortner-production-e31c.up.railway.app/)
---

## ✨ Features

- Shorten any URL instantly
- Modern, responsive UI
- Copy to clipboard with one click
- Track and manage your shortened links
- Fast redirects via clean Laravel routing

---

## 🛠 Tech Stack

| Layer     | Technology                          |
|-----------|--------------------------------------|
| Backend   | [Laravel](https://laravel.com/)      |
| Frontend  | [Vue.js 3](https://vuejs.org/)       |
| Bridge    | [Inertia.js](https://inertiajs.com/) |
| Styling   | Tailwind CSS                         |
| Database  | MySQL / SQLite                       |

Inertia.js acts as the glue between Laravel and Vue, enabling a seamless SPA experience without a separate API.

---

## 🚀 Getting Started

### Prerequisites

- PHP 8.1+
- Composer
- Node.js 18+ & npm
- A database (MySQL or SQLite)

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/adrianopris31/url-shortner.git
cd url-shortner

# 2. Install PHP dependencies
composer install

# 3. Install JS dependencies
npm install

# 4. Set up environment
cp .env.example .env
php artisan key:generate

# 5. Configure your database in .env, then run migrations
php artisan migrate

# 6. Build assets
npm run dev

# 7. Start the development server
php artisan serve
```

Open [http://localhost:8000](http://localhost:8000) in your browser.

---

## 🗂 Project Structure

```
app/
├── Http/Controllers/     # Laravel controllers
├── Models/               # Eloquent models (e.g. ShortUrl)
resources/
├── js/
│   ├── Pages/            # Vue.js page components (Inertia)
│   └── Components/       # Reusable Vue components
├── views/
│   └── app.blade.php     # Root Blade template (Inertia entry point)
routes/
└── web.php               # App routes including redirect handler
```

---

## ⚙️ Configuration

Key `.env` values to set before running:

```env
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=url_shortener
DB_USERNAME=root
DB_PASSWORD=
```

---

## 📄 License

This project is open-source and available under the [MIT License](LICENSE).
