<?php namespace App\Exceptions;

/**
 * Исключение "нет ключей"
 * Class NoKeysException
 * @package App\Exceptions
 */
class NoKeysException extends \Exception {
    public function __construct($message = null, \Exception $previous = null, $code = 0)
    {
        parent::__construct($message, $code, $previous);
    }
}