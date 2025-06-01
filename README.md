Here is your **updated `README.md`** file with the **Telegram Bot setup** and **Laravel 11 provider/schedule injection instructions** fully integrated.

---

````markdown
# Laravel Backup Service

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ahmedweb/laravel-backup-service.svg)](https://packagist.org/packages/ahmedweb/laravel-backup-service)
[![License: MIT](https://img.shields.io/github/license/ahmedweb/laravel-backup-service.svg)](LICENSE.md)

A Laravel package that simplifies application backups using Google Drive storage, provides Artisan commands to manage them, and sends the latest backup link directly to your Telegram group.

---

## 🚀 Features

* Upload Laravel backups to **Google Drive**
* Send **backup download link to Telegram**
* Automatically **store and manage backup links**
* Remove **old backup files** from Google Drive
* Clean and readable service structure
* Easy-to-use Artisan commands

---

## 📦 Installation

Install the package using Composer:

```bash
composer require ahmedweb/laravel-backup-service
````

After installation, publish the package configuration and files with:

```bash
php artisan vendor:publish --tag=laravel-backup-service
```

---

## 🛠️ Configuration

### 1. Environment Variables

Add the following to your `.env` file:

```env
FILESYSTEM_CLOUD=google

GOOGLE_DRIVE_CLIENT_ID=your-google-client-id
GOOGLE_DRIVE_CLIENT_SECRET=your-google-client-secret
GOOGLE_DRIVE_REFRESH_TOKEN=your-google-refresh-token
GOOGLE_DRIVE_FOLDER_ID=your-google-folder-id
# Optional
GOOGLE_DRIVE_FOLDER=

TELEGRAM_BOT_TOKEN=your-telegram-bot-token
TELEGRAM_CHAT_ID=your-group-chat-id
```

---

### 2. Filesystem Configuration

In `config/filesystems.php`, add the Google Drive disk:

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

## 💬 Telegram Setup

To enable Telegram backup link notifications:

### Step-by-step:

#### Step 1: Create Your Telegram Bot

1. **Open Telegram**: Launch the Telegram app or go to the [web version](https://web.telegram.org/).
2. **Find BotFather**: Search for `@BotFather` and start a conversation.
3. **Start a Chat**: Click “Start” or type `/start`.
4. **Create a New Bot**: Use `/newbot` and follow prompts to set:

   * **Name** (e.g., My Awesome Bot)
   * **Username** (must end in `bot`, e.g., `myawesome_bot`)
5. **Get Your API Token**: BotFather will return a token like:

   ```
   123456789:ABCdefGhIJKlmNoPQRstUvWxYz1234567890
   ```

   Save it for your `.env` file.

#### Step 2: Add Bot to Group

1. Create a new group in Telegram.
2. Add your bot to the group.
3. Mention the bot once to activate it.

#### Step 3: Get Group Chat ID

* Use `@userinfobot` or your bot logs.
* Or send a message using your bot and inspect the `chat.id` in the response.

#### Step 4: Update `.env`

```env
TELEGRAM_BOT_TOKEN=123456789:ABCdefGhIJKlmNoPQRstUvWxYz1234567890
TELEGRAM_CHAT_ID=-1001234567890
```

---

## 🧩 Application Bootstrap Setup (Laravel 11)

If you're using **Laravel 11**, ensure you inject the storage provider and optionally schedule the backup commands:

### Register the `GoogleDriveStorageProvider`

In `bootstrap/app.php`, add:

```php
use AhmedWeb\LaravelBackupService\Providers\GoogleDriveStorageProvider;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        GoogleDriveStorageProvider::class,
    ])
    ->create();
```

### Optional: Add Scheduled Commands

Still in `bootstrap/app.php`, you can define the scheduler:

```php
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('backup:run')->everyMinute();
        $schedule->command('backup:clean')->everyMinute();
        $schedule->command('backup:store-link', ['sass'])->everyMinute();
        $schedule->command('backup:delete-old-files')->everyMinute();
    })
    ->create();
```

Ensure your system cron is running Laravel’s scheduler:

```bash
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```

---

## 🧪 Artisan Commands

| Command                                | Description                                                  |
| -------------------------------------- | ------------------------------------------------------------ |
| `php artisan backup:store-latest-link` | Stores the latest backup file link and sends it via Telegram |
| `php artisan backup:delete-old`        | Deletes all older backup files from Google Drive             |
| `php artisan backup:clean-drive`       | Cleans backups on Google Drive based on retention            |

You can schedule these in `app/Console/Kernel.php` (Laravel <=10):

```php
$schedule->command('backup:store-latest-link')->daily();
$schedule->command('backup:delete-old')->weekly();
```

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
│   └── filesystems.php (merged if needed)
```

---

## 📜 Requirements

| Package                             | Version |
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

Feel free to fork this package, suggest changes, or submit PRs. Any improvements are welcome!

---

## 📜 License

Licensed under the [MIT license](LICENSE.md).

---

## 🧑‍💻 Author

**Ahmed Web**
📧 [ahmedwry588@gmail.com](mailto:ahmedwry588@gmail.com)
🌍 [GitHub Profile](https://github.com/ahmedweb)

---

## 🔗 Helpful Resources

* [Google Developer Console](https://console.cloud.google.com) — Get credentials
* [OAuth Playground](https://developers.google.com/oauthplayground) — Get refresh token
* [Detailed Tutorial](https://medium.com/@al_imran_ahmed/how-to-backup-your-laravel-application-in-google-drive-2803c31756a0)


