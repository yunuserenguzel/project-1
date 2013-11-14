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
class Sonic {
    public static function NewSonic($userId,$latitude,$longitude,$privacy){
        $userId = mysql_real_escape_string($userId);
        $latitude = mysql_real_escape_string($latitude);
        $longitude = mysql_real_escape_string($longitude);
        $privacy = mysql_real_escape_string($privacy);

        $sonicId = Util::GenerateUniqueId();

        $sql = "INSERT INTO `sonic`(`id`, `creation_date`, `latitude`, `longitude`, `isPrivate`) VALUES ('$sonicId',Now(),$latitude,$longitude,$privacy)";
        DatabaseConnector::query($sql);

        $sql = "INSERT INTO `sonicowner`(`sonic_id`, `user_id`) VALUES ('$sonicId','$userId')";
        DatabaseConnector::query($sql);
        //TODO: move sonic to sonic folder
        return $sonicId;
    }
    public static function GetSonicListOfUser($userId,$pageNumber,$pageCount){
        $userId = mysql_real_escape_string($userId);
        $pageNumber = mysql_real_escape_string($pageNumber);
        $pageCount = mysql_real_escape_string($pageCount);

        $start = $pageNumber * $pageCount;

        $sql = "SELECT *
                FROM `sonicowner`
                INNER JOIN sonic ON sonic.id = sonicowner.sonic_id
                WHERE sonicowner.user_id='$userId'
                LIMIT $start, $pageCount";
        return Sonic::InsertUserInfoToSonics(DatabaseConnector::get_results($sql));

    }
    private static function InsertUserInfoToSonics($sonics){
        for($i=0;$i<count($sonics);$i++){
            $sonic = $sonics[$i];
            $sonic->user = User::GetUser($sonic->user_id);
        }
        return $sonics;
    }
    public static function GetSonicFeedForUser($userId,$pageNumber,$pageCount){
        $userId = mysql_real_escape_string($userId);
        $pageCount = mysql_real_escape_string($pageCount);
        $pageNumber = mysql_real_escape_string($pageNumber);

        $start = $pageNumber * $pageCount;

        $sql = "SELECT *
                FROM `following` AS F
                INNER JOIN sonicowner AS SO ON SO.user_id=F.followed_id
                INNER JOIN sonic AS S ON S.id=SO.sonic_id
                WHERE F.follower_id='$userId'
                LIMIT $start, $pageCount";
        return Sonic::InsertUserInfoToSonics(DatabaseConnector::get_results($sql));
    }
    public static function LikeSonic($userId,$sonicId){
        $userId = mysql_real_escape_string($userId);
        $sonicId = mysql_real_escape_string($sonicId);

        $sql = "INSERT INTO `likes`(`sonic_id`, `user_id`) VALUES ('$sonicId','$userId')";
        return DatabaseConnector::query($sql);
    }

    public static function UnLikeSonic($userId,$sonicId){
        $userId = mysql_real_escape_string($userId);
        $sonicId = mysql_real_escape_string($sonicId);

        $sql = "DELETE FROM `likes` WHERE sonic_id='$sonicId' AND user_id='$userId'";
        return DatabaseConnector::query($sql);
    }
    public static function DeleteSonic($sonicId){
        $sonicId = mysql_real_escape_string($sonicId);

        $sql = "DELETE FROM sonic WHERE id='$sonicId'";
        DatabaseConnector::query($sql);

        $sql = "DELETE FROM sonicowner WHERE sonicId='sonicId'";
        return DatabaseConnector::query($sql);
    }
    public static function isSonicExists($sonicId){
        $sonicId = mysql_real_escape_string($sonicId);
        $sql = "SELECT 1 AS isExist FROM sonic WHERE id='$sonicId'";
        if(DatabaseConnector::get_value($sql) == '1'){
            return true;
        }
        else {
            return false;
        }

    }
}