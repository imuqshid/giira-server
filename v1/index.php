<?php
 
error_reporting(-1);
ini_set('display_errors', 'On');
 
require_once '../include/db_handler.php';
require '.././libs/Slim/Slim.php';
 
\Slim\Slim::registerAutoloader();
 
$app = new \Slim\Slim();
 
// User login
$app->post('/user/login', function() use ($app) {
    // check for required params
    verifyRequiredParams(array('name', 'email'));
 
    // reading post params
    $name = $app->request->post('name');
    $email = $app->request->post('email');
 
    // validating email address
    validateEmail($email);
 
    $db = new DbHandler();
    $response = $db->createUser($name, $email);
 
    // echo json response
    echoRespnse(200, $response);
});
 
 
/* * *
 * Updating user
 *  we use this url to update user's gcm registration id
 */
$app->put('/user/:id', function($id) use ($app) {
    global $app;
 
    verifyRequiredParams(array('gcm_registration_id'));
 
    $gcm_registration_id = $app->request->put('gcm_registration_id');
 
    $db = new DbHandler();
    $response = $db->updateGcmID($id, $gcm_registration_id);
 
    echoRespnse(200, $response);
});
 
/* * *
 * fetching all chat rooms
 */
$app->get('/giira_chatrooms', function() {
    $response = array();
    $db = new DbHandler();
 
    // fetching all user tasks
    $result = $db->getAllChatrooms();
 
    $response["error"] = false;
    $response["giira_chatrooms"] = array();
 
    // pushing single chat room into array
    while ($chat_room = $result->fetch_assoc()) {
        $tmp = array();
        $tmp["chat_room_id"] = $chat_room["chat_room_id"];
        $tmp["name"] = $chat_room["name"];
        $tmp["created_at"] = $chat_room["created_at"];
        array_push($response["giira_chatrooms"], $tmp);
    }
 
    echoRespnse(200, $response);
});
 
/**
 * Messaging in a chat room
 * Will send push notification using Topic Messaging
 *  */
$app->post('/giira_chatrooms/:id/message', function($chat_room_id) {
    global $app;
    $db = new DbHandler();
 
    verifyRequiredParams(array('id', 'message'));
 
    $id = $app->request->post('id');
    $message = $app->request->post('message');
 
    $response = $db->addMessage($id, $chat_room_id, $message);
 
    if ($response['error'] == false) {
        require_once __DIR__ . '/../libs/gcm/gcm.php';
        require_once __DIR__ . '/../libs/gcm/push.php';
        $gcm = new GCM();
        $push = new Push();
 
        // get the user using userid
        $user = $db->getUser($id);
 
        $data = array();
        $data['user'] = $user;
        $data['message'] = $response['message'];
        $data['chat_room_id'] = $chat_room_id;
 
        $push->setTitle("Google Cloud Messaging");
        $push->setIsBackground(FALSE);
        $push->setFlag(PUSH_FLAG_CHATROOM);
        $push->setData($data);
         
        // echo json_encode($push->getPush());exit;
 
        // sending push message to a topic
        $gcm->sendToTopic('topic_' . $chat_room_id, $push->getPush());
 
        $response['user'] = $user;
        $response['error'] = false;
    }
 
    echoRespnse(200, $response);
});
 
 
/**
 * Sending push notification to a single user
 * We use user's gcm registration id to send the message
 * * */
$app->post('/giira_users/:id/message', function($to_id) {
    global $app;
    $db = new DbHandler();
 
    verifyRequiredParams(array('message'));
 
    $from_id = $app->request->post('id');
    $message = $app->request->post('message');
 
    $response = $db->addMessage($from_id, $to_id, $message);
 
    if ($response['error'] == false) {
        require_once __DIR__ . '/../libs/gcm/gcm.php';
        require_once __DIR__ . '/../libs/gcm/push.php';
        $gcm = new GCM();
        $push = new Push();
 
        $user = $db->getUser($to_id);
 
        $data = array();
        $data['user'] = $user;
        $data['message'] = $response['message'];
        $data['image'] = '';
 
        $push->setTitle("Google Cloud Messaging");
        $push->setIsBackground(FALSE);
        $push->setFlag(PUSH_FLAG_USER);
        $push->setData($data);
 
        // sending push message to single user
        $gcm->send($user['gcm_registration_id'], $push->getPush());
 
        $response['user'] = $user;
        $response['error'] = false;
    }
 
    echoRespnse(200, $response);
});
 
 
/**
 * Sending push notification to multiple giira_users
 * We use gcm registration ids to send notification message
 * At max you can send message to 1000 recipients
 * * */
$app->post('/giira_users/message', function() use ($app) {
 
    $response = array();
    verifyRequiredParams(array('id', 'to', 'message'));
 
    require_once __DIR__ . '/../libs/gcm/gcm.php';
    require_once __DIR__ . '/../libs/gcm/push.php';
 
    $db = new DbHandler();
 
    $id = $app->request->post('id');
    $to_ids = array_filter(explode(',', $app->request->post('to')));
    $message = $app->request->post('message');
 
    $user = $db->getUser($id);
    $giira_users = $db->getgiira_users($to_ids);
 
    $registration_ids = array();
 
    // preparing gcm registration ids array
    foreach ($giira_users as $u) {
        array_push($registration_ids, $u['gcm_registration_id']);
    }
 
    // insert messages in db
    // send push to multiple users
    $gcm = new GCM();
    $push = new Push();
 
    // creating tmp message, skipping database insertion
    $msg = array();
    $msg['message'] = $message;
    $msg['message_id'] = '';
    $msg['chat_room_id'] = '';
    $msg['created_at'] = date('Y-m-d G:i:s');
 
    $data = array();
    $data['user'] = $user;
    $data['message'] = $msg;
    $data['image'] = '';
 
    $push->setTitle("Google Cloud Messaging");
    $push->setIsBackground(FALSE);
    $push->setFlag(PUSH_FLAG_USER);
    $push->setData($data);
 
    // sending push message to multiple users
    $gcm->sendMultiple($registration_ids, $push->getPush());
 
    $response['error'] = false;
 
    echoRespnse(200, $response);
});
 
$app->post('/giira_users/send_to_all', function() use ($app) {
 
    $response = array();
    verifyRequiredParams(array('id', 'message'));
 
    require_once __DIR__ . '/../libs/gcm/gcm.php';
    require_once __DIR__ . '/../libs/gcm/push.php';
 
    $db = new DbHandler();
 
    $id = $app->request->post('id');
    $message = $app->request->post('message');
 
    require_once __DIR__ . '/../libs/gcm/gcm.php';
    require_once __DIR__ . '/../libs/gcm/push.php';
    $gcm = new GCM();
    $push = new Push();
 
    // get the user using userid
    $user = $db->getUser($id);
     
    // creating tmp message, skipping database insertion
    $msg = array();
    $msg['message'] = $message;
    $msg['message_id'] = '';
    $msg['chat_room_id'] = '';
    $msg['created_at'] = date('Y-m-d G:i:s');
 
    $data = array();
    $data['user'] = $user;
    $data['message'] = $msg;
    $data['image'] = 'http://www.androidhive.info/wp-content/uploads/2016/01/Air-1.png';
 
    $push->setTitle("Google Cloud Messaging");
    $push->setIsBackground(FALSE);
    $push->setFlag(PUSH_FLAG_USER);
    $push->setData($data);
 
    // sending message to topic `global`
    // On the device every user should subscribe to `global` topic
    $gcm->sendToTopic('global', $push->getPush());
 
    $response['user'] = $user;
    $response['error'] = false;
 
    echoRespnse(200, $response);
});
 
/**
 * Fetching single chat room including all the chat messages
 *  */
$app->get('/giira_chatrooms/:id', function($chat_room_id) {
    global $app;
    $db = new DbHandler();
 
    $result = $db->getChatRoom($chat_room_id);
 
    $response["error"] = false;
    $response["giira_messages"] = array();
    $response['chat_room'] = array();
 
    $i = 0;
    // looping through result and preparing tasks array
    while ($chat_room = $result->fetch_assoc()) {
        // adding chat room node
        if ($i == 0) {
            $tmp = array();
            $tmp["chat_room_id"] = $chat_room["chat_room_id"];
            $tmp["name"] = $chat_room["name"];
            $tmp["created_at"] = $chat_room["chat_room_created_at"];
            $response['chat_room'] = $tmp;
        }
 
        if ($chat_room['id'] != NULL) {
            // message node
            $cmt = array();
            $cmt["message"] = $chat_room["message"];
            $cmt["message_id"] = $chat_room["message_id"];
            $cmt["created_at"] = $chat_room["created_at"];
 
            // user node
            $user = array();
            $user['id'] = $chat_room['id'];
            $user['username'] = $chat_room['username'];
            $cmt['user'] = $user;
 
            array_push($response["giira_messages"], $cmt);
        }
    }
 
    echoRespnse(200, $response);
});
 
/**
 * Verifying required params posted or not
 */
function verifyRequiredParams($required_fields) {
    $error = false;
    $error_fields = "";
    $request_params = array();
    $request_params = $_REQUEST;
    // Handling PUT request params
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $app = \Slim\Slim::getInstance();
        parse_str($app->request()->getBody(), $request_params);
    }
    foreach ($required_fields as $field) {
        if (!isset($request_params[$field]) || strlen(trim($request_params[$field])) <= 0) {
            $error = true;
            $error_fields .= $field . ', ';
        }
    }
 
    if ($error) {
        // Required field(s) are missing or empty
        // echo error json and stop the app
        $response = array();
        $app = \Slim\Slim::getInstance();
        $response["error"] = true;
        $response["message"] = 'Required field(s) ' . substr($error_fields, 0, -2) . ' is missing or empty';
        echoRespnse(400, $response);
        $app->stop();
    }
}
 
/**
 * Validating email address
 */
function validateEmail($email) {
    $app = \Slim\Slim::getInstance();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response["error"] = true;
        $response["message"] = 'Email address is not valid';
        echoRespnse(400, $response);
        $app->stop();
    }
}
 
function IsNullOrEmptyString($str) {
    return (!isset($str) || trim($str) === '');
}
 
/**
 * Echoing json response to client
 * @param String $status_code Http response code
 * @param Int $response Json response
 */
function echoRespnse($status_code, $response) {
    $app = \Slim\Slim::getInstance();
    // Http response code
    $app->status($status_code);
 
    // setting response content type to json
    $app->contentType('application/json');
 
    echo json_encode($response);
}
 
$app->run();
?>