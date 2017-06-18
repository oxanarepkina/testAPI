<?php

/**
 * Created by PhpStorm.
 * User: andrii
 * Date: 18/06/2017
 * Time: 14:54
 */
class IllegalApiParamsException extends Exception
{
    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}