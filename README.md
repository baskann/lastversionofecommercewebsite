# How to Run This Project

1. **Download the project**
```bash
git clone https://github.com/baskann/ecommerce-system.git
cd ecommerce-system
```

2. **Install dependencies**
```bash
composer install
```

3. **Copy environment file**
```bash
cp .env.example .env
```

4. **Generate key**
```bash
php artisan key:generate
```

5. **Run migrations**
```bash
php artisan migrate
```

6. **Start server**
```bash
php artisan serve
```

Visit: http://localhost:8000
