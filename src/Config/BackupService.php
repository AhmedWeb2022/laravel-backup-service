<?php

return [
    'google_drive' => [
        'clientId' => env('GOOGLE_DRIVE_CLIENT_ID'),
        'clientSecret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
        'refreshToken' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
        'folderId' => env('GOOGLE_DRIVE_FOLDER_ID', 'root'),
        'projectName' => env('GOOGLE_DRIVE_PROJECT_NAME', 'Default Project'),
    ],
    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
        'chatId' => env('TELEGRAM_CHAT_ID'),
    ],
];
