<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yunuserenguzel
 * Date: 9/21/13
 * Time: 3:04 PM
 * To change this template use File | Settings | File Templates.
 */

require_once('../lib/DatabaseManager.php');
require_once('../lib/Util.php');

class User {
    public static function IsUsernameExists($username){
        $username = mysql_real_escape_string($username);

        $sql = "SELECT 1 isExist FROM user WHERE username='$username'";
        if(DatabaseConnector::get_value($sql) == '1'){
            return true;
        }
        else {
            return false;
        }
    }

    public static function IsEmailExists($email){
        $email= mysql_real_escape_string($email);

        $sql = "SELECT 1 isExist FROM user WHERE email='$email'";
        if(DatabaseConnector::get_value($sql) == '1'){
            return true;
        }
        else {
            return false;
        }
    }

    public static function IsUserLoginCorrect($usernameOrEmail,$passhash){
        $usernameOrEmail = mysql_real_escape_string($usernameOrEmail);
        $passhash = mysql_real_escape_string($passhash);

        $sql = "SELECT id FROM user WHERE (username='$usernameOrEmail' OR email='$usernameOrEmail') AND passhash='$passhash' ";

        if($user_id = DatabaseConnector::get_value($sql)){
            return $user_id;
        }
        else{
            return false;
        }
    }

    public static function NewUser($username,$email,$passhash){
        $username = mysql_real_escape_string($username);
        $email = mysql_real_escape_string($email);
        $passhash = mysql_real_escape_string($passhash);

        $userId = Util::GenerateUniqueId();

        $sql = "INSERT INTO `user`(`id`, `username`, `email`, `passhash`) VALUES ('$userId','$username','$email','$passhash')";
        DatabaseConnector::query($sql);

        return $userId;
    }

    public static function DeleteUser($userId){
        $userId = mysql_real_escape_string($userId);
        DatabaseConnector::query("DELETE FROM `user` WHERE id='$userId'");
        //TODO: delete all user data among the system
    }
    public static function UpdateUserName($userId,$username){
        $userId = mysql_real_escape_string($userId);
        $username = mysql_real_escape_string($username);

        $sql = "UPDATE `user` SET `username` = '$username' WHERE `id` = '$userId'";
        return DatabaseConnector::query($sql);
    }
    public static function UpdateEmail($userId,$email){
        $userId = mysql_real_escape_string($userId);
        $email = mysql_real_escape_string($email);

        $sql = "UPDATE `user` SET `email` = '$email' WHERE `id` = '$userId'";
        return DatabaseConnector::query($sql);

    }
    public static function UpdateName($userId,$name){
        $userId = mysql_real_escape_string($userId);
        $name = mysql_real_escape_string($name);

        $sql = "UPDATE `user` SET `realname` = '$name' WHERE `id` = '$userId'";
        return DatabaseConnector::query($sql);

    }
    public static function UpdatePasshash($userId,$passhash){
        $userId = mysql_real_escape_string($userId);
        $passhash = mysql_real_escape_string($passhash);

        $sql = "UPDATE `user` SET `passhash` = '$passhash' WHERE `id` = '$userId'";
        return DatabaseConnector::query($sql);

    }
    public static function UpdateProfileImage($userId,$profileImageName){
        $userId = mysql_real_escape_string($userId);
        $profileImageName = mysql_real_escape_string($profileImageName);

        $sql = "UPDATE `user` SET `profile_image` = '$profileImageName' WHERE `id` = '$userId'";
        return DatabaseConnector::query($sql);

    }
    public static function FollowUser($followerUserId,$followedUserId){
        $followedUserId = mysql_real_escape_string($followedUserId);
        $followerUserId = mysql_real_escape_string($followerUserId);

        $sql = "INSERT INTO `following`(`follower_id`, `followed_id`) VALUES ('$followerUserId','$followedUserId')";
        return DatabaseConnector::query($sql);

    }
    public  static function UnFollowUser($followerUserId,$followedUserId){
        $followerUserId = mysql_real_escape_string($followerUserId);
        $followedUserId = mysql_real_escape_string($followedUserId);

        $sql = "DELETE FROM `following` WHERE `follower_id`='$followerUserId' AND `followed_id`='$followedUserId'";
        return DatabaseConnector::query($sql);
    }
    public static function GetFollowerListOfUser($userId,$pageNumber,$pageCount){
        $userId = mysql_real_escape_string($userId);
        $pageNumber = mysql_real_escape_string($pageNumber);
        $pageCount = mysql_real_escape_string($pageCount);

        $start = $pageCount * $pageNumber;

        $sql = "SELECT *
                FROM `following` AS F
                INNER JOIN `user` AS U ON U.id=F.follower_id
                WHERE F.followed_id=U.id AND U.id='$userId'
                LIMIT BY $start, $pageCount";
        return DatabaseConnector::get_results($sql);
    }
    public static function GetFollowedListOfUser($userId,$pageNumber,$pageCount){
        $userId = mysql_real_escape_string($userId);
        $pageNumber = mysql_real_escape_string($pageNumber);
        $pageCount = mysql_real_escape_string($pageCount);

        $start = $pageCount * $pageNumber;

        $sql = "SELECT *
                FROM `following` AS F
                INNER JOIN `user` AS U ON U.id=F.followed_id
                WHERE F.follower_id=U.id AND U.id='$userId'
                LIMIT BY $start, $pageCount";
        return DatabaseConnector::get_results($sql);
    }

    public static function CreateAuthenticationForUser($userId,$platform){
        $userId = mysql_real_escape_string($userId);
        $platform = mysql_real_escape_string($platform);

        $token = Util::GenerateAuthToken();

        $sql = "INSERT INTO `authentication`(`token`, `user_id`, `creation_date`, `platform`, `is_active`) VALUES ('$token','$userId',Now(),'$platform',1)";
        DatabaseConnector::query($sql);
        return $token;

    }


    public static function isUserAuthenticated($token){
        $token = mysql_real_escape_string($token);
        $sql = "SELECT 1 as isAuthenticated FROM authentication WHERE toke = '$token'";
        if(DatabaseConnector::get_value($sql) == '1'){
            return true;
        }
        else {
            return false;
        }
    }

    public static function GetAuthenticatedUser($token){
        $token = mysql_real_escape_string($token);
        $sql = "SELECT *
                FROM user AS U
                INNER JOIN authentication A ON A.user_id=U.id
                WHERE A.token = '$token' AND a.is_active=1";
        return DatabaseConnector::get_single($sql);
    }


    public static function SearchUser($query){
        $query = mysql_real_escape_string($query);
        $sql = "SELECT * FROM user WHERE username LIKE '%$query%' OR `name` LIKE '%$query%' OR email LIKE '%$query%'";
        return DatabaseConnector::get_results($sql);
    }
}