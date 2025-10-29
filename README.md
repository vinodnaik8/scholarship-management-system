# Scholarship Management System (Aadhaar Login)

A user-friendly and secure **Scholarship Management System** built using **PHP, HTML, CSS, JavaScript, and MySQL**.  
The system features **Aadhaar-based login authentication**, **role-based access** (Admin & Student), and **automated email notifications** powered by **PHPMailer**.

---

## 🚀 Features

### 🔐 Authentication
- Aadhaar-based login system for secure and verified access.
- Role-based dashboard:
  - **Admin:** Manage scholarships, approve/reject applications, and monitor users.
  - **Student:** Apply for scholarships, upload documents, and track status.

### 💼 Scholarship Management
- Apply for scholarships online with ease.
- Upload required documents securely.
- Track real-time application status.

### 📬 Email Notifications (PHPMailer)
- Automatic email notifications sent to users for:
  - Successful registration
  - Application submission
  - Status updates (Approved / Rejected)
  - Admin actions

### 💻 Admin Features
- Dashboard overview of all applications.
- Approve or reject scholarship applications.
- Manage student accounts and scholarships.

### 🎨 User-Friendly Interface
- Responsive and modern UI built with HTML, CSS, and JavaScript.
- Clean design for both admin and student dashboards.

---

## 🛠️ Tech Stack

| Component | Technology |
|------------|-------------|
| **Frontend** | HTML, CSS, JavaScript |
| **Backend** | PHP |
| **Database** | MySQL |
| **Email Service** | PHPMailer |
| **Authentication** | Aadhaar-based login |
| **Version Control** | Git & GitHub |

---

## ⚙️ Installation & Setup

### 🔹 Prerequisites
- [XAMPP](https://www.apachefriends.org/index.html) or [WAMP](https://www.wampserver.com/en/)
- PHP ≥ 7.4
- MySQL Database
- Composer (for PHPMailer)

### 🔹 Steps to Run Locally

```bash
# Step 1: Clone the repository
git clone https://github.com/<your-username>/scholarship-management-system.git

# Step 2: Move project folder to your htdocs directory (for XAMPP)
# Example:
C:\xampp\htdocs\scholarship-management-system

# Step 3: Start Apache and MySQL from XAMPP Control Panel

# Step 4: Import the SQL database
# - Open phpMyAdmin
# - Create a new database (e.g., scholarship_db)
# - Import the provided .sql file in /database directory

# Step 5: Configure Database Connection
# Edit config.php (or db.php)
# Example:
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "scholarship_db";

# Step 6: Configure PHPMailer in mail_config.php
# Example:
$mail->Host = 'smtp.gmail.com';
$mail->Username = 'your_email@gmail.com';
$mail->Password = 'your_app_password';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

# Step 7: Access the project
# Open browser and go to:
http://localhost/scholarship-management-system/


## 🔒 Security Notes

- Aadhaar number validation follows UIDAI guidelines (for demo/testing purpose only — not connected to real UIDAI servers).
- Never commit sensitive data (like Aadhaar API keys, PHPMailer passwords, or database credentials) to your GitHub repo.
- Store credentials in a `.env` file and **add it to `.gitignore`** before pushing to GitHub.
- Always sanitize and validate all form inputs to prevent SQL Injection and XSS attacks.
- Use HTTPS in production for secure communication.

---

## 👨‍💻 Author

**Vinod**  
📧 vinodnaik00548@gmail.com  
🌐 [https://github.com/vinodnaik8](https://github.com/vinodnaik8)

