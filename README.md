# Student Attendance Management System

## Project Description

The **Student Attendance Management System** is a Laravel-based web application designed to help teachers and administrators manage classes, students, attendance records, QR attendance, and reports in one workspace.

The system allows authorized users to create classes, register students, record attendance manually or through QR scanning, view attendance analytics, import student data, export reports, and test RESTful API endpoints using Postman.

## Developers

* Mica Danielle Soriano
* Kristine Mae Peralta
* Maricris Panuyas
* Mary Rose Purungganan

## Main Features

### Authentication System

* User login
* User logout
* Session handling
* Password hashing
* Guest and authenticated route protection

### Teacher/Admin Module

* Dashboard with attendance statistics
* Create, read, update, and delete classes
* Create, read, update, and delete students
* Assign students to classes
* Record attendance manually
* Use QR attendance scanner
* View student QR/profile pages
* Generate attendance reports
* Import students using CSV
* Export reports as PDF, XLSX, CSV, and JSON

### Student/User Module

* Login and logout
* View student profile through QR code
* View attendance-related records and information

## Technologies Used

* Laravel
* PHP
* Blade Templates
* Laravel Breeze Authentication
* Laravel Sanctum
* Eloquent ORM
* MySQL / SQLite
* HTML
* CSS
* JavaScript
* Vite
* Tailwind CSS
* Git and GitHub
* Railway Deployment

## Laravel Requirements Mapping

| Requirement          | Implementation                                                                                      |
| -------------------- | --------------------------------------------------------------------------------------------------- |
| Authentication       | Laravel Breeze login/logout                                                                         |
| Session Handling     | Laravel web session authentication                                                                  |
| Password Hashing     | Laravel hashed password handling                                                                    |
| Controllers          | `ClassController`, `StudentController`, `AttendanceController`, `ReportController`, API controllers |
| Models               | `User`, `ClassModel`, `Student`, `Attendance`                                                       |
| Migrations           | Database migrations inside `database/migrations`                                                    |
| Resource Controllers | Resource routes for classes, students, and attendance                                               |
| Blade Templates      | Dashboard, classes, students, attendance, reports, auth pages                                       |
| Master Layout        | `resources/views/components/layout.blade.php`                                                       |
| Navigation Component | `resources/views/components/nav-link.blade.php`                                                     |
| Middleware           | `auth`, `guest`, `verified`, and `admin` middleware                                                 |
| Route Groups         | Authenticated route group and admin-only route group                                                |
| Relationships        | User/teacher, classes, students, and attendance relationships                                       |
| RESTful API          | API endpoints for login, attendance, user info, and logout                                          |
| Reports              | Attendance reports with multiple export formats                                                     |
| Import/Export        | CSV import and PDF/XLSX/CSV/JSON export                                                             |
| Deployment           | Railway deployment                                                                                  |
| Version Control      | GitHub repository                                                                                   |

## Database Tables

The system uses the following main tables:

* `users`
* `class_models`
* `students`
* `attendances`
* `personal_access_tokens`

Laravel also includes system tables such as:

* `sessions`
* `cache`
* `jobs`

## Database Relationships

* A user/teacher can have many classes.
* A class belongs to a teacher.
* A class has many students.
* A student belongs to a class.
* A student has many attendance records.
* An attendance record belongs to a student.
* An attendance record belongs to a class.

## CRUD Operations

The system includes full CRUD operations for the following modules:

### Classes

* Create class
* View class list
* Update class details
* Delete class

### Students

* Create student
* View student list
* Update student details
* Delete student

### Attendance

* Create attendance record
* View attendance history
* Update attendance record
* Delete attendance record

## Middleware Protection

The system protects routes using Laravel middleware.

### Authenticated Routes

Only logged-in users can access:

* Dashboard
* Attendance records
* Attendance scanner
* Reports
* Profile management

### Admin Routes

Only admin users can access:

* Class management
* Student management

## RESTful API Endpoints

The system includes REST API routes for attendance management.

### Public API Routes

```http
POST /api/login
GET /api/attendance
```

### Protected API Routes

These routes require a Sanctum Bearer Token.

```http
GET /api/me
POST /api/logout
POST /api/attendance
GET /api/attendance/{attendance}
PUT /api/attendance/{attendance}
PATCH /api/attendance/{attendance}
DELETE /api/attendance/{attendance}
```

## API Testing Using Postman

### 1. Login and Get Token

```http
POST /api/login
```

Body:

```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

Copy the returned token and use it as a Bearer Token in Postman.

### 2. Get Attendance Records

```http
GET /api/attendance
```

### 3. Create Attendance Record

```http
POST /api/attendance
```

Body:

```json
{
  "class_id": 1,
  "student_id": 1,
  "date": "2026-06-11",
  "present": "present"
}
```

### 4. Update Attendance Record

```http
PUT /api/attendance/1
```

Body:

```json
{
  "class_id": 1,
  "student_id": 1,
  "date": "2026-06-11",
  "present": "late"
}
```

### 5. Delete Attendance Record

```http
DELETE /api/attendance/1
```

### 6. Logout API Token

```http
POST /api/logout
```

## Report Generation

The system includes auto-generated attendance reports.

Available report formats:

```text
PDF
XLSX
CSV
JSON
```

Report export links:

```text
/reports/export/pdf
/reports/export/xlsx
/reports/export/csv
/reports/export/json
```

## CSV Import Format

The system supports importing students through CSV.

Go to:

```text
/reports/import
```

Required CSV headers:

```csv
name,email
Mica Danielle Soriano,micasoriano@gmail.com
Kristine Peralta,tineperalta@gmail.com
Maricris Panuyas,crispanuyas@gmail.com
Mary Rose Purungganan,rosepurungganan@gmail.com
```

Recommended CSV format:

```csv
name,email,qr_code,class_id
Mica Danielle Soriano,micasoriano@gmail.com,QR001,1
Kristine Peralta,tineperalta@gmail.com,QR002,1
Maricris Panuyas,crispanuyas@gmail.com,QR003,2
```

## Default Accounts After Seeding

### Admin Account

```text
Email: admin@example.com
Password: password
```

### Student Account

```text
Email: student@example.com
Password: password
```

## Installation and Setup Instructions

### 1. Clone the Repository

```bash
git clone https://github.com/mdrsoriano-psu-c/attendance_management_system.git
cd attendance_management_system
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Create Environment File

```bash
cp .env.example .env
```

For Windows PowerShell:

```powershell
copy .env.example .env
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Configure Database

Update the `.env` file depending on the database being used.

Example for MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=attendance_system
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Run Migrations and Seeders

```bash
php artisan migrate:fresh --seed
```

### 8. Build Frontend Assets

```bash
npm run build
```

### 9. Start the Development Server

```bash
php artisan serve
```

Open the system in the browser:

```text
http://127.0.0.1:8000
```

## Deployment

The system is deployed online using Railway.

Hosting link:

```text
https://soothing-adaptation-production.up.railway.app
```

## Railway Environment Variables

Example Railway variables:

```env
APP_NAME="Attendance System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://soothing-adaptation-production.up.railway.app

LOG_CHANNEL=stderr
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQLHOST}}
DB_PORT=${{MySQL.MYSQLPORT}}
DB_DATABASE=${{MySQL.MYSQLDATABASE}}
DB_USERNAME=${{MySQL.MYSQLUSER}}
DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local
PORT=8080
```

## GitHub Repository

Repository link:

```text
https://github.com/mdrsoriano-psu-c/attendance_management_system
```

## GitHub and Collaboration

The project is pushed to GitHub and should show regular commits from the developers. All group members should be added as collaborators or contributors in the repository.

Recommended Git commands:

```bash
git add .
git commit -m "Update attendance management system"
git push origin main
```

## Important Files and Folders

```text
app/Http/Controllers
app/Models
database/migrations
database/seeders
resources/views
resources/views/components
routes/web.php
routes/api.php
Dockerfile
README.md
```

## Security Notes

Do not commit the following files and folders to GitHub:

```text
.env
vendor/
node_modules/
database/database.sqlite
```

The `.env` file contains sensitive configuration such as database credentials and application keys.

## Summary

The Student Attendance Management System demonstrates the required Laravel final project concepts, including authentication, session handling, password hashing, CRUD operations, database migrations, Eloquent relationships, Blade templates, layout components, middleware protection, route groups, RESTful API endpoints, report generation, import/export features, GitHub version control, and Railway deployment.
