<?php
/**
 * Created by PhpStorm.
 * User: text_
 * Date: 25/10/2018
 * Time: 10:23
 */

namespace App\Exceptions;


class AccountInactiveException extends \Exception
{
    /**
     * AccountInactiveException constructor.
     * @param $message
     */
    public function __construct($message)
    {
        parent::__construct($message);
    }
}