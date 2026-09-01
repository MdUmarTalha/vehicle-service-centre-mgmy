# Vehicle Service Centre Management System

A simple PHP + MySQL-based vehicle service centre management system.

##  Project Structure

```
vehicle_service_centre/
├── config/
│   └── db.php               # Database connection configuration
├── css/
├── js/
├── includes/
│   ├── header.php           # Common header with navigation
│   └── footer.php           # Common footer
├── services/
│   └── add_service.php      # Add new service form
├── customers/
│   └── add_customer.php     # Add new customer form
├── vehicles/
│   └── add_vehicle.php      # Add new vehicle form
├── database_schema.sql      # MySQL database schema
├── index.php                # Home page
└── dashboard.php            # Main dashboard page
```

##  Installation & Setup
### 1️ Import Database

- Open **MySQL Workbench** or **phpMyAdmin**
- Open/import `database_schema.sql` from the project folder
- Execute the SQL script to create the `vehicle_service_centre` database and its tables

### 2️ Configure XAMPP

- Copy the `vehicle_service_centre/` folder to your `htdocs` directory (usually `C:/xampp/htdocs/`)
- Start **Apache** and **MySQL** from XAMPP Control Panel

### 3️ Access in Browser

- Visit: [http://localhost/vehicle_service_centre](http://localhost/vehicle_service_centre)

##  Features

- Add new customers, vehicles, and services
- Simple dashboard navigation
- MySQL database integration via PHP
- Clean, minimal frontend with header/footer templates

---

© 2025 Vehicle Service Centre
