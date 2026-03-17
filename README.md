# 🗳️ VoteSecure — Secure Online Voting System

[![PHP](https://img.shields.io/badge/PHP-8+-777DD6?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![XAMPP](https://img.shields.io/badge/XAMPP-FD425C?style=for-the-badge&logo=xampp&logoColor=white)](https://www.apachefriends.org/)
[![License](https://img.shields.io/badge/License-MIT-brightgreen?style=for-the-badge)](LICENSE)

A **secure, transparent, and modern** online voting platform (EVM-style) built for college projects. Features voter registration, admin-managed elections, one-person-one-vote enforcement, and live results with bar charts.

![Hero Screenshot](https://via.placeholder.com/1200x600/0f0c29/6366f1?text=VoteSecure+Hero) *(Live demo: http://localhost/voting/index.php)*

## ✨ Features

| Feature | Description |
|---------|-------------|
| **Voter Portal** | Register/Login, Dashboard, EVM Voting |
| **Admin Panel** | Add/Delete Candidates (max 4), Election Controls (Start/End/Reset) |
| **Security** | Password hashing (bcrypt), SQL injection prevention, Vote enforcement |
| **Results** | Live bar charts, Winner declaration, Vote percentages |
| **UI/UX** | Glassmorphism, Dark theme, Responsive, Animations |
| **States** | not_started → ongoing → ended → result_declared → reset |

## 🏗️ Architecture

```
Browser → Apache (XAMPP) → PHP → MySQL (3307)
                ↓
         Sessions + db.php
```

**Database Schema**:
```
users: id, name, email(Unique), password_hash, voted(0/1)
candidates: id, name, party, votes, created_at
election_status: id, status('not_started'|'ongoing'|'ended'|'result_declared')
```

## 🚀 Quick Start (2 Minutes)

### Prerequisites
- [ ] XAMPP installed

### 1. Start XAMPP
```
Apache: START ✅
MySQL:  START ✅ (Port 3307)
```

### 2. Setup DB
```
http://localhost/voting/setup_db.php
→ "✅ Database Setup Complete!"
```

### 3. Launch App
```
http://localhost/voting/index.php
```

### Test Flow
```
1. Register → rihan@gmail.com / pass123
2. Admin Login → admin / admin123 → Add 4 candidates → Start Election
3. Voter Dashboard → EVM → Vote
4. Admin → End → Declare Results
5. View Results → Charts + Winner 🏆
```

## 📁 File Structure

```
voting/
├── db.php              # DB Connection
├── setup_db.php        # Auto-setup
├── index.php           # Home
├── register.php        # Voter Signup
├── login.php           # Voter Login
├── dashboard.php       # Voter Stats
├── evm.php            # Vote Casting
├── result.php         # Results + Charts
├── admin_*.php         # Admin Panel
├── style.css          # Premium UI (1000+ lines)
├── README.md          # 📄 This file
└── project_report.html # 🎓 College Report
```

## 🔐 Admin Credentials
```
Username: admin
Password: admin123
```

## 🛡️ Security Implemented
- `password_hash(PASSWORD_DEFAULT)` ✅
- `mysqli_real_escape_string()` ✅
- Session validation ✅
- Vote flags ✅
- Input validation ✅
- Confirmation dialogs ✅

## 📊 Sample Results
```
shoyeb (miim): 2 votes (100%) 🏆
shoyeb (bjp): 0 votes (0%)
rehan (aam):  0 votes (0%)
irshaan (bjp): 0 votes (0%)
Total: 2 votes
```

## 🎨 Design Tokens
- **Colors**: Primary `#6366f1`, Accent `#ec4899`, Success `#10b981`
- **Style**: Glassmorphism, Gradients, Shimmer, Mesh BG
- **Responsive**: Mobile-first, Flex/Grid

## 📱 Screenshots

**Home** | **EVM** | **Results**
---|---|---
![Home](https://via.placeholder.com/400x250/0f0c29/6366f1?text=Home) | ![EVM](https://via.placeholder.com/400x250/0f0c29/6366f1?text=EVM) | ![Results](https://via.placeholder.com/400x250/0f0c29/10b981?text=Results)

**Admin Dashboard**
![Admin](https://via.placeholder.com/800x400/0f0c29/6366f1?text=Admin+Dashboard)

## 🧪 Testing

| Test Case | Status |
|-----------|--------|
| Voter Registration | ✅ |
| Duplicate Email Block | ✅ |
| Vote Twice Block | ✅ |
| Max 4 Candidates | ✅ |
| Election States | ✅ |
| SQL Injection | Blocked ✅ |
| XSS | Sanitized ✅ |

## 🔮 Future Enhancements
- [ ] Multi-election support
- [ ] Voter ID upload/photo
- [ ] OTP verification
- [ ] HTTPS/Production deploy
- [ ] Candidate photos
- [ ] Audit logs
- [ ] Mobile App (PWA)

## 📄 College Project Report
View the **complete documentation** at [project_report.html](project_report.html)

## 🤝 Contributing
1. Fork repo
2. Create feature branch
3. Submit PR

## 📄 License
MIT License — Free for educational/commercial use.

---

**⭐ Made with ❤️ for college projects**  
**Live Demo**: `http://localhost/voting/`  
**Author**: College Student (2026)
