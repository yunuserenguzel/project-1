<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yunuserenguzel
 * Date: 9/21/13
 * Time: 2:36 PM
 * To change this template use File | Settings | File Templates.
 */

require_once('../model/User.php');
require_once('../model/Notification.php');

class APIUser{

    public static function Login($usernameOrEmail,$password,$platform){
        $passhash = Util::GeneratePassHash($password);
        if($userId = User::IsUserLoginCorrect($usernameOrEmail,$passhash)){
            $result = new stdClass();
            $result->token = User::CreateAuthenticationForUser($userId,$platform);
            return $result;
        }
        else {
            throwError(ApiAuthenticationFailedError,"username or password is wrong");
        }

    }

    public static function Register($username,$email,$password,$platform){
        //TODO: email validation is required
        if(User::IsUsernameExists($username) == true){
            throwError(ApiUsernameExistsError,"this username is taken $username");
        }
        if(User::IsEmailExists($email) == true){
            throwError(ApiEmailExistsError,"this email is taken $email");
        }
        $passhash = Util::GeneratePassHash($password);
        $userId = User::NewUser($username,$email,$passhash);
        $result = new stdClass();
        $result->user_id = $userId;
        $result->token = User::CreateAuthenticationForUser($userId,$platform);
        return $result;
    }

    public static function ResetPassword($email){

    }

    public static function UpdateInformation($field,$value){

        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        if($field == "username"){
            User::UpdateUserName($userId,$value);
        }
        else if($field == "password"){
            $passhash = Util::GeneratePassHash($value);
            User::UpdatePasshash($userId,$passhash);
        }
        else if($field == "email"){
            User::UpdateEmail($userId,$value);
        }
        else if($field == "name"){
            User::UpdateName($userId,$value);
        }
        else if($field == "profile_image"){
            $image = $_FILES['image'];
//            $value = AuthenticationManager::AuthenticatedUser()->username . Util::GenerateUniqueId();
            //TODO: save image to disk
        }
        else
            throwError(ApiInvalidFieldNameError,"Field name is invalid: $field");
        return successfulOperation();

    }

    public static function Follow($userIdToBeFollowed){
        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        User::FollowUser($userId,$userIdToBeFollowed);
        Notification::CreateNotification(NotificationTypeUserFollow,$userId,null,$userIdToBeFollowed);
        return successfulOperation();
    }

    public static function UnFollow($userIdToBeFollowed){
        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        User::UnFollowUser($userId,$userIdToBeFollowed);
        return successfulOperation();
    }

    public static function GetFollowerList($pageNumber,$pageCount){
        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        CheckPageNumberAndCount($pageNumber,$pageCount);
        $result = new stdClass();
        $result->users = User::GetFollowerListOfUser($userId,$pageNumber,$pageCount);
        return $result;
    }

    public static function GetFollowedList($pageNumber,$pageCount){
        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        CheckPageNumberAndCount($pageNumber,$pageCount);
        $result = new stdClass();
        $result->users = User::GetFollowedListOfUser($userId,$pageNumber,$pageCount);
        return $result;
    }

    public static function SearchUser($query){

    }


}