<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yunuserenguzel
 * Date: 9/30/13
 * Time: 9:35 AM
 * To change this template use File | Settings | File Templates.
 */

define("ApiAuthenticationFailedError", 1001);
define("ApiParameterMissingError",1000);
define("ApiUsernameExistsError",3001);
define("ApiEmailExistsError",3002);

function throwError($code,$description){
    $result = new stdClass();
    $result->error = new stdClass();
    $result->error->code = $code;
    $result->error->description = $description;
    die(json_encode($result));
}
