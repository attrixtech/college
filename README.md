# thrumble.college — Complete Higher-Education Management System

A comprehensive, production-ready Indian College & Higher-Education Management System built on **Laravel 13** and **MySQL (XAMPP)**, compliant with **NEP 2020**, **CBCS**, **AISHE**, and **NAAC / NIRF** requirements.

---

## 🚀 Key Modules (All 30 Included)

1. **Dashboard & Analytics**: Total students, faculty & staff metrics, admission conversion, fee collections, library overdues, placement charts, and Chart.js financial trends.
2. **Online Admission Management**: Public admission portal, Aadhaar consent, Class 10/12 & entrance marks, cut-off based automated Merit List and Waiting List generation, registration number generation, payment tracking, and receipts.
3. **Enquiry & Admission CRM**: Lead source tracking, counsellor assignments, scheduled follow-ups, lost enquiry analytics, and bulk communication.
4. **Student Information System (SIS)**: 360° student records, category & reservation details, guardians, document uploads, migration/transfer history, and digital Student ID Cards.
5. **Course & Academic Management (NEP 2020 / CBCS)**: UG, PG, Diploma, and Certificate courses, credit systems, syllabus summaries, and academic calendars.
6. **Class & Timetable Management**: Section allocations, classroom scheduling, substitute teachers, and real-time faculty/room timetable conflict detection.
7. **Attendance Management**: Manual, QR, Biometric, and RFID attendance recording, faculty attendance, and automated <75% attendance shortage alerts.
8. **Examination Management**: Internal, Mid-Sem & End-Sem exams, schedules, invigilator allocation, mark entry, automated Grade & SGPA/CGPA computation, admit cards, and transcripts.
9. **Library Management**: Book catalogue, accession numbering, barcodes, issue/return/renew, and ₹5/day overdue fine calculation.
10. **Fee & Student Accounts**: Course & category-wise fee structures, concessions, UPI/Cards/NetBanking/Cash payments, ledger tracking, and receipts.
11. **College Finance & Accounting**: Chart of Accounts, General Ledger, Cashbook/Bankbook, Vouchers with GST/TDS, department budgets, and fixed assets.
12. **Faculty & Staff HR**: Employee profiles, designations, qualification records, weekly workload tracking, and leave management.
13. **Payroll Management**: Indian salary structures (Basic, HRA, DA, TA, PF, ESI, Professional Tax, TDS), automated monthly payroll processing, and salary payslips.
14. **Hostel Management**: Boys' & Girls' hostels, floor/room/bed allocation, mess charges, visitor registers, complaints, and outing approvals.
15. **Transport Management**: Vehicle registration, bus routes, stops, fuel records, GPS device IDs, and student route allocation.
16. **Procurement & Inventory**: Purchase requisitions, Purchase Orders (PO), vendor records, lab equipment, and stock tracking.
17. **Student Portal**: Student self-service dashboard, weekly timetable, marksheet download, fee receipts, and assignment submission.
18. **Faculty Portal**: Faculty dashboard, teaching schedules, attendance marking, grading, and leave applications.
19. **Parent Portal**: Parent login with multi-child linking, attendance tracking, fee balances, and exam results.
20. **Learning Management System (LMS)**: Study materials, video lectures, online assignments, student submissions, and subject discussion forums.
21. **Placement & Career Management**: Corporate drives, job openings, packages (LPA), eligibility filters, student applications, and offer tracking.
22. **Scholarships & Financial Aid**: Government schemes (NSP, SC/ST/OBC/EWS), institutional awards, approval workflows, and disbursements.
23. **Certificates & Document Management**: Digital Bonafide, Transfer (TC), Migration, Character, and NOC certificates with instant QR verification tokens.
24. **Grievance & Discipline**: Confidential anti-ragging, harassment, academic grievance redressal, and disciplinary records.
25. **Campus Events & Clubs**: Cultural festivals, hackathons, sports meets, seminars, and event budgets.
26. **Alumni Association**: Batch directory, current employment, mentorship programs, and alumni donations.
27. **Accreditation & Compliance**: NAAC criteria documentation, NIRF metrics, AISHE data upload tracking, and IQAC records.
28. **Research & Innovation**: Faculty publications, funded grants, patents, conferences, and incubation center projects.
29. **Communication & Alerts**: College-wide notices, department announcements, WhatsApp, SMS, and emergency alerts.
30. **System Administration & RBAC**: Multi-campus and multi-college configurations, role-based access control (Admin, Faculty, Student, Parent, Accountant, Librarian), audit logging, student CSV exports, and database JSON backups.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 13 (PHP 8.4+)
- **Database**: MySQL / MariaDB (XAMPP `college` database)
- **Frontend**: Blade templates, Tailwind CSS, Chart.js
- **Testing**: PHPUnit / Pest Feature Test Suite (112 assertions, 100% passing)

---

## ⚙️ Installation & Setup

1. **Clone the repository**:
   ```bash
   git clone <repository-url>
   cd college
   ```

2. **Install Composer dependencies**:
   ```bash
   composer install
   ```

3. **Configure Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Set your MySQL credentials in `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=college
   DB_USERNAME=root
   DB_PASSWORD=
   DB_SOCKET=/Applications/XAMPP/xamppfiles/var/mysql/mysql.sock
   ```

4. **Run Migrations & Seeders**:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Start the Application**:
   ```bash
   php artisan serve
   ```
   Access at `http://localhost:8000`.

---

## 🔒 Security & Roles

Authentication is strictly required across the entire platform. Pre-seeded demo user accounts (password: `password`):
- **Super Administrator**: `admin@thrumble.college`
- **Faculty Member**: `faculty@thrumble.college`
- **Student**: `student@thrumble.college`
- **Parent / Guardian**: `parent@thrumble.college`

---

## 🙌 Credits

- **Developed by**: [Attrix Technologies](https://www.attrixtech.com)
- **Marketed by**: [Infusionn Pvt. Ltd.](https://www.infusionn.in)
