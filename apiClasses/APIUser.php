<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yunuserenguzel
 * Date: 9/21/13
 * Time: 2:36 PM
 * To change this template use File | Settings | File Templates.
 */

require_once('../model/User.php');

class APIUser{

    public static function Login($usernameOrEmail,$password){

    }

    public static function Register($username,$email,$password){
        if(User::IsUsernameExists($username) == true){

        }
        if(User::IsEmailExists($email) == true){

        }
        User::NewUser($username,$email,$password);

    }

    public static function ResetPassword($email){

    }

    public static function UpdateInformation($field,$value){

    }

    public static function Follow($userIdToBeFollowed){

    }

    public static function UnFollow($userIdToBeFollowed){

    }

    public static function GetFollowerList($pageNumber,$pageCount){

    }

    public static function GetFollowedList($pageNumber,$pageCount){

    }

    public  static function SearchUser($query){

    }


}