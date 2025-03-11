# Symfony E-commerce Application

## 📌 Project Overview
This is a simple e-commerce application built using Symfony 6.4. The application includes product listing, product details, category filtering, sorting, pagination, and API endpoints using API Platform.

---

## 🛠️ Setup Instructions

### 1️⃣ Clone the Repository
```sh
git clone https://github.com/mehul1310/ecom_app.git
cd ecom_app
```

### 2️⃣ Install Dependencies
```sh
composer install
```

### 3️⃣ Configure Environment Variables
Copy the `.env` file and configure database connection:
```sh
cp .env .env.dev
```
Update `DATABASE_URL` in `.env.dev`:
```
DATABASE_URL="mysql://user:password@127.0.0.1:3306/ecom_app?serverVersion=8.0"
```

### 4️⃣ Setup the Database
```sh
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate
```

### 5️⃣ Load Fixtures (Test Data)
```sh
symfony console doctrine:fixtures:load
```

### 6️⃣ Start the Symfony Server
```sh
symfony server:start
```

Your app will be running at `http://127.0.0.1:8000`.

---

## 🌟 Features Implemented
- **Product Management** (create and read operations)
- **Category Filtering & Sorting** (Title, Price - ASC)
- **Pagination** (On product listing page & API)
- **Image Uploads** (Stores product images)
- **REST API** (Built using API Platform)
- **Unit Testing** (PHPUnit tests for key functionalities)

---

## 📄 API Endpoints

### 📍 Product API
| Method | Endpoint | Description |
|--------|----------|-------------|
| `GET` | `/api/products` | Get paginated product list |
| `GET` | `/api/products/{id}` | Get a single product |

Example:
```sh
curl -X GET http://127.0.0.1:8000/api/products
curl -X GET http://127.0.0.1:8000/api/products/1
```

---

## 🧪 Setting Up Test Environment

### 1️⃣ Configure Test Database
Copy the `.env` file for testing:
```sh
cp .env.test .env.test.local
```

Update `DATABASE_URL` in `.env.test.local`:
```
DATABASE_URL="mysql://user:password@127.0.0.1:3306/ecom_app?serverVersion=8.0"
```

### 2️⃣ Create Test Database
```sh
symfony console doctrine:database:create --env=test
symfony console doctrine:migrations:migrate --env=test
```

### 3️⃣ Load Fixtures for Testing
```sh
symfony console doctrine:fixtures:load --env=test
```

## ✅ Running Tests
To run unit tests:
```sh
php bin/phpunit
```

---

