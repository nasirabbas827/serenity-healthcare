# Serenity HealthCare (final)

A lightweight PHP web application that streamlines the management of a healthcare facility.  
It provides an admin dashboard for handling doctors, patients, staff, pharmacy inventory, appointments, billing, and complaints—all backed by a MySQL database.

---

## Overview

Serenity HealthCare offers a clean, role‑based interface for clinic administrators to:

- Register and manage doctors, patients, staff, and pharmacy items.  
- Record and view appointments, medical histories, and test results.  
- Generate and track bills, as well as handle patient complaints.  
- Securely log in/out with session‑based authentication.

The project is organized with a clear separation between core PHP scripts and the `admin/` module, making it easy to extend or integrate with other systems.

---

## Features

| Category | Description |
|----------|-------------|
| **User Management** | Add / edit / delete doctors, patients, staff, and pharmacy items. |
| **Appointment & Test Handling** | Schedule appointments, record test details, and view results. |
| **Billing & Payments** | Create bills, view billing history, and export records. |
| **Complaints** | Patients can submit complaints; admins can view and resolve them. |
| **Medical History** | Store and retrieve patient medical records securely. |
| **Admin Dashboard** | Centralised navigation (`admin_navbar.php`) with quick access to all modules. |
| **Authentication** | Secure admin login (`admin_login.php`) with session management and logout. |
| **Responsive UI** | Simple, clean layout using standard HTML/CSS (no external frameworks required). |

---

## Tech Stack

| Layer | Technology |
|-------|------------|
| **Backend** | PHP 7.4+ |
| **Database** | MySQL (see `Database/healthcare_db.sql`) |
| **Web Server** | Apache / Nginx (compatible with PHP-FPM) |
| **Styling** | Basic HTML5 & CSS3 (no external CSS frameworks) |
| **Version Control** | Git |

---

## Installation

### Prerequisites

- PHP 7.4 or newer with `mysqli` extension enabled.  
- MySQL server (or MariaDB) with user privileges to create a database.  
- A web server (Apache/Nginx) configured to serve PHP files.  

### Steps

1. **Clone the repository**

   ```bash
   git clone https://github.com/your-username/Serenity_HealthCare_final.git
   cd Serenity_HealthCare_final
   ```

2. **Create the database**

   ```bash
   mysql -u root -p < Database/healthcare_db.sql
   ```

   *Adjust the MySQL credentials as needed.*

3. **Configure the connection**

   Edit `admin/config.php` and replace the placeholder values with your own database credentials:

   ```php
   <?php
   define('DB_HOST', 'YOUR_DB_HOST');
   define('DB_USER', 'YOUR_DB_USER');
   define('DB_PASS', 'YOUR_DB_PASSWORD');
   define('DB_NAME', 'YOUR_DB_NAME');
   ?>
   ```

4. **Set file permissions**

   Ensure the `admin/uploads/` directory is writable by the web server so that images can be stored:

   ```bash
   chmod -R 755 admin/uploads
   ```

5. **Start the server**

   - **Apache** – Place the project folder inside `htdocs` (or configure a virtual host).  
   - **Built‑in PHP server** (for quick testing):

     ```bash
     php -S localhost:8000
     ```

6. **Access the application**

   Open your browser and navigate to:

   ```
   http://localhost/Serenity_HealthCare_final/admin/admin_login.php
   ```

   Use the default admin credentials (set in `admin/config