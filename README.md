# 🕌 omarhesham.school

> A full-stack Islamic education platform for Quran memorization (Hifz), built with Laravel 11, Blade + Alpine.js, and Tailwind CSS.

---

## ✨ Overview

**omarhesham.school** is a structured Quran memorization platform connecting students with qualified teachers. Students log their daily Hifz progress, earn badges, and receive weekly reports — while teachers review and approve sessions. Admins manage content moderation, parental consent, and platform-wide reporting.

The platform is built with child safety at its core: minors require verified parental consent before gaining any access, all teacher-uploaded content is quarantined until reviewed, and no advertising is shown to any user.

---

## 🧱 Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel 11 (PHP 8.3) |
| Frontend | Blade templates + Alpine.js + Tailwind CSS |
| Database | MySQL |
| Payments | Stripe (one-time & recurring donations) |
| Email | Laravel Notifications (queued, SMTP) |
| Queue | Laravel Queue (`emails` channel) |
| Scheduler | Laravel Scheduler (cron) |
| Assets | Vite |

---

## 🚀 Features

### 👨‍🎓 Students
- Register and enroll in free or premium Hifz programs
- Log daily memorization sessions (surah, ayah range, quality score, notes)
- Track a live streak counter and earn XP-based badges
- Receive weekly email progress reports with a daily activity chart
- Download PDF progress reports
- Full GDPR-compliant data export and account deletion

### 👩‍🏫 Teachers
- View assigned students and their full progress history
- Approve or flag student progress logs
- Upload content (PDF, audio, YouTube) to lessons
- Receive weekly student summaries by email

### 🛡️ Admins & Center Admins
- Full user management (ban, unban, role assignment)
- Content moderation queue (approve / reject uploaded files)
- Program and lesson management
- Audit log viewer
- Donation and consent record reports

### 👨‍👧 Child Safety (COPPA-compliant)
- Children's accounts are locked until a parent approves via a tokenized email link
- Consent tokens expire after 7 days with automated reminders
- Minors see no advertising and access no unmoderated content

### 🏅 Badge & XP System
- 48 badges across 7 categories: Streak, Volume, Quality, Consistency, Social, Program, Special
- Tiers: Bronze → Silver → Gold → Platinum
- Special badges for Ramadan streaks, young students, and yearly dedication
- XP leaderboard for top students

---

## 📁 Project Structure

```
app/
├── Console/Commands/      # Scheduled jobs (weekly reports, consent cleanup)
├── Events/                # BadgeEarned
├── Http/
│   ├── Controllers/       # Admin, Teacher, Student, Auth, Public
│   └── Middleware/        # RoleMiddleware, MinorProtection, VerifiedBan, SecureHeaders
├── Listeners/             # SendBadgeEarnedNotification
├── Models/                # User, Program, Lesson, ProgressLog, Badge, Donation, ...
├── Notifications/         # 7 queued email notifications
├── Providers/             # ViewServiceProvider (sidebar + streak injection)
└── Services/              # BadgeService (checkAndAward, leaderboard, XP)

database/
├── migrations/            # 15+ migrations
├── seeders/               # Full demo dataset (28 users, 5 programs, 200+ logs)
└── factories/             # UserFactory, ProgressLogFactory, DonationFactory

resources/views/
├── layouts/               # app.blade.php, dashboard.blade.php, auth.blade.php
├── emails/                # 8 HTML email templates (consent, badges, reports, bans)
├── student/               # Dashboard, progress, report
├── teacher/               # Dashboard, students, upload
├── admin/                 # Dashboard, users, moderation, programs, reports
└── policies/              # Privacy, Terms, Child Safety, Cookies
```

---

## ⚙️ Installation

```bash
# 1. Clone
git clone https://github.com/YOUR_USERNAME/omarhesham-school.git
cd omarhesham-school

# 2. Install PHP dependencies
composer install

# 3. Environment
cp .env.example .env
php artisan key:generate

# 4. Configure .env (DB credentials, Stripe keys, mail settings)
nano .env

# 5. Database
mysql -u root -p -e "CREATE DATABASE omarhesham_school;"
php artisan migrate:fresh --seed

# 6. Frontend
npm install
npm run build

# 7. Run
php artisan serve
```

---

## 🔐 Demo Credentials

| Role | Email | Password |
|---|---|---|
| Admin | omar@omarhesham.school | Admin@12345 |
| Center Admin | layla@omarhesham.school | CenterAdmin@123 |
| Teacher | mahmoud.teacher@omarhesham.school | Teacher@2026 |
| Teacher | fatima.teacher@omarhesham.school | Teacher@2026 |
| Adult Student | ahmed@student.test | Student@2026 |
| Adult Student | sarah@student.test | Student@2026 |
| Child (approved) | yusuf.jr@student.test | Student@2026 |
| Child (pending) | omar.jr@student.test | Student@2026 |
| Child (banned) | bilal.child@student.test | Student@2026 |

---

## 📬 Email Notifications

All emails are queued on the `emails` channel and use a branded HTML template.

| Trigger | Recipient |
|---|---|
| Child registers | Parent (consent request with approve/deny links) |
| Parent approves | Child (account activated) |
| Parent denies | Child (access denied) |
| Teacher approves a log | Student |
| Weekly report | Student + their assigned teacher |
| Badge earned (XP ≥ 100) | Student |
| Content moderated | Teacher (approved or rejected) |
| Account banned | User |

---

## 🗓️ Scheduled Jobs

| Command | Schedule | Purpose |
|---|---|---|
| `reports:weekly` | Every Saturday 08:00 Cairo | Send weekly Hifz reports |
| `consent:send-reminders` | Daily 09:00 Cairo | Nudge parents with pending consents |
| `consent:clean-expired` | Daily 02:00 Cairo | Block accounts with expired consent |
| `prune:audit-logs` | Monthly | Remove audit logs older than 365 days |

---

## 🔒 Security

- `SecureHeaders` middleware on every response (CSP, HSTS, X-Frame-Options)
- `VerifiedBan` middleware blocks banned users on every authenticated request
- `MinorProtection` middleware prevents minors from accessing age-restricted content
- All content uploaded by teachers is quarantined and requires admin approval before students can see it
- Parental consent tokens are single-use, 64-character random strings that expire in 7 days
- GDPR: users can export their data or delete their account from account settings

---

## 📄 License

Private project — all rights reserved © Omar Hesham.
