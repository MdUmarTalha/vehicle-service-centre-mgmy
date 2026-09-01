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

Installation & Setup

Follow these steps to set up and run the project locally using XAMPP:

 1. Import Database
1. Open **phpMyAdmin** or **MySQL Workbench**.
2. Create or import `database_schema.sql` located in the project root folder.
3. Execute the SQL script to create the `vehicle_service_centre` database and required tables.

 2. Configure Local Environment
1. Copy the `vehicle_service_centre/` directory to your XAMPP `htdocs` folder:
   ```text
   C:/xampp/htdocs/
   ```
2. Open the **XAMPP Control Panel**.
3. Start the **Apache** and **MySQL** services.

 3. Launch Application
Open your web browser and visit:
```text
http://localhost/vehicle_service_centre
```
  Tech Stack

* **Frontend:** HTML5, CSS3
* **Backend:** PHP
* **Database:** MySQL
* **Environment:** XAMPP (Apache)
---

