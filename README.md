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
```

---
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

1. **Create a bot**

   * Go to [BotFather](https://t.me/BotFather) on Telegram
   * Use `/newbot` to create a bot
   * Save the **bot token**

2. **Create a Telegram group**

   * Add the bot to the group
   * Mention the bot once in the group to activate it

3. **Get your group chat ID**

   * Use a Telegram API tool like `@userinfobot` or your own bot's message logs
   * Or temporarily send a message from your bot and check the logs for `chat.id`

4. **Add to `.env`**:

   ```env
   TELEGRAM_BOT_TOKEN=123456:ABC-YourBotToken
   TELEGRAM_CHAT_ID=-1001234567890
   ```

Once configured, your bot will be able to post backup links directly to your group.

---

## 🧪 Artisan Commands

| Command                                | Description                                                  |
| -------------------------------------- | ------------------------------------------------------------ |
| `php artisan backup:store-latest-link` | Stores the latest backup file link and sends it via Telegram |
| `php artisan backup:delete-old`        | Deletes all older backup files from Google Drive             |
| `php artisan backup:clean-drive`       | Cleans backups on Google Drive based on retention            |

You can schedule these in `app/Console/Kernel.php`:

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
│   │   └── BackupServiceProvider.php
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

Would you like this `README.md` saved to your project directory or formatted for publishing on GitHub/Packagist?
