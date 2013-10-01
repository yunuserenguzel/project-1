<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yunuserenguzel
 * Date: 9/21/13
 * Time: 2:36 PM
 * To change this template use File | Settings | File Templates.
 */

require_once('../model/Sonickle.php');

class APISonickle
{

    /*  pre-condition:
     *
     * */
    public static function CreateSonickle($sonickle,$latitude,$longitude){
        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        $sonickleId = Sonickle::NewSonickle($userId,$latitude,$longitude,0);
        //TODO:move sonickle to sonickles folder using sonickle id as name
        return 1;
    }

    public static function GetMySonickles($pageNumber,$pageCount){
        CheckPageNumberAndCount($pageNumber,$pageCount);
        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        return Sonickle::GetSonickleListOfUser($userId,$pageNumber,$pageCount);
    }
    public static function GetSonickleFeed($pageNumber,$pageCount){
        CheckPageNumberAndCount($pageNumber,$pageCount);
        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        return Sonickle::GetSonickleFeedForUser($userId,$pageNumber,$pageCount);
    }
    public static function LikeSonickle($sonickleId){
        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        Sonickle::LikeSonickle($userId,$sonickleId);
        Notification::CreateNotification(NotificationTypeSonickleLike,$userId,$sonickleId,null);
        return 1;
    }
    public static function UnLikeSonickle($sonickleId){
        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        Sonickle::UnLikeSonickle($userId,$sonickleId);
        return 1;
    }
    public static function DeleteSonickle($sonickleId){
        Sonickle::DeleteSonickle($sonickleId);
        return 1;
    }

}