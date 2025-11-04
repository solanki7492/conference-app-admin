# Firebase Push Notification Admin Panel - Setup Instructions

## Prerequisites

1. **Firebase Project**: Create a Firebase project at https://console.firebase.google.com/
2. **Firebase Admin SDK**: Download your Firebase Admin SDK private key JSON file
3. **PHP & Composer**: Ensure PHP 8.2+ and Composer are installed

## Setup Steps

### 1. Environment Configuration

Copy `.env.example` to `.env` and update the following variables:

```env
# Database Configuration
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database.sqlite

# Firebase Configuration
FIREBASE_CREDENTIALS=/path/to/firebase-credentials.json
FIREBASE_PROJECT_ID=your-project-id
```

### 2. Firebase Setup

1. Go to Firebase Console → Project Settings → Service Accounts
2. Click "Generate new private key" to download the JSON file
3. Place the JSON file in your project (e.g., `storage/app/firebase-credentials.json`)
4. Update `FIREBASE_CREDENTIALS` in your `.env` file with the absolute path

### 3. Database Setup

```bash
# Create database file (if using SQLite)
touch database/database.sqlite

# Run migrations
php artisan migrate
```

### 4. Create Admin User

```bash
php artisan tinker
```

Then in the tinker shell:
```php
User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password')
]);
```

### 5. Install Dependencies & Build Assets

```bash
composer install
npm install && npm run build
```

### 6. Run the Application

```bash
php artisan serve
```

Visit `http://localhost:8000` and login with your admin credentials.

## API Endpoints

### Register Device Token
```
POST /api/register-device
Content-Type: application/json

{
    "user_id": 1,
    "device_token": "your-fcm-token-here",
    "platform": "ios" // or "android"
}
```

### Get Device Tokens (for debugging)
```
GET /api/device-tokens
```

## Testing Firebase Configuration

You can test your Firebase configuration by:

1. Registering a test device token via the API
2. Sending a test notification through the admin panel
3. Checking the notification history for success/failure rates

## Mobile App Integration

To integrate with your mobile app:

1. Initialize Firebase in your mobile app
2. Get the FCM token from your app
3. Send the token to your Laravel API using the `/api/register-device` endpoint
4. Users will now receive notifications sent from the admin panel

## Troubleshooting

- **Firebase errors**: Check that your credentials JSON file path is correct and the file is readable
- **No device tokens**: Ensure your mobile app is calling the registration API
- **Notifications not delivered**: Check Firebase project settings and ensure FCM is enabled