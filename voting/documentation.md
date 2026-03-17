# 📄 VoteSecure — Online Voting System Documentation

## 📌 Project Overview

**VoteSecure** is a secure online voting system built with **PHP** and **MySQL** on **XAMPP**. It enables voters to register, login, and cast votes for candidates managed by an administrator. The system enforces one-person-one-vote rules and provides real-time election results.

**Tech Stack:** PHP 7+ | MySQL | HTML5 | CSS3 | JavaScript | XAMPP

---

## 🏗️ System Architecture

```
┌─────────────────────────────────────────────────────────┐
│                      BROWSER                             │
│  ┌──────────┐  ┌──────────┐  ┌───────────┐              │
│  │  Voter   │  │  Admin   │  │  Public   │              │
│  │  Pages   │  │  Panel   │  │  Pages    │              │
│  └────┬─────┘  └────┬─────┘  └─────┬─────┘              │
└───────┼──────────────┼──────────────┼────────────────────┘
        │              │              │
┌───────▼──────────────▼──────────────▼────────────────────┐
│                   APACHE SERVER (XAMPP)                    │
│  ┌─────────────────────────────────────────────────────┐ │
│  │                    PHP Backend                       │ │
│  │  ┌─────────┐ ┌──────────┐ ┌───────────────────────┐ │ │
│  │  │ Session │ │  db.php   │ │  Business Logic      │ │ │
│  │  │ Manager │ │ (connect) │ │  (vote, auth, etc.)  │ │ │
│  │  └─────────┘ └────┬─────┘ └───────────────────────┘ │ │
│  └────────────────────┼─────────────────────────────────┘ │
└───────────────────────┼──────────────────────────────────┘
                        │
┌───────────────────────▼──────────────────────────────────┐
│                   MySQL Database                          │
│  ┌──────────┐  ┌─────────────┐  ┌──────────────────┐    │
│  │  users   │  │  candidates │  │ election_status  │    │
│  └──────────┘  └─────────────┘  └──────────────────┘    │
└──────────────────────────────────────────────────────────┘
```

---

## 📁 File Structure

| File | Type | Description |
|------|------|-------------|
| `setup_db.php` | Backend | Creates database and all tables |
| `db.php` | Backend | Database connection + session init |
| `index.php` | Page | Homepage with quick access cards |
| `register.php` | Page | Voter registration (form + backend) |
| `login.php` | Page | Voter login (form + backend) |
| `logout.php` | Backend | Session destroy + redirect |
| `dashboard.php` | Page | Voter dashboard (stats + actions) |
| `evm.php` | Page | EVM voting machine (cast vote) |
| `vote_success.php` | Page | Vote confirmation screen |
| `result.php` | Page | Election results with bar chart |
| `admin_login.php` | Page | Admin authentication |
| `admin_dashboard.php` | Page | Admin control panel |
| `admin_logout.php` | Backend | Admin session destroy |
| `about_us.php` | Page | About page + star rating |
| `style.css` | CSS | Premium design system |

---

## 🗄️ Database Schema (ER Diagram)

```
┌────────────────────────┐     ┌────────────────────────┐
│        users           │     │      candidates        │
├────────────────────────┤     ├────────────────────────┤
│ id (PK, AUTO_INC)      │     │ id (PK, AUTO_INC)      │
│ name (VARCHAR 100)     │     │ name (VARCHAR 100)     │
│ email (VARCHAR 100, UQ)│     │ party (VARCHAR 100)    │
│ password (VARCHAR 255) │     │ votes (INT, default 0) │
│ voted (TINYINT, def 0) │     │ created_at (TIMESTAMP) │
│ created_at (TIMESTAMP) │     └────────────────────────┘
└────────────────────────┘
                               ┌────────────────────────┐
                               │   election_status      │
                               ├────────────────────────┤
                               │ id (PK, AUTO_INC)      │
                               │ status (VARCHAR 20)    │
                               │  → not_started         │
                               │  → ongoing             │
                               │  → ended               │
                               └────────────────────────┘
```

**Relationships:**
- `users.voted` → tracks if user has voted (0 = no, 1 = yes)
- `candidates.votes` → running count of votes received
- `election_status.status` → global election state

---

## 🔄 Workflow Diagrams

### Voter Registration Flow

```
┌─────────┐     ┌──────────────┐     ┌──────────────┐     ┌───────────┐
│  Visit  │────▶│  Fill Form   │────▶│  Validate &  │────▶│  Success  │
│  Register│     │  (name,email │     │  Check Dup   │     │  Redirect │
│  Page   │     │   password)  │     │  Email       │     │  to Login │
└─────────┘     └──────────────┘     └──────┬───────┘     └───────────┘
                                            │ Error
                                     ┌──────▼───────┐
                                     │  Show Error  │
                                     │  Message     │
                                     └──────────────┘
```

### Voter Login & Voting Flow

```
┌─────────┐     ┌──────────────┐     ┌──────────────┐     ┌───────────┐
│  Login  │────▶│  Verify      │────▶│  Dashboard   │────▶│  EVM Page │
│  Page   │     │  Credentials │     │  (see stats) │     │  (vote)   │
└─────────┘     └──────────────┘     └──────────────┘     └─────┬─────┘
                                                                │
                                     ┌──────────────┐     ┌─────▼─────┐
                                     │  View Result │◀────│  Vote     │
                                     │  Page        │     │  Success  │
                                     └──────────────┘     └───────────┘
```

### Admin Workflow

```
┌───────────┐     ┌──────────────┐     ┌──────────────────────────────┐
│  Admin    │────▶│  Admin       │────▶│  Admin Dashboard             │
│  Login    │     │  Auth Check  │     │  ┌─────────────────────────┐ │
│           │     │  (admin/     │     │  │ 1. Add Candidates (≤4)  │ │
└───────────┘     │  admin123)   │     │  │ 2. Start Election       │ │
                  └──────────────┘     │  │ 3. End Election         │ │
                                       │  │ 4. Reset Election       │ │
                                       │  │ 5. View Results         │ │
                                       │  └─────────────────────────┘ │
                                       └──────────────────────────────┘
```

### Election Lifecycle

```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│  NOT STARTED │────▶│   ONGOING    │────▶│    ENDED     │────▶│    RESET     │
│              │     │              │     │              │     │  (back to    │
│ • Add/Delete │     │ • Voters can │     │ • No voting  │     │  NOT STARTED)│
│   Candidates │     │   cast votes │     │ • Results    │     │ • All cleared│
│ • No voting  │     │ • Live count │     │   are final  │     │              │
└──────────────┘     └──────────────┘     └──────────────┘     └──────────────┘
   Admin clicks         Admin clicks         Admin clicks
   "Start Election"     "End Election"       "Reset Election"
```

---

## 🔐 Security Features

| Feature | Implementation |
|---------|---------------|
| Password Hashing | `password_hash()` with `PASSWORD_DEFAULT` (bcrypt) |
| SQL Injection Prevention | `mysqli_real_escape_string()` on all inputs |
| Session Management | PHP sessions for user/admin authentication |
| One-Person-One-Vote | `users.voted` flag checked before each vote |
| Input Validation | Server-side validation on all forms |
| Election State Control | Voting blocked when election is not ongoing |
| Confirmation Dialogs | JavaScript `confirm()` on irreversible actions |
| XSS Prevention | `htmlspecialchars()` on all displayed data |

---

## 🚀 Setup Instructions

### Prerequisites
- XAMPP installed with Apache + MySQL running

### Steps

1. **Start XAMPP** → Start Apache and MySQL
2. **Open browser** → Go to `http://localhost/voting/setup_db.php`
   - This creates the `voting` database and all tables automatically
3. **Go to homepage** → `http://localhost/voting/index.php`

### Admin Credentials
- **Username:** `admin`
- **Password:** `admin123`

### Quick Test Flow
1. Register a new voter at `/register.php`
2. Login at `/login.php`
3. Admin login at `/admin_login.php` → add 4 candidates → start election
4. Go back to voter dashboard → click "Vote Now" → select a candidate
5. Admin ends election → view results at `/result.php`

---

## 🎨 Design System

| Token | Value | Usage |
|-------|-------|-------|
| Primary | `#667eea` | Buttons, links, accents |
| Secondary | `#764ba2` | Gradient endpoints |
| Dark BG | `#0f0c29 → #302b63 → #24243e` | Page backgrounds |
| Glass BG | `rgba(255,255,255,0.08)` | Card/container backgrounds |
| Border | `rgba(255,255,255,0.15)` | Glass borders |
| Success | `#48bb78` | Success states |
| Danger | `#fc5c65` | Error states, delete actions |
| Font | Inter (Google Fonts) | All text |

**Design Features:** Glassmorphism • Gradient text • Micro-animations • Responsive grid • Dark mode

---

## 📊 Feature Summary

| Feature | Status |
|---------|--------|
| Voter Registration | ✅ Complete |
| Voter Login/Logout | ✅ Complete |
| Voter Dashboard | ✅ Complete |
| EVM Voting (dynamic candidates) | ✅ Complete |
| One-person-one-vote enforcement | ✅ Complete |
| Admin Login | ✅ Complete |
| Add Candidates (max 4) | ✅ Complete |
| Delete Candidates | ✅ Complete |
| Start / End / Reset Election | ✅ Complete |
| Live Results with Bar Charts | ✅ Complete |
| Winner Declaration | ✅ Complete |
| About Page with Star Rating | ✅ Complete |
| Responsive Design | ✅ Complete |
| Premium UI (Glassmorphism) | ✅ Complete |

---

*© 2026 VoteSecure — Online Voting System*
