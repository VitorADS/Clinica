<?php

namespace App\Session\Admin;

use App\Models\Entitys\User;

class Login{

    /**
     * 
     */
    private static function init()
    {
        if(session_status() != PHP_SESSION_ACTIVE){
            session_start();
        }
    }

    /**
     * @param User $user
     * @return bool
     */
    public static function login(User $user): bool
    {
        self::init();

        $_SESSION['admin']['usuario'] = [
            'id' => $user->getId(),
            'nome' => $user->getNome(),
            'email' => $user->getEmail()
        ];

        return true;
    }

    /**
     * @return boolean
     */
    public static function isLogged()
    {
        self::init();

        return isset($_SESSION['admin']['usuario']['id']);
    }

    /**
     * 
     */
    public static function logout()
    {
        self::init();

        unset($_SESSION['admin']['usuario']);
        return true;
    }

    /**
     * @param User $user
     * @return bool
     */
    public static function verificaUsuarioLogado(User $user): bool
    {
        if(self::isLogged() && $user->getId() === $_SESSION['admin']['usuario']['id']){
            return true;
        }

        return false;
    }
}