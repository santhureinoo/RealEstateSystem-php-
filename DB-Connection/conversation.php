<?php
    require_once(dirname(__FILE__).'/mysqli_class.php');
    require_once(dirname(__FILE__).'/mysqli_conf.php');

    $db = new ViralDB();

    function getConversationsByID($id) {
        global $db;
        $sql = "SELECT c.id, u1.id as u1_id, u1.username as u1_username,u1.photo as u1_photo,u2.photo as u2_photo, u2.id as u2_id, u2.username as u2_username,c.status from conversation c INNER JOIN user u1 ON c.user1=u1.id INNER JOIN user u2 ON c.user2=u2.id WHERE (c.user1=$id OR c.user2=$id) AND c.status ='Active'";
        return $db->query($sql);
    }

    function getChatUserById($userid) {
        global $db;
        $sql = "SELECT * FROM user WHERE id=$userid";
        echo $sql;
        $result = $db->query($sql);
        return $result[0];
    }

    function checkExistingConversation($sender,$receiver) {
        global $db;
        $sql = "SELECT COUNT(*) as existingConversation FROM conversation WHERE (user1 = $sender OR user2 = $sender) AND (user1= $receiver OR user2 = $receiver)";
        return $db->query($sql)[0]["existingConversation"];
    }

    function addConversationByID($user1,$user2) {
        global $db;
        $table_name = "conversation";
        $Array_sql = array("user1"=>$user1,"user2"=>$user2,"status"=>"Active","created_at"=>date("Y-m-d"));
        $result = $db->insert($Array_sql,$table_name);
    }

    function addChat($senderid,$receiverid,$message) {
        global $db;
        $table_name="chat";
        $Array_sql = array("senderid"=>$senderid,"receiverid"=>$receiverid,"message"=>$message,"status"=>"Active","created_at"=>date("Y-m-d"));
        $result = $db->insert($Array_sql,$table_name);
    }

    function getChatsByConversationID($id) {
        global $db;
        $sql = "SELECT * FROM chat WHERE conversationid=$id";
        return $db->query($sql);
    }
?>