<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yunuserenguzel
 * Date: 9/21/13
 * Time: 3:35 PM
 * To change this template use File | Settings | File Templates.
 */
require_once('../lib/DatabaseManager.php');
require_once('../lib/Util.php');
class Sonickle {
    public static function NewSonickle($userId,$latitude,$longitude,$privacy){
        $userId = mysql_real_escape_string($userId);
        $latitude = mysql_real_escape_string($latitude);
        $longitude = mysql_real_escape_string($longitude);
        $privacy = mysql_real_escape_string($privacy);

        $sonickleId = Util::GenerateUniqueId();

        $sql = "INSERT INTO `sonickle`(`id`, `creation_date`, `latitude`, `longitude`, `isPrivate`) VALUES ('$sonickleId',Now(),$latitude,$longitude,$privacy)";
        DatabaseConnector::query($sql);

        $sql = "INSERT INTO `sonickleowner`(`sonickle_id`, `user_id`) VALUES ('$sonickleId','$userId')";
        DatabaseConnector::query($sql);
        //TODO: move sonickle to sonickle folder
        return $sonickleId;
    }
    public static function GetSonickleListOfUser($userId,$pageNumber,$pageCount){
        $userId = mysql_real_escape_string($userId);
        $pageNumber = mysql_real_escape_string($pageNumber);
        $pageCount = mysql_real_escape_string($pageCount);

        $start = $pageNumber * $pageCount;

        $sql = "SELECT *
                FROM `sonickleowner`
                INNER JOIN sonickle ON sonickle.id = sonickleowner.sonickle_id
                WHERE sonickleowner.user_id='$userId'
                LIMIT $start, $pageCount";
        return DatabaseConnector::get_results($sql);

    }
    public static function GetSonickleFeedForUser($userId,$pageNumber,$pageCount){
        $userId = mysql_real_escape_string($userId);
        $pageCount = mysql_real_escape_string($pageCount);
        $pageNumber = mysql_real_escape_string($pageNumber);

        $start = $pageNumber * $pageCount;

        $sql = "SELECT *
                FROM `following` AS F
                INNER JOIN sonickleowner AS SO ON SO.user_id=F.followed_id
                INNER JOIN sonickle AS S ON S.id=SO.sonickle_id
                WHERE F.follower_id='$userId'
                LIMIT $start, $pageCount";
        return DatabaseConnector::get_results($sql);
    }
    public static function LikeSonickle($userId,$sonickleId){
        $userId = mysql_real_escape_string($userId);
        $sonickleId = mysql_real_escape_string($sonickleId);

        $sql = "INSERT INTO `likes`(`sonickle_id`, `user_id`) VALUES ('$sonickleId','$userId')";
        return DatabaseConnector::query($sql);
    }

    public static function UnLikeSonickle($userId,$sonickleId){
        $userId = mysql_real_escape_string($userId);
        $sonickleId = mysql_real_escape_string($sonickleId);

        $sql = "DELETE FROM `likes` WHERE sonickle_id='$sonickleId' AND user_id='$userId'";
        return DatabaseConnector::query($sql);
    }
    public static function DeleteSonickle($sonickleId){
        $sonickleId = mysql_real_escape_string($sonickleId);

        $sql = "DELETE FROM sonickle WHERE id='$sonickleId'";
        DatabaseConnector::query($sql);

        $sql = "DELETE FROM sonickleowner WHERE sonickleId='sonickleId'";
        return DatabaseConnector::query($sql);
    }
    public static function isSonickleExists($sonickleId){
        $sonickleId = mysql_real_escape_string($sonickleId);
        $sql = "SELECT 1 AS isExist FROM sonickle WHERE id='$sonickleId'";
        if(DatabaseConnector::get_value($sql) == '1'){
            return true;
        }
        else {
            return false;
        }

    }
}