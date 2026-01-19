# SPUP Activity System - Setup Guide

## 🎉 Installation Complete!

Your SPUP (St. Paul University Philippines) Activity System is now successfully installed and running on your laptop.

## 📋 System Overview

This is a Laravel-based web application for managing user activities with an approval workflow system. It includes:

- **Activity Submission**: Users can submit activity proposals
- **Approval Workflow**: Adviser → OSA (Office of Student Affairs) approval process
- **Role-based Access**: Admin, User, Adviser, and OSA roles
- **Document Management**: File uploads for budgets, permits, and supporting documents
- **Notifications**: Real-time notifications for status updates
- **Activity Logging**: Complete audit trail of all actions

## 🚀 Quick Start

### Starting the Application

1. **Start XAMPP Services** (if not already running):
   - Open XAMPP Control Panel
   - Start **Apache** and **MySQL** services

2. **Start Laravel Development Server**:
   ```bash
   cd "C:\Users\NaCho\OneDrive\Desktop\spup-activity-system"
   php artisan serve
   ```

3. **Access the Application**:
   - Open your browser and go to: http://127.0.0.1:8000

## 👥 Test User Accounts

All accounts use the password: **`password`**

### 🔧 Administrator
- **Email**: admin@spup.edu.ph
- **Password**: password
- **Role**: System Administrator
- **Access**: Full system access, user management, system settings

### 👨‍🏫 Advisers
1. **Dr. Ana Reyes**
   - **Email**: adviser1@spup.edu.ph
   - **Department**: Computer Science Department

2. **Prof. Carlos Mendoza**
   - **Email**: adviser2@spup.edu.ph
   - **Department**: Business Administration Department

3. **Dr. Lisa Garcia**
   - **Email**: adviser3@spup.edu.ph
   - **Department**: Engineering Department

### 🏢 OSA (Office of Student Affairs)
1. **Maria Santos**
   - **Email**: osa1@spup.edu.ph
   - **Department**: Office of Student Affairs

2. **John Dela Cruz**
   - **Email**: osa2@spup.edu.ph
   - **Department**: Office of Student Affairs

### 🎓 Users
1. **Juan Pedro**
   - **Email**: student1@spup.edu.ph
   - **Student ID**: 2021-00001
   - **Course**: Bachelor of Science in Computer Science
   - **Year**: 3rd Year

2. **Maria Clara**
   - **Email**: student2@spup.edu.ph
   - **Student ID**: 2021-00002
   - **Course**: Bachelor of Science in Business Administration
   - **Year**: 2nd Year

3. **Jose Rizal**
   - **Email**: student3@spup.edu.ph
   - **Student ID**: 2021-00003
   - **Course**: Bachelor of Science in Civil Engineering
   - **Year**: 4th Year

4. **Anna Karenina**
   - **Email**: student4@spup.edu.ph
   - **Student ID**: 2021-00004
   - **Course**: Bachelor of Science in Information Technology
   - **Year**: 1st Year

5. **Pedro Penduko**
   - **Email**: student5@spup.edu.ph
   - **Student ID**: 2021-00005
   - **Course**: Bachelor of Science in Accountancy
   - **Year**: 3rd Year

## 🔄 Activity Workflow

1. **User Submission**: Users submit activity proposals
2. **Adviser Review**: Assigned adviser reviews and recommends/rejects
3. **OSA Final Approval**: OSA staff provides final approval/rejection
4. **Notifications**: All parties receive notifications at each step

## 🛠 Technical Details

### System Requirements (✅ Installed)
- PHP 8.2.12 (via XAMPP)
- Composer 2.8.10
- Node.js 24.4.0
- MySQL/MariaDB (via XAMPP)

### Database
- **Name**: spup_activity_system
- **Host**: 127.0.0.1
- **Port**: 3306
- **Username**: root
- **Password**: (empty)

### File Structure
```
spup-activity-system/
├── app/                 # Laravel application code
├── database/           # Migrations and seeders
├── public/             # Web accessible files
├── resources/          # Views, CSS, JS
├── storage/            # File uploads and logs
└── vendor/             # PHP dependencies
```

## 🔧 Development Commands

### Database Operations
```bash
# Run migrations
php artisan migrate

# Seed database with test data
php artisan db:seed

# Reset database (caution: deletes all data)
php artisan migrate:fresh --seed
```

### Frontend Assets
```bash
# Install Node.js dependencies
npm install

# Build for production
npm run build

# Development mode with hot reload
npm run dev
```

## 📁 File Uploads

The system supports file uploads for:
- Budget documents
- Permit files
- Supporting documents

Files are stored in `storage/app/public/` and accessible via the `/storage/` URL.

## 🔒 Security Notes

- Change default passwords in production
- Configure proper file upload restrictions
- Set up SSL/HTTPS for production deployment
- Review and configure CORS settings if needed

## 🆘 Troubleshooting

### Common Issues

1. **Database Connection Error**:
   - Ensure XAMPP MySQL service is running
   - Check database credentials in `.env` file

2. **Permission Errors**:
   - Ensure `storage/` and `bootstrap/cache/` directories are writable

3. **Asset Loading Issues**:
   - Run `npm run build` to rebuild frontend assets
   - Clear browser cache

4. **Server Not Starting**:
   - Check if port 8000 is available
   - Try different port: `php artisan serve --port=8080`

## 📞 Support

For technical support or questions about the SPUP Activity System, please refer to the Laravel documentation or contact your system administrator.

---

**🎉 Congratulations! Your SPUP Activity System is ready to use!**
