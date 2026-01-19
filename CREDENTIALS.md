# SPUP Activity System — Seeded Login Credentials

Generated from `database/seeders/*.php` on 2025-08-15.

Notes:
- All emails below are created/updated by `LoginCredentialsSeeder` unless otherwise noted.
- Passwords shown are the seeded defaults. Change them in production.
- Some additional test users are created by `UserSeeder` with password `password`. Where the same email exists in both seeders, `LoginCredentialsSeeder` takes precedence.

---

## Administrator
- Email: `admin@spup.edu.ph`
- Password: `admin123`
- Role: `admin`

## Student Officers (exactly 5)
- Emails and assigned departments:
  - `student1@spup.edu.ph` — SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION
  - `student2@spup.edu.ph` — SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT
  - `student3@spup.edu.ph` — SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING
  - `student4@spup.edu.ph` — SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES
  - `student5@spup.edu.ph` — SCHOOL OF MEDICINE
- Password: `student123`
- Role: `student`

## Advisers
- Emails and assigned departments:
  - `adviser1@spup.edu.ph` — SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING
  - `adviser2@spup.edu.ph` — SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT
  - `adviser3@spup.edu.ph` — SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION
  - `adviser4@spup.edu.ph` — SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES
  - `adviser5@spup.edu.ph` — SCHOOL OF MEDICINE
- Password: `adviser123`
- Role: `adviser`

Additional adviser from `UserSeeder`:
- Email: `adviser@spup.edu.ph` — SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING
- Password: `password`
- Role: `adviser`

## Deans
- Emails:
  - `dean.aste@spup.edu.ph` — SCHOOL OF ARTS, SCIENCES AND TEACHER EDUCATION
  - `dean.bahm@spup.edu.ph` — SCHOOL OF BUSINESS, ACCOUNTANCY AND HOSPITALITY MANAGEMENT
  - `dean.ite@spup.edu.ph` — SCHOOL OF INFORMATION TECHNOLOGY AND ENGINEERING
  - `dean.nahs@spup.edu.ph` — SCHOOL OF NURSING AND ALLIED HEALTH SCIENCES
  - `dean.medicine@spup.edu.ph` — SCHOOL OF MEDICINE
- Password: `dean123`
- Role: `dean`

Note: There is also a `DeanSeeder` that can create alternative dean emails (e.g., `dean.arts@spup.edu.ph`, `dean.business@spup.edu.ph`, `dean.it@spup.edu.ph`, `dean.nursing@spup.edu.ph`, `dean.medicine@spup.edu.ph`) with the same password `dean123` if executed.

## PSG Council Advisers
- Emails:
  - `psg.adviser1@spup.edu.ph`
  - `psg.adviser2@spup.edu.ph`
- Password: `psg123`
- Role: `psg_adviser`

## Director of Student Affairs
- Emails:
  - `director.sa@spup.edu.ph`
  - `director.sa2@spup.edu.ph`
- Password: `director123`
- Role: `director`

## Vice President for Academics
- Emails:
  - `vp.academics@spup.edu.ph`
  - `vp.academics2@spup.edu.ph`
- Password: `vp123`
- Role: `vp`

---

## Source Files
- `database/seeders/DatabaseSeeder.php`
- `database/seeders/LoginCredentialsSeeder.php`
- `database/seeders/UserSeeder.php`
- `database/seeders/DeanSeeder.php` (optional, if run)

If you seed afresh via `php artisan migrate:fresh --seed`, the above credentials will be created. If both `UserSeeder` and `LoginCredentialsSeeder` touch the same email, `LoginCredentialsSeeder` values take precedence because it runs after and uses `updateOrCreate()`.

---

## Recent Access and UI Changes (2025-08-16)

These changes affect navigation and profile UI, not the seeded accounts themselves:

- Adviser activities page removed
  - Routes to `/adviser/activities` disabled in `routes/web.php`.
  - All UI links to adviser activities removed from `resources/views/layouts/sidebar.blade.php` and `resources/views/adviser/dashboard.blade.php`.

- Adviser dashboard
  - "Quick Actions" section removed from `resources/views/adviser/dashboard.blade.php`.

- Adviser profile updates (`/adviser/profile`)
  - Removed "Faculty Adviser" labels/badges in header.
  - School (department) selection is now read-only: dropdown disabled and value preserved via hidden input.
  - Removed the "School Affiliation" display panel.
  - Header colors now match dean scheme per department.

Note: After view/route changes, views were cleared via `php artisan view:clear` to reflect updates immediately.
