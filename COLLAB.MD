# Baittendance - Team Setup & Development Guide

Welcome to the Baittendance project. We are building this using the Laravel PHP framework. **DO NOT** place this project inside your `xampp/htdocs` folder. Laravel has its own local server. We are only using XAMPP for the MySQL database.

## Phase 1: Environment Setup

1. **Start XAMPP:**
   - Open the XAMPP Control Panel.
   - Start the **Apache** and **MySQL** modules.
2. **Create the Database:**
   - Open your browser and navigate to `http://localhost/phpmyadmin`.
   - Click "New" on the left sidebar.
   - Name the database `baittendance_db` and click "Create". Do not manually create any tables.

## Phase 2: Project Configuration

1. **Get the Code:**
   - Download or pull the project files from our shared repository/folder into your standard working directory (e.g., your Documents or Desktop).
2. **Install Dependencies:**
   - Open your terminal inside the project folder.
   - Run `composer install` (Make sure you have Composer installed on your PC).
3. **Setup Environment Variables:**
   - Duplicate the `.env.example` file and rename the copy to exactly `.env`.
   - Open the `.env` file and update the database section to match XAMPP:
     ```ini
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=baittendance_db
     DB_USERNAME=root
     DB_PASSWORD=
     ```
4. **Generate Application Key:**
   - In your terminal, run `php artisan key:generate`.

## Phase 3: Database Migration & Running the App

1. **Build the Tables:**
   - In your terminal, run `php artisan migrate`.
   - This will automatically construct the `users`, `employees`, and `attendances` tables in your XAMPP database.
2. **Start the Laravel Server:**
   - In your terminal, run `php artisan serve`.
   - Open your browser and go to `http://127.0.0.1:8000`.

