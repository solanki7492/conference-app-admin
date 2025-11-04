# Firebase Push Notification Admin Panel

A comprehensive Laravel-based admin panel for managing Firebase Cloud Messaging (FCM) push notifications. This application allows administrators to send push notifications to mobile app users and track notification history with detailed analytics.

## 🚀 Features

### ✅ Admin Authentication
- Secure login system using Laravel Breeze
- Protected admin routes with middleware
- User session management

### ✅ Firebase Cloud Messaging Integration
- Complete FCM setup using Firebase Admin SDK for PHP
- Support for iOS and Android devices
- Batch notification sending (up to 500 devices per batch)
- Automatic handling of invalid/expired tokens

### ✅ Device Token Management
- RESTful API for mobile apps to register device tokens
- Support for user association and platform detection
- Automatic token cleanup for invalid registrations
- Device activity tracking

### ✅ Notification Management
- **Send Notifications**: Rich form interface for creating notifications
- **Target Options**: Send to all users or specific device selections
- **Validation**: Input validation for title, message, and targets
- **Real-time Results**: Success/failure tracking with detailed statistics

### ✅ Notification History & Analytics
- Complete notification history with pagination
- Detailed delivery statistics (sent/success/failure counts)
- Success rate calculations and progress bars
- Individual notification detail views
- Sent date/time tracking with admin attribution

### ✅ Professional Dashboard
- Clean, responsive design using Tailwind CSS
- Statistics overview (total devices, active devices, notifications sent)
- Recent notifications preview
- Quick action buttons
- Mobile-friendly interface

### ✅ API Endpoints
- `POST /api/register-device` - Register/update device tokens
- `GET /api/device-tokens` - Retrieve registered tokens (debugging)

## 📋 Database Schema

### Users Table
- Standard Laravel users table for admin authentication
- Fields: id, name, email, password, timestamps

### Device Tokens Table
- `id` - Primary key
- `user_id` - Optional user association
- `token` - FCM device token (unique, up to 500 chars)
- `platform` - Device platform (ios/android)
- `last_used_at` - Last activity timestamp
- `created_at`, `updated_at` - Laravel timestamps

### Notifications Table
- `id` - Primary key
- `title` - Notification title
- `message` - Notification message content
- `sent_to` - Target type ('all' or 'specific')
- `target_tokens` - JSON array of specific tokens (if applicable)
- `sent_count` - Total notifications attempted
- `success_count` - Successful deliveries
- `failure_count` - Failed deliveries
- `sent_by` - Admin user who sent the notification
- `created_at`, `updated_at` - Laravel timestamps

## 🛠️ Installation & Setup

### Prerequisites
- PHP 8.2 or higher
- Composer
- Node.js & NPM
- Firebase project with FCM enabled

### Step 1: Clone and Install Dependencies
```bash
git clone <repository-url>
cd push-notification-admin-panel
composer install
npm install && npm run build
```

### Step 2: Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

Update your `.env` file with:
```env
APP_NAME="Firebase Notification Admin Panel"

# Database (SQLite recommended for simplicity)
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

# Firebase Configuration
FIREBASE_CREDENTIALS=storage/app/firebase-credentials.json
FIREBASE_PROJECT_ID=your-firebase-project-id
```

### Step 3: Firebase Setup
1. Create a Firebase project at [Firebase Console](https://console.firebase.google.com/)
2. Go to Project Settings → Service Accounts
3. Generate and download the private key JSON file
4. Place it at `storage/app/firebase-credentials.json`
5. Enable Firebase Cloud Messaging in your project

### Step 4: Database Setup
```bash
# Create SQLite database
touch database/database.sqlite

# Run migrations
php artisan migrate

# Create admin user
php artisan admin:create --email=your@email.com --password=yourpassword
```

### Step 5: Start the Application
```bash
php artisan serve
```

Visit `http://localhost:8000` and login with your admin credentials.

## 📱 Mobile App Integration

### Register Device Tokens
```javascript
// Example API call from your mobile app
fetch('/api/register-device', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
    },
    body: JSON.stringify({
        user_id: 123, // Optional
        device_token: 'your-fcm-token-here',
        platform: 'ios' // or 'android'
    })
})
```

### FCM Token Generation
- **iOS**: Use Firebase iOS SDK to generate tokens
- **Android**: Use Firebase Android SDK to generate tokens
- **Flutter**: Use `firebase_messaging` package

## 🎯 Usage Guide

### Sending Notifications
1. Navigate to "Send Notification" from the dashboard
2. Enter notification title and message
3. Choose target audience:
   - **All Users**: Sends to all registered devices
   - **Specific Devices**: Select individual devices
4. Click "Send Notification"
5. View real-time delivery results

### Viewing History
1. Click "Notification History" to see all sent notifications
2. View detailed statistics for each notification
3. Click "View Details" for comprehensive delivery analytics

### Managing Devices
- Device registration is handled automatically via API
- Invalid tokens are automatically cleaned up
- View active device count on the dashboard

## 🔧 Technical Details

### Architecture
- **Backend**: Laravel 11 with Firebase Admin SDK
- **Frontend**: Blade templates with Tailwind CSS
- **Database**: SQLite (configurable to MySQL/PostgreSQL)
- **Authentication**: Laravel Breeze
- **Notifications**: Firebase Cloud Messaging

### Key Components
- `FirebaseNotificationService`: Handles all FCM operations
- `DeviceToken` Model: Manages device token lifecycle
- `Notification` Model: Tracks notification history and statistics
- Admin Controllers: Handle dashboard and notification management
- API Controllers: Manage device token registration

### Security Features
- Authentication middleware on all admin routes
- Input validation on all forms
- CSRF protection
- Secure token storage and handling

## 🚨 Troubleshooting

### Firebase Issues
- Verify your `firebase-credentials.json` file path and permissions
- Ensure FCM is enabled in your Firebase project
- Check that your project ID matches the `.env` configuration

### Database Issues
- Ensure the SQLite database file exists and is writable
- Run `php artisan migrate:fresh` to reset the database if needed

### Notification Delivery Issues
- Invalid tokens are automatically removed
- Check Firebase project settings for proper configuration
- Verify device tokens are being registered correctly via the API

## 📊 Statistics & Monitoring

The admin panel provides comprehensive analytics:
- Total registered devices
- Active devices (last 7 days)
- Total notifications sent
- Recent notification performance
- Success/failure rates with detailed breakdowns

## 🔄 Future Enhancements

Potential improvements for production use:
- Notification scheduling
- User segmentation and targeting
- Push notification templates
- Advanced analytics and reporting
- Multi-admin user management
- API rate limiting
- Notification preview/testing

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

---

**Built with Laravel 11, Firebase Admin SDK, and Tailwind CSS**

## 🎮 Quick Start

**Login Credentials:**
- Email: `admin@example.com`
- Password: `password`

**Test API:**
```bash
# Register a test device token
curl -X POST http://localhost:8000/api/register-device \
  -H "Content-Type: application/json" \
  -d '{"user_id": 1, "device_token": "test-token-123", "platform": "ios"}'
```

**Server is running at:** http://127.0.0.1:8000
