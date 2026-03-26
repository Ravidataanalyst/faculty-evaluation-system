# 🎓 Faculty Performance Evaluation System using Data Analytics

## 📌 Overview

The **Faculty Performance Evaluation System** is a full-stack web application designed to streamline and automate the evaluation process of faculty members in educational institutions.

It integrates **data analytics, role-based dashboards, and real-time feedback systems** to provide actionable insights into faculty performance.

This project demonstrates practical implementation of:
- Web development (PHP + MySQL)
- Data analytics dashboards
- Role-based access control
- Real-world workflow automation

---

## 🚀 Key Features

### 🔐 Authentication & Security
- OTP-based login system
- Password hashing for secure authentication
- Role-based access (Admin, Faculty, Department Head, Student)

### 📊 Analytics Dashboard
- Department-wise performance analysis
- Faculty score trends (time-series)
- Radar charts for individual evaluation
- Top-performing faculty ranking

### 🧑‍🏫 Faculty Module
- Submit self-evaluation
- View feedback summaries
- Track performance trends

### 🧑‍🎓 Student Feedback System
- Students can provide feedback for faculty
- Rating + comments system
- Integrated into evaluation scoring

### 🧑‍💼 Department Head Module
- Review faculty evaluations
- Assign scores
- View detailed student feedback

### 🏢 Admin Panel
- System-wide analytics
- Manage users & roles
- Generate reports

### 🔔 Notification System
- Real-time alerts
- Email notifications (PHPMailer)
- Reminder system for pending actions

---

## 🏗️ Tech Stack

| Category        | Technology Used |
|----------------|----------------|
| Frontend       | HTML, CSS, Bootstrap 5 |
| Backend        | PHP |
| Database       | MySQL |
| Charts         | Chart.js |
| Email Service  | PHPMailer |
| Server         | XAMPP |

---

## 📂 Project Structure
FACULTY_EVAL/
│
├── analytics/ # Data analytics modules
├── assets/ # CSS, JS, images
├── evaluation/ # Evaluation logic
├── feedback/ # Student feedback system
├── functions/ # Reusable functions
├── includes/ # Common includes
├── notifications/ # Notification system
├── templates/ # UI templates
├── uploads/ # User uploads
│
├── dashboard_admin.php
├── dashboard_faculty.php
├── dashboard_dept.php
├── dashboard_student.php
│
├── login & auth files
├── config.php
├── db.php
└── index.php


---

## ⚙️ Installation & Setup

### 1️⃣ Clone the Repository

```bash
git clone https://github.com/Ravidataanalyst/faculty-evaluation-system.git

2️⃣ Move to XAMPP

Place the project inside:
C:/xampp/htdocs/

3️⃣ Start Services
Apache ✅
MySQL ✅
4️⃣ Database Setup
Open phpMyAdmin
Create a database:
faculty_eval
Import your .sql file

5️⃣ Configure Database

Edit config.php:
$host = "localhost";
$user = "root";
$password = "";
$database = "faculty_eval";

6️⃣ Run the Project

Open browser:
http://localhost/faculty_eval/

📊 Real-World Impact

This system solves key problems in traditional evaluation systems:

Eliminates manual evaluation errors
Provides data-driven insights
Improves transparency
Enhances decision-making using analytics

🔮 Future Enhancements
Machine Learning-based performance prediction
AI-driven feedback analysis (NLP)
Mobile app integration
Cloud deployment (AWS / Azure)
Advanced role permissions

🛡️ Security Features
Password hashing
OTP authentication
Session management
CSRF protection (planned)

🤝 Contributing

Contributions are welcome!

Fork the repo
Create a new branch
Make changes
Submit a pull request


📜 License

This project is licensed under the MIT License.

You are free to:

Use
Modify
Distribute

With proper attribution.


👨‍💻 Author
Ravi kumar C
B.tech Artificial intelligence and data science 
Engineering Graduate | Data Analytics | ML | Full Stack

🔗 LinkedIn: (https://www.linkedin.com/in/ravi-kumar-26ab08302/)
📧 Email: (divyachannnn1234@gmail.com)