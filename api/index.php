<?php
error_reporting(NULL);

require_once('../apiClasses/APINotification.php');
require_once('../apiClasses/APISonickle.php');
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
$result->cmd = $cmd;
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
        $result = APISonickle::CreateSonickle($sonic,$latitude,$longitude);
        break;

    case 'get_my_sonics':
        $pageNumber = param('page_number');
        $pageCount = param('page_count');
        $result = APISonickle::GetMySonickles($pageNumber,$pageCount);
        break;

    case 'get_sonic_feed':
        $pageNumber = param('page_number');
        $pageCount = param('page_count');
        $result = APISonickle::GetSonickleFeed($pageNumber,$pageCount);
        break;

    case 'like_sonic':
        $sonicId = param('sonic_id');
        $result = APISonickle::LikeSonickle($sonicId);
        break;

    case 'unlike_sonic':
        $sonicId = param('sonic_id');
        $result = APISonickle::UnLikeSonickle($sonicId);
        break;

    case 'delete_sonic':
        $sonicId = param('sonic_id');
        $result = APISonickle::DeleteSonickle($sonicId);
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

echo json_encode($result);