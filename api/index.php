<?php
error_reporting(NULL);
date_default_timezone_set('UTC');

require_once('../apiClasses/APINotification.php');
require_once('../apiClasses/APISonic.php');
require_once('../apiClasses/APIUser.php');
require_once('../apiClasses/APIError.php');
require_once('../lib/AuthenticationManager.php');
require_once('../lib/Util.php');


$cmd = param('cmd',true, 1000, "API command is missing");
$token = param('token',false);

DatabaseConnector::connect();

if($token != ''){
    AuthenticationManager::AuthenticateWithToken($token);
    if(AuthenticationManager::AuthenticatedUser() == null){
        throwError(2000,"Authentication token is invalid");
    }
}

$result = new stdClass();
switch($cmd){

    //user commands
    case 'login':
        $user = param('user');
        $password = param('password');
        $platform = param('platform');
        $result = APIUser::Login($user,$password,$platform);
        break;

    case 'register':
        $username = param('username');
        $password = param('password');
        $email = param('email');
        $platform = param('platform');
        $result = APIUser::Register($username,$email,$password,$platform);
        break;

    case 'reset_password':
        $email = param('email');
        $result = APIUser::ResetPassword($email);
        break;

    case 'update_profile':
        $field = param('field');
        $value = param('value');
        $result = APIUser::UpdateInformation($field,$value);
        break;

    case 'follow_user':
        $userId = param('user_id');
        $result = APIUser::Follow($userId);
        break;

    case 'unfollow_user':
        $userId = param('user_id');
        $result = APIUser::UnFollow($userId);
        break;

    case 'get_followed_list':
        $pageNumber = param('page_number');
        $pageCount = param('page_count');
        $result = APIUser::GetFollowedList($pageNumber,$pageCount);
        break;

    case 'get_follower_list':
        $pageNumber = param('page_number');
        $pageCount = param('page_count');
        $result = APIUser::GetFollowerList($pageNumber,$pageCount);
        break;

    case 'search_user':
        $query = param('query');
        $result = APIUser::SearchUser($query);
        break;

    //sonic commands
    case 'create_sonic':
        $sonic = param('sonic');
        $latitude = param('latitude');
        $longitude = param('longitude');
        $result = APISonic::CreateSonic($sonic,$latitude,$longitude);
        break;

    case 'get_sonics':
        $user = param('user',false);
        $count = param('count',false);
        $before_sonic = param('before_sonic',false);
        $after_sonic = param('after_sonic',false);
        $result = APISonic::GetSonics($user,$count,$before_sonic,$after_sonic);
        break;

//    case 'get_sonics':
//        $pageNumber = param('page_number');
//        $pageCount = param('page_count');
//        $result = APISonic::GetSonicFeed($pageNumber,$pageCount);
//        break;

    case 'like_sonic':
        $sonicId = param('sonic_id');
        $result = APISonic::LikeSonic($sonicId);
        break;

    case 'unlike_sonic':
        $sonicId = param('sonic_id');
        $result = APISonic::UnLikeSonic($sonicId);
        break;

    case 'delete_sonic':
        $sonicId = param('sonic_id');
        $result = APISonic::DeleteSonic($sonicId);
        break;

    //notification commands
    case 'get_last_notifications':
        $result = APINotifications::GetLastNotifications();
        break;

    case 'read_notification':
        $notificationId = param('notification_id');
        $result = APINotifications::ReadNotification($notificationId);
        break;

}

$result->cmd = $cmd;
$result->request_date = date('Y-m-d H:i:s T', time());
echo json_encode($result);