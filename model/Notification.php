<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yunuserenguzel
 * Date: 9/21/13
 * Time: 4:02 PM
 * To change this template use File | Settings | File Templates.
 */

require_once('../lib/DatabaseManager.php');
require_once('../lib/Util.php');

define("NotificationTypeUserFollow",NotificationTypeUserFollow);
define("NotificationTypeSonickleLike",NotificationTypeSonickleLike);


class Notification {

    public static function CreateNotification($type,$userId,$sonickleId,$otherUserId){
        $type = mysql_real_escape_string($type);
        $userId = mysql_real_escape_string($userId);
        $sonickleId = mysql_real_escape_string($sonickleId);
        $otherUserId = mysql_real_escape_string($otherUserId);

        $notificationId = Util::GenerateUniqueId();

        $sql = "INSERT INTO `notification`(`id`, `type`, `actor_user_id`, `sonickle_id`, `affected_user_id`, `action_date`)
                VALUES ('$notificationId','$type','$userId','$sonickleId','$otherUserId',Now())";
        DatabaseConnector::query($sql);

        return $notificationId;

    }
    public static function GetNotificationListOfUser($userId,$pageNumber,$pageCount){
        $userId = mysql_real_escape_string($userId);
        $pageNumber = mysql_real_escape_string($pageNumber);
        $pageCount = mysql_real_escape_string($pageCount);

        $start = $pageCount * $pageNumber;

        $sql = "SELECT *
                FROM notification N
                WHERE N.affected_user_id = '$userId'
                LIMIT BY $start,$pageCount";
        return DatabaseConnector::get_results($sql);
    }
    public static function ReadNotification($notificationId){
        $notificationId = mysql_real_escape_string($notificationId);

        $sql = "UPDATE notification SET isRead=1 WHERE id='$notificationId'";
        return DatabaseConnector::query($sql);
    }

}