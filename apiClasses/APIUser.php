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

    public static function Register($username,$email,$password,$platform){
        if(User::IsUsernameExists($username) == true){
            throwError(ApiUsernameExistsError,"this username is taken $username");
        }
        if(User::IsEmailExists($email) == true){
            throwError(ApiEmailExistsError,"this email is taken $email");
        }
        $userId = User::NewUser($username,$email,$password);
        $result = new stdClass();
        $result->token = User::CreateAuthenticationForUser($userId,$platform);
        return $result;
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