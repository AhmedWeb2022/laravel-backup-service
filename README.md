# Laravel Backup Service

[![Latest Version on Packagist](https://img.shields.io/packagist/v/ahmedweb/laravel-backup-service.svg)](https://packagist.org/packages/ahmedweb/laravel-backup-service)
[![License: MIT](https://img.shields.io/github/license/ahmedweb/laravel-backup-service.svg)](LICENSE.md)

A Laravel package that simplifies application backups using Google Drive storage, provides Artisan commands to manage them, and sends the latest backup link directly to your Telegram group.

---

## 🔐 Getting Google Drive Credentials & Refresh Token

To connect your Laravel backup service with Google Drive, you need credentials and a refresh token:

1. **Create a Google Cloud Project & OAuth Credentials**
   Visit the [Google Cloud Console](https://console.cloud.google.com)

   * Create a new project
   * Navigate to **APIs & Services > Credentials**
   * Create OAuth 2.0 Client ID credentials (Choose Application type: Web Application)
   * Note your **Client ID** and **Client Secret**

2. **Obtain the Refresh Token using OAuth 2.0 Playground**
   Go to the [OAuth 2.0 Playground](https://developers.google.com/oauthplayground)

   * In Step 1, select **Google Drive API v3** scopes (e.g., `https://www.googleapis.com/auth/drive.file`)
   * Click **Authorize APIs** and login with your Google account
   * In Step 2, click **Exchange authorization code for tokens**
   * Copy the **Refresh Token** provided

3. **Helpful Tutorial**
   For detailed step-by-step instructions, refer to this tutorial:
   [How to backup your Laravel application in Google Drive](https://medium.com/@al_imran_ahmed/how-to-backup-your-laravel-application-in-google-drive-2803c31756a0)

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

#### Step 1: Create Your Telegram Bot

1. **Open Telegram**
   Launch the Telegram app on your device or access the [web version](https://web.telegram.org).

2. **Find the BotFather**
   In the search bar, type `@BotFather` and select the official BotFather bot.

3. **Start a Chat**
   Click on the "Start" button or type `/start` to initiate the conversation with BotFather.

4. **Create a New Bot**
   Use the command `/newbot` to create a new bot. BotFather will ask you to choose a name and a username:

   * **Name**: This is the display name of your bot (e.g., `My Awesome Bot`)
   * **Username**: Must be unique and end with `bot` (e.g., `myawesome_bot`)

5. **Get Your API Token**
   Once your bot is created, BotFather will provide you with an API token. Example:

   ```
   123456789:ABCdefGhIJKlmNoPQRstUvWxYz1234567890
   ```

   Save this token, as you will need it to connect your Laravel application to your bot.

---

#### Step 2: Create a Telegram Group

1. Create a group and add your newly created bot to it.
2. Mention the bot once in the group to activate it.

---

#### Step 3: Get Your Group Chat ID

* Use a Telegram tool like `@userinfobot` or check your own bot's message logs
* Or send a message from your bot to the group, then inspect the logs for `chat.id`

---

#### Step 4: Add to `.env`

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

Would you like me to export this file (e.g., as `README.md`) or push it directly into your Laravel package repository?
