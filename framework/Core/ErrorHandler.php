<?php
/**
 * Created by PhpStorm.
 * User: egordm
 * Date: 7-7-17
 * Time: 10:27
 */

namespace Framework\Core;


class ErrorHandler
{


    /***
     * Response type
     * @var int
     */
    public $type;

    /**
     * ErrorHandler constructor.
     */
    public function __construct()
    {
        @set_exception_handler([$this, 'handle_exception']);
    }

    public function handle_exception(\Throwable $throwable)
    {
        //if($this->type == ?)
        echo self::formatException($throwable);

        \Framework\Core\Logger::error($throwable);
        \Framework\Core\Logger::close();

        //TODO: close everything, give error a cool layout, handle different return types
        //todo: $exception->getTrace()
    }

    public static function formatException(\Throwable $throwable)
    {
        return sprintf("Exception: \"%s\" in file: \"%s\" at line %d.\n\tStackTrace: %s",
            $throwable->getMessage(), $throwable->getFile(), $throwable->getLine(), $throwable->getTraceAsString());
    }
}