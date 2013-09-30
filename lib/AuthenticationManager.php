<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yunuserenguzel
 * Date: 9/29/13
 * Time: 3:49 PM
 * To change this template use File | Settings | File Templates.
 */

require_once('../model/User.php');

class AuthenticationManager {

    private static $AuthenticationToken = null;
    private static $AuthenticatedUser = null;

    public static function AuthenticateWithToken($token){
        if(AuthenticationManager::$AuthenticatedUser == null){
            $user = User::GetAuthenticatedUser($token);
            if($user != null)
            AuthenticationManager::$AuthenticatedUser = $user;
            AuthenticationManager::$AuthenticationToken = $token;
        }

    }

    public static function AuthenticatedUser(){
        return AuthenticationManager::$AuthenticatedUser;
    }

    public static function AuthenticationToken(){
        return AuthenticationManager::$AuthenticationToken;
    }
}