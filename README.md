# Student Attendance Management System

## Project Description
A Laravel-based Student Attendance Management System for managing classes, students, QR attendance, attendance records, reports, imports, exports, and RESTful API testing through Postman.

## Developers
- Cristopherson Domantay
- Add group member name here
- Add group member name here

## Main Features
### Authentication
- Login
- Logout
- Session handling
- Password hashing through Laravel authentication

### Teacher/Admin Module
- Dashboard with attendance analytics
- Create, read, update, and delete classes
- Create, read, update, and delete students
- Assign students to classes
- Record attendance manually
- Use QR attendance scanner page
- Generate attendance reports
- Import students using CSV
- Export reports as PDF, XLSX, CSV, and JSON

### Student/User Module
- Login/logout
- View attendance dashboard and attendance records

## Laravel Requirements Mapping
| Requirement | Where Used |
|---|---|
| Routing | `routes/web.php`, `routes/api.php` |
| Middleware | `auth`, `guest`, `admin` middleware groups/routes |
| Sessions | Laravel login/logout session handling |
| Authentication | Laravel Breeze auth pages |
| Blade Templates | Dashboard, classes, students, attendance, reports pages |
| Master Layout | `resources/views/components/layout.blade.php` using `<x-layout>` |
| Navigation Component | `resources/views/components/nav-link.blade.php` using `<x-nav-link>` |
| Resource Controllers | `ClassController`, `StudentController`, `AttendanceController` |
| Eloquent ORM | Models: `User`, `ClassModel`, `Student`, `Attendance` |
| Relationships | Teacher has many classes, class has many students, attendance belongs to student/class |
| REST API | `GET`, `POST`, `PUT/PATCH`, `DELETE` attendance endpoints |
| Migrations | Database table schema under `database/migrations` |
| Forms | Create/edit forms for classes, students, attendance, import |
| Reports | PDF, XLSX, CSV, JSON export |
| Import/Export | CSV student import and report export |
| GitHub | Push repository with proper commits and collaborators |
| Deployment | Render-ready Dockerfile included |

## Database Tables
- `users`
- `class_models`
- `students`
- `attendances`
- `personal_access_tokens`
- Laravel system tables: `sessions`, `cache`, `jobs`

## Relationships
- User/Teacher has many classes
- Class belongs to teacher
- Class has many students
- Student belongs to class
- Student has many attendance records
- Attendance belongs to student
- Attendance belongs to class

## Default Accounts After Seeding
### Admin/Teacher
- Email: `admin@example.com`
- Password: `password`

### Student/User
- Email: `student@example.com`
- Password: `password`

## Installation / Setup Instructions
```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run build
php artisan serve
```

Open the system:
```text
http://127.0.0.1:8000
```

## REST API Testing in Postman
Use the seeded admin account to get a token first.

### 1. Login and Get Token
```http
POST /api/login
```

Body → raw JSON:
```json
{
  "email": "admin@example.com",
  "password": "password"
}
```

Copy the returned token. In Postman, go to Authorization → Bearer Token → paste the token.

### 2. Get Attendance Records
```http
GET /api/attendance
```

### 3. Create Attendance Record
```http
POST /api/attendance
```

Body → raw JSON:
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

Body → raw JSON:
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

### 6. Logout Token
```http
POST /api/logout
```

## Report Export Links
After login, open:
```text
/reports
```

Available exports:
```text
/reports/export/pdf
/reports/export/xlsx
/reports/export/csv
/reports/export/json
```

## CSV Import Format
Go to:
```text
/reports/import
```

Required CSV headers:
```csv
name,email
Juan Dela Cruz,juan@example.com
Maria Santos,maria@example.com
```

Optional headers:
```csv
name,email,qr_code,class_id
Pedro Reyes,pedro@example.com,SAMPLE-QR-001,1
```

## Deployment Notes
This project includes:
- `Dockerfile`
- `docker/apache.conf`
- `.dockerignore`

For Render, use Docker deployment and set environment variables such as:
```env
APP_NAME="Attendance System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://soothing-adaptation-production.up.railway.app
DB_CONNECTION=mysql
DB_DATABASE=/var/www/html/database/database.sqlite
```

Do not commit:
- `.env`
- `vendor/`
- `node_modules/`
- `database/database.sqlite`

## Hosting Link
Add deployed link here:
```text
https://your-hosting-link-here
```

## QR Student Profile + Analytics Update

This version includes:
- Student QR card generation
- QR scanner page with camera support
- Scanning a QR opens the student profile
- Student profile shows attendance analytics
- Dashboard analytics with charts and top students

### How to use QR feature
1. Login as admin.
2. Go to **Students**.
3. Click **QR Card** for any student.
4. Print or open the QR card.
5. Go to **Dashboard → Open Scanner**.
6. Scan the QR code.
7. The student profile appears and attendance can be marked present.

### Important note for camera scanner
The camera scanner uses browser camera permission. If camera does not open, allow camera permission in Chrome. Manual scanner input still works by pasting the QR value or using a USB barcode scanner.


## Attendance Page vs API

Use this for the normal browser page:

```text
http://127.0.0.1:8000/attendance
```

If you accidentally open this API URL in the browser, the system redirects you to the styled Attendance History page:

```text
http://127.0.0.1:8000/api/attendance
```

For Postman/API testing, send the request with `Accept: application/json`.
