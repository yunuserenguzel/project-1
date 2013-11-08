<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yunuserenguzel
 * Date: 9/21/13
 * Time: 2:36 PM
 * To change this template use File | Settings | File Templates.
 */

require_once('../model/Sonic.php');

class APISonic
{

    /*  pre-condition:
     *
     * */
    public static function CreateSonic($sonicData,$latitude,$longitude){
        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        $sonicId = Sonic::NewSonic($userId,$latitude,$longitude,0);
        //TODO: move sonic to sonics folder using sonic id as name
        file_put_contents("../sonic/".$sonicId.".snc",$sonicData);
        $result = new stdClass();
        $result->sonic_id = $sonicId;
        return $result;

    }

    public static function GetMySonics($pageNumber,$pageCount){
        CheckPageNumberAndCount($pageNumber,$pageCount);
        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        $result = new stdClass();
        $result->sonics = Sonic::GetSonicListOfUser($userId,$pageNumber,$pageCount);
        return $result;
    }

    public static function GetSonicFeed($pageNumber,$pageCount){
        CheckPageNumberAndCount($pageNumber,$pageCount);
        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        $result = new stdClass();
        $result->sonics = Sonic::GetSonicFeedForUser($userId,$pageNumber,$pageCount);
        return $result;
    }

    public static function LikeSonic($sonicId){
        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        if(Sonic::isSonicExists($sonicId) == false){
            throwError(ApiInputDoesNotExistError,"Given sonic id does not exists : ".$sonicId);
        }
        Sonic::LikeSonic($userId,$sonicId);
        Notification::CreateNotification(NotificationTypeSonicLike,$userId,$sonicId,null);
        return successfulOperation();
    }

    public static function UnLikeSonic($sonicId){
        $userId = AuthenticationManager::AuthenticatedUser()->user_id;
        Sonic::UnLikeSonic($userId,$sonicId);
        return successfulOperation();
    }

    public static function DeleteSonic($sonicId){
        Sonic::DeleteSonic($sonicId);
        return successfulOperation();
    }

}