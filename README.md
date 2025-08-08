# Hospital Management System API

A secure, role-based RESTful API built with Laravel for managing patients, doctors, appointments, medicines, and billing in a hospital environment.

---

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Installation](#installation)
- [Configuration](#configuration)
- [Database Setup](#database-setup)
- [Authentication](#authentication)
- [API Endpoints](#api-endpoints)
- [Filtering & Pagination](#filtering--pagination)
- [Role-Based Access Control](#role-based-access-control)
- [Testing](#testing)
- [Contributing](#contributing)
- [License](#license)

---

## Features

- Role-based authentication (Admin, Doctor, Patient) using JWT  
- CRUD operations for Patients, Doctors, Appointments, Medicines, and Bills  
- Advanced filtering on Doctors and Patients by attributes and related user data  
- Appointment booking, approval, and rejection workflows  
- Doctor and Patient profile management  
- Email notifications for appointment status updates  
- Excel export for medicines  
- Pagination support on list endpoints  

---

## Tech Stack

- **Backend:** Laravel 10  
- **Authentication:** Laravel Passport or JWT Auth  
- **Database:** MySQL  
- **Mail:** Laravel Queues and SMTP  
- **Testing:** PHPUnit  

---

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/yourusername/hospital-management-system.git
   cd hospital-management-system
````

2. Install dependencies:

   ```bash
   composer install
   ```

3. Copy `.env` and generate app key:

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

---

## Configuration

Update your `.env` file with your database and mail credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hospital_db
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=admin@hospital.com
MAIL_FROM_NAME="Hospital Admin"
```

---

## Database Setup

Run the migrations and seed initial data:

```bash
php artisan migrate --seed
```

---

## Authentication

* **Login:** `POST /api/login`
* **Logout:** `POST /api/logout`
* **Get Current User:** `GET /api/me`

Use Bearer token in `Authorization` header for protected routes.

---

## API Endpoints

### Authentication

| Method | Endpoint    | Description            |
| ------ | ----------- | ---------------------- |
| POST   | /api/login  | User login             |
| POST   | /api/logout | User logout            |
| GET    | /api/me     | Get authenticated user |

### Patients (Admin only)

| Method | Endpoint             | Description       |
| ------ | -------------------- | ----------------- |
| GET    | /api/patients        | List all patients |
| GET    | /api/patients/filter | Filter patients   |
| POST   | /api/patients        | Create patient    |
| GET    | /api/patients/{id}   | Show patient      |
| PUT    | /api/patients/{id}   | Update patient    |
| DELETE | /api/patients/{id}   | Delete patient    |

### Doctors (Admin only)

| Method | Endpoint            | Description      |
| ------ | ------------------- | ---------------- |
| GET    | /api/doctors        | List all doctors |
| GET    | /api/doctors/filter | Filter doctors   |
| POST   | /api/doctors        | Create doctor    |
| GET    | /api/doctors/{id}   | Show doctor      |
| PUT    | /api/doctors/{id}   | Update doctor    |
| DELETE | /api/doctors/{id}   | Delete doctor    |

### Doctor Profile (Doctor only)

| Method | Endpoint                   | Description                         |
| ------ | -------------------------- | ----------------------------------- |
| GET    | /api/doctor/profile        | Get authenticated doctor profile    |
| PUT    | /api/doctor/profile/update | Update authenticated doctor profile |

### Appointments

| Method | Endpoint                       | Description                 |
| ------ | ------------------------------ | --------------------------- |
| GET    | /api/appointments              | List appointments           |
| POST   | /api/appointments              | Book appointment            |
| PUT    | /api/appointments/{id}/approve | Approve appointment (Admin) |
| PUT    | /api/appointments/{id}/reject  | Reject appointment (Admin)  |

### Medicines

| Method | Endpoint              | Description               |
| ------ | --------------------- | ------------------------- |
| GET    | /api/medicines        | List medicines            |
| GET    | /api/medicines/export | Export medicines to Excel |
| POST   | /api/medicines        | Add medicine              |
| GET    | /api/medicines/{id}   | Show medicine             |
| PUT    | /api/medicines/{id}   | Update medicine           |
| DELETE | /api/medicines/{id}   | Delete medicine           |

### Bills

| Method | Endpoint                 | Description        |
| ------ | ------------------------ | ------------------ |
| POST   | /api/bills               | Create bill        |
| PUT    | /api/bills/{bill}/status | Update bill status |
| GET    | /api/bills               | List bills         |
| GET    | /api/bills/{bill}        | Show bill          |
| DELETE | /api/bills/{bill}        | Delete bill        |

---

## Filtering & Pagination

Filter support on `/api/patients/filter` and `/api/doctors/filter`.
Use query parameters like:

* **Doctors:** `name`, `email`, `specialization`, `phone`
* **Patients:** `name`, `email`, `phone`

Pagination enabled by default with 5 items per page.

**Example:**

```
GET /api/doctors/filter?specialization=cardiology&name=John&page=2
```

---

## Role-Based Access Control

* **Admin:** Full access to all CRUD and management routes
* **Doctor:** Manage own profile, view and manage appointments & prescriptions
* **Patient:** Manage own profile, book appointments, view bills

---

## Testing

Run all unit and feature tests with:

```bash
php artisan test
```

---

## Contributing

Contributions are welcome! Please fork the repository, create a feature branch, and open a pull request.

---

## License

This project is licensed under the MIT License.

```

---

If you’d like me to generate a downloadable `.md` file or convert it to PDF/Word, just say the format you need.
```
