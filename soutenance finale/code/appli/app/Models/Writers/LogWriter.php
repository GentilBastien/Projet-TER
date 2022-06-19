<?php

namespace App\Models\Writers;

use App\Models\Settings;
use Exception;

class LogWriter
{
    public static function addLog(String $msg)
    {
        /**
         * Format defaults.
         */
        date_default_timezone_set(Settings::$DEFAULT_LOCALE);
        $date = date(Settings::$DEFAULT_DATETIME_FORMAT, time());
        /**
         * Creates new log in the file.
         */
        $log = "[" . $date . "]" . $msg . PHP_EOL;
        /**
         * Writes it.
         */
        file_put_contents(
            $filename = Settings::$LOG_PATH . Settings::$LOG_FILENAME,
            $data = $log,
            $flags = FILE_APPEND
        );
    }
}
