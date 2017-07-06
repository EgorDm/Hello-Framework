<?php
/**
 * Created by PhpStorm.
 * User: egordm
 * Date: 7-7-17
 * Time: 0:19
 */

namespace Framework\Core;


class Logger
{
    const LOG_DIR = __ROOT__.'/temp';
    const LOG_FILE = self::LOG_DIR.'/logs.log';

    /**
     * The file permissions.
     */
    const FILE_CHMOD = 756;

    private static $log_file;

    public static function initialize()
    {
        return self::open();
    }

    public static function open() {
        if (self::isOpen()) return true;

        if(!is_dir(self::LOG_DIR)){
            if(!mkdir(self::LOG_DIR, self::FILE_CHMOD, true)){
                throw new \Exception('Can\'t find log directory nor create it.');
            }
        }

        if(!self::$log_file = fopen(self::LOG_FILE, 'a+')){
            throw new \Exception('Could not open or create the log.');
        }
        return true;
    }

    public static function close()
    {
        if (self::isOpen()) {
            fclose(self::$log_file);
        }
    }

    public static function error(\Throwable $exception)
    {
        $message = sprintf("Exception: \"%s\" in file: \"%s\" at line %d.\n\tStackTrace: %s",
            $exception->getMessage(), $exception->getFile(), $exception->getLine(), $exception->getTraceAsString());
        self::log($message);
    }

    public static function log($message)
    {
        if (!self::isOpen()) return;
        flock(self::$log_file, LOCK_EX);
        fwrite(self::$log_file, "$message\n");
        flock(self::$log_file, LOCK_UN);
    }

    private static function isOpen() {
        return self::$log_file != null;
    }
}