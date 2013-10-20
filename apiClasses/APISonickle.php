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
    public static function CreateSonickle($sonickleData,$latitude,$longitude){
        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        $sonickleId = Sonickle::NewSonickle($userId,$latitude,$longitude,0);
        //TODO: move sonickle to sonickles folder using sonickle id as name
        return successfulOperation();
    }

    public static function GetMySonickles($pageNumber,$pageCount){
        CheckPageNumberAndCount($pageNumber,$pageCount);
        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        $result = new stdClass();
        $result->sonickles = Sonickle::GetSonickleListOfUser($userId,$pageNumber,$pageCount);
        return $result;
    }

    public static function GetSonickleFeed($pageNumber,$pageCount){
        CheckPageNumberAndCount($pageNumber,$pageCount);
        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        $result = new stdClass();
        $result->sonickles = Sonickle::GetSonickleFeedForUser($userId,$pageNumber,$pageCount);
        return $result;
    }

    public static function LikeSonickle($sonickleId){
        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        if(Sonickle::isSonickleExists($sonickleId) == false){
            throwError(ApiInputDoesNotExistError,"Given sonickle id does not exists : ".$sonickleId);
        }
        Sonickle::LikeSonickle($userId,$sonickleId);
        Notification::CreateNotification(NotificationTypeSonickleLike,$userId,$sonickleId,null);
        return successfulOperation();
    }

    public static function UnLikeSonickle($sonickleId){
        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        Sonickle::UnLikeSonickle($userId,$sonickleId);
        return successfulOperation();
    }

    public static function DeleteSonickle($sonickleId){
        Sonickle::DeleteSonickle($sonickleId);
        return successfulOperation();
    }

}