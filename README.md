# Laravel Backup Service

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ahmedweb/laravel-backup-service.svg)](https://packagist.org/packages/ahmedweb/laravel-backup-service)
[![License: MIT](https://img.shields.io/github/license/ahmedweb/laravel-backup-service.svg)](LICENSE.md)

**Laravel Backup Service** is a powerful and developer-friendly package designed to automate your Laravel application backups. It seamlessly integrates with **Google Drive** and **Telegram**, offering convenient backup uploads, link notifications, and scheduled cleanup – all through simple Artisan commands.

---

## 🚀 Features

* 📁 Backup uploads directly to **Google Drive**
* 🔗 Auto-send backup download links to **Telegram**
* 📌 Store and manage latest backup file links
* 🧹 Automatically delete outdated backup files
* ✅ Clean, modular, and maintainable code structure
* 💻 Easy-to-use Artisan commands
* 🕐 Fully schedulable via Laravel Scheduler

---

## 📦 Installation

Install the package via Composer:

```bash
composer require ahmedweb/laravel-backup-service
```

Publish the configuration and service provider:

```bash
php artisan vendor:publish --tag=laravel-backup-service
```

---

## ⚙️ Configuration

### 1. Environment Setup

Add the following variables to your `.env` file:

```env
FILESYSTEM_CLOUD=google

GOOGLE_DRIVE_CLIENT_ID=your-client-id
GOOGLE_DRIVE_CLIENT_SECRET=your-client-secret
GOOGLE_DRIVE_REFRESH_TOKEN=your-refresh-token
GOOGLE_DRIVE_FOLDER_ID=your-folder-id
# Optional custom folder name
GOOGLE_DRIVE_FOLDER=

TELEGRAM_BOT_TOKEN=your-telegram-bot-token
TELEGRAM_CHAT_ID=your-telegram-chat-id
```

---

### 2. Filesystem Configuration

Update `config/filesystems.php` with a new `google_drive` disk:

```php
'google_drive' => [
    'driver' => 'google',
    'clientId' => env('GOOGLE_DRIVE_CLIENT_ID'),
    'clientSecret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
    'refreshToken' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
    'folder' => env('GOOGLE_DRIVE_FOLDER'),
    'folderId' => env('GOOGLE_DRIVE_FOLDER_ID'),
],
```

---

## 💬 Telegram Integration

### Step 1: Create a Telegram Bot

1. Open Telegram and search for `@BotFather`.
2. Use `/newbot` and follow the prompts to create a bot.
3. Copy the provided token and paste it into your `.env` as `TELEGRAM_BOT_TOKEN`.

### Step 2: Add Bot to a Group

1. Create a group or use an existing one.
2. Add your bot to the group.
3. Mention the bot once to activate it.

### Step 3: Get Group Chat ID

* Use `@userinfobot` or check the `chat.id` field via bot API messages.
* Paste the group chat ID into your `.env` as `TELEGRAM_CHAT_ID`.

---

## ⚙️ Laravel 11 Integration

### Register Storage Provider

In `bootstrap/app.php`, register the Google Drive provider:

```php
use AhmedWeb\LaravelBackupService\Providers\GoogleDriveStorageProvider;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        GoogleDriveStorageProvider::class,
    ])
    ->create();
```

---

## ⏰ Scheduling Backups (Optional)

In `bootstrap/app.php`, add scheduled commands:

```php
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('backup:run')->daily();
        $schedule->command('backup:clean')->daily();
        $schedule->command('backup:store-latest-link')->dailyAt('01:00');
        $schedule->command('backup:delete-old')->weekly();
    })
    ->create();
```

Ensure Laravel Scheduler is set in your server cron:

```bash
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```

---

## 🧪 Available Artisan Commands

| Command                    | Description                                                   |
| -------------------------- | ------------------------------------------------------------- |
| `backup:store-latest-link` | Stores and sends the latest backup download link via Telegram |
| `backup:delete-old`        | Deletes outdated backup files from Google Drive               |
| `backup:clean-drive`       | Cleans up Google Drive backups according to retention rules   |

> For Laravel 10 or below, schedule commands in `app/Console/Kernel.php`.

---

## 📁 File Structure

```
laravel-backup-service/
├── src/
│   ├── Commands/
│   │   ├── StoreLatestBackupLink.php
│   │   ├── DeleteOldBackupFiles.php
│   │   └── CleanGoogleDriveBackups.php
│   ├── Services/
│   │   └── GoogleDriveBackupService.php
│   ├── Providers/
│   │   └── GoogleDriveStorageProvider.php
├── config/
│   └── filesystems.php (optional override)
```

---

## ✅ Requirements

| Dependency                          | Version |
| ----------------------------------- | ------- |
| PHP                                 | ^8.2    |
| Laravel                             | ^11.31  |
| `spatie/laravel-backup`             | ^9.3    |
| `google/apiclient`                  | ^2.15   |
| `irazasyed/telegram-bot-sdk`        | ^3.15   |
| `masbug/flysystem-google-drive-ext` | ^2.4    |
| `yaza/laravel-google-drive-storage` | ^4.1    |

---

## 🤝 Contributing

Contributions are welcome! Feel free to fork the repo, submit issues, or open pull requests for any improvements or fixes.

---

## 📜 License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

---

## 👨‍💻 Author

**Ahmed Web**
📧 [ahmedwry588@gmail.com](mailto:ahmedwry588@gmail.com)
🌐 [GitHub](https://github.com/ahmedweb)

---

## 🔗 Resources

* [Google Developer Console](https://console.cloud.google.com) – Create and manage your credentials
* [OAuth 2.0 Playground](https://developers.google.com/oauthplayground) – Retrieve your refresh token
* [Spatie Laravel Backup Docs](https://spatie.be/docs/laravel-backup)
* [Telegram Bot API](https://core.telegram.org/bots/api)


