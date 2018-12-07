<?php
/**
 * Created by PhpStorm.
 * User: text_
 * Date: 06/12/2018
 * Time: 18:09
 */

namespace App\Event;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\EventDispatcher\Event;



class ForgotPassEvent extends Event
{
    const FORGOT_PASS = 'forgot_pass';

    protected $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }
    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }
}