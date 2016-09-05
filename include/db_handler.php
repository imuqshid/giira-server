
<?php
 
/**
 * Class to handle all db operations
 * This class will have CRUD methods for database tables
 *
 * @author Ravi Tamada
 */
class DbHandler {
 
    private $conn;
 
    function __construct() {
        require_once dirname(__FILE__) . '/db_connect.php';
        // opening db connection
        $db = new DbConnect();
        $this->conn = $db->connect();
    }
 
    
 
    // updating user GCM registration ID
    public function updateGcmID($id, $gcm_registration_id) {
        $response = array();
        $stmt = $this->conn->prepare("UPDATE giira_users SET gcm_registration_id = ? WHERE id = ?");
        $stmt->bind_param("si", $gcm_registration_id, $id);
 
        if ($stmt->execute()) {
            // User successfully updated
            $response["error"] = false;
            $response["message"] = 'GCM registration ID updated successfully';
        } else {
            // Failed to update user
            $response["error"] = true;
            $response["message"] = "Failed to update GCM registration ID";
            $stmt->error;
        }
        $stmt->close();
 
        return $response;
    }
 
    // fetching single user by id
    public function getUser($id) {
        $stmt = $this->conn->prepare("SELECT id, name, email, gcm_registration_id, created_at FROM giira_users WHERE id = ?");
        $stmt->bind_param("s", $id);
        if ($stmt->execute()) {
            // $user = $stmt->get_result()->fetch_assoc();
            $stmt->bind_result($userid, $name, $email, $gcm_registration_id, $created_at);
            $stmt->fetch();
            $user = array();
            $user["id"] = $id;
            $user["name"] = $name;
            $user["email"] = $email;
            $user["gcm_registration_id"] = $gcm_registration_id;
            $user["created_at"] = $created_at;
            $stmt->close();
            return $user;
        } else {
            return NULL;
        }
    }
 
    // fetching multiple users by ids
    public function getgiira_users($ids) {
 
        $giira_users = array();
        if (sizeof($ids) > 0) {
            $query = "SELECT id, name, email, gcm_registration_id, created_at FROM giira_users WHERE id IN (";
 
            foreach ($ids as $id) {
                $query .= $id . ',';
            }
 
            $query = substr($query, 0, strlen($query) - 1);
            $query .= ')';
 
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
 
            while ($user = $result->fetch_assoc()) {
                $tmp = array();
                $tmp["id"] = $user['id'];
                $tmp["name"] = $user['name'];
                $tmp["email"] = $user['email'];
                $tmp["gcm_registration_id"] = $user['gcm_registration_id'];
                $tmp["created_at"] = $user['created_at'];
                array_push($giira_users, $tmp);
            }
        }
 
        return $giira_users;
    }
 
    // messaging in a chat room / to persional message
    public function addMessage($id, $chat_room_id, $message) {
        $response = array();
 
        $stmt = $this->conn->prepare("INSERT INTO giira_messages (chat_room_id, id, message) values(?, ?, ?)");
        $stmt->bind_param("iis", $chat_room_id, $id, $message);
 
        $result = $stmt->execute();
 
        if ($result) {
            $response['error'] = false;
 
            // get the message
            $message_id = $this->conn->insert_id;
            $stmt = $this->conn->prepare("SELECT message_id, id, chat_room_id, message, created_at FROM giira_messages WHERE message_id = ?");
            $stmt->bind_param("i", $message_id);
            if ($stmt->execute()) {
                $stmt->bind_result($message_id, $id, $chat_room_id, $message, $created_at);
                $stmt->fetch();
                $tmp = array();
                $tmp['message_id'] = $message_id;
                $tmp['chat_room_id'] = $chat_room_id;
                $tmp['message'] = $message;
                $tmp['created_at'] = $created_at;
                $response['message'] = $tmp;
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'Failed send message';
        }
 
        return $response;
    }
 
    // fetching all chat rooms
    public function getAllChatrooms() {
        $stmt = $this->conn->prepare("SELECT * FROM giira_chatrooms");
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
    }
 
    // fetching single chat room by id
    function getChatRoom($chat_room_id) {
        $stmt = $this->conn->prepare("SELECT cr.chat_room_id, cr.name, cr.created_at as chat_room_created_at, u.name as username, c.* FROM giira_chatrooms cr LEFT JOIN giira_messages c ON c.chat_room_id = cr.chat_room_id LEFT JOIN giira_users u ON u._id = c.id WHERE cr.chat_room_id = ?");
        $stmt->bind_param("i", $chat_room_id);
        $stmt->execute();
        $tasks = $stmt->get_result();
        $stmt->close();
        return $tasks;
    }
 
    /**
     * Checking for duplicate user by email address
     * @param String $email email to check in db
     * @return boolean
     */
    private function isUserExists($email) {
        $stmt = $this->conn->prepare("SELECT id from giira_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $num_rows = $stmt->num_rows;
        $stmt->close();
        return $num_rows > 0;
    }
 
    /**
     * Fetching user by email
     * @param String $email User email id
     */
    public function getUserByEmail($email) {
        $stmt = $this->conn->prepare("SELECT id, name, email, created_at FROM giira_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            // $user = $stmt->get_result()->fetch_assoc();
            $stmt->bind_result($id, $name, $email, $created_at);
            $stmt->fetch();
            $user = array();
            $user["id"] = $id;
            $user["name"] = $name;
            $user["email"] = $email;
            $user["created_at"] = $created_at;
            $stmt->close();
            return $user;
        } else {
            return NULL;
        }
    }
 
}
 
?>