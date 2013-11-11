<?php
/**
 * Created by JetBrains PhpStorm.
 * User: yunuserenguzel
 * Date: 9/30/13
 * Time: 9:35 AM
 * To change this template use File | Settings | File Templates.
 */

define("ApiAuthenticationFailedError", 2001);
define("ApiParameterMissingError",1000);
define("ApiUnAuthorizedOperationError",2002);
define("ApiUsernameExistsError",3001);
define("ApiEmailExistsError",3002);
define("ApiInvalidFieldNameError",3003);
define("ApiInvalidInputError",3004);
define("ApiInputDoesNotExistError",3005);
function throwError($code,$description){
    $result = new stdClass();
    $result->error = true;
    $result->error_code = $code;
    $result->error_message = $description;
    die(json_encode($result));
}
