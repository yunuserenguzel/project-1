<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yunuserenguzel
 * Date: 9/22/13
 * Time: 11:44 AM
 * To change this template use File | Settings | File Templates.
 */

class Util {

    public static $SERVERNAME = "SNCKL001";

    public static function GenerateUniqueId(){
        return uniqid(Util::$SERVERNAME);
    }

    public static function GenerateAuthToken(){
        return uniqid(Util::$SERVERNAME) . uniqid() . uniqid();
    }
}


function param($hash,$required = true, $code = 1001, $description = 'parameter missing '){
    $param =  isset($_GET[$hash]) ? $_GET[$hash] : (isset($_POST[$hash]) ? $_POST[$hash] : '');
    if($param == '' && $required){
        throwError($code,"$description: $hash");
    }
    return $param;
}
