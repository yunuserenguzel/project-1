<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yunuserenguzel
 * Date: 9/22/13
 * Time: 11:44 AM
 * To change this template use File | Settings | File Templates.
 */


class Util {

    public static $SERVERNAME = "SNC001";

    public static function GenerateUniqueId(){
        return time().uniqid(Util::$SERVERNAME,true);
    }

    public static function GenerateAuthToken(){
        return time().uniqid(Util::$SERVERNAME) . uniqid() . uniqid();
    }

    public static function GeneratePassHash($password){
        return md5($password);
    }
}


function param($hash,$required = true, $code = 1001, $description = 'parameter missing '){
    $param =  isset($_GET[$hash]) ? $_GET[$hash] : (isset($_POST[$hash]) ? $_POST[$hash] : '');
    if($param == '' && $required){
        throwError($code,"$description: $hash");
    }
    return $param;
}

function CheckPageNumberAndCount($pageNumber,$pageCount){
    if(!is_numeric($pageNumber)){
        throwError(ApiInvalidInputError,"pageNumber should be a number");
    }
    if(!is_numeric($pageCount)){
        throwError(ApiInvalidInputError,"pageCount should be a number");
    }
    if($pageNumber < 0){
        throwError(ApiInvalidInputError,"pageNumber cannot be negative");
    }
    if($pageCount < 0){
        throwError(ApiInvalidInputError,"pageCount cannot be negative");
    }
}

function successfulOperation(){
    $result = new stdClass();
    $result->success = true;
    return $result;
}