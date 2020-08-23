<?php
  require_once(dirname(__FILE__).'/../util/imageConvert.php');
   $db = new ViralDB();
    /**
     * This is for Login User.
     */
    function login($email,$password) {
        global $db;
        $sql = "SELECT id,username,email FROM user where email='$email' AND password='$password' AND status='Active'";
       $result = $db->query($sql);;
       if($result != "" && count($result) > 0) {
         session_start();
        $_SESSION["current_user"] =$result[0]["username"];
        $_SESSION['email']= $result[0]['email'];
        $_SESSION['userid'] = $result[0]['id'];
        header('Location: index.php');
       }
       else {
         return "Username or Password Incorrect, Please Try Again!";
       }
    }
    function adminLogin($password,$email) {
      global $db;
        $sql = "SELECT id,username,email FROM admin where email='$email' AND password='$password'";
       $result = $db->query($sql);
       if($result != null && count($result) > 0) {
         session_start();
        $_SESSION["current_admin"] =$result[0]["username"];
        $_SESSION['admin_email']= $result[0]['email'];
        $_SESSION['admin_userid'] = $result[0]['id'];
        header('Location: index.php');
       }
       else {
         return "Username or Password Incorrect, Please Try Again!";
       }
    }
    function logout() {
      session_destroy();
      header('Location: login.php');
    }

    function addUser($profile,$username,$email,$password,$address,$city,$phone,$nrc,$nrc_front,$nrc_back,$relation,$religion,$job,$income,$members){
        global $db;
        $table_name = "user";
        $profile = readyToSave($profile);
        $nrc_back = readyToSave($nrc_back);
        $nrc_front = readyToSave($nrc_front);
        $Array_sql = array("photo"=>$profile,"username"=>$username,"email"=>$email,"password"=>$password,"address"=>$address,"city"=>$city,"ph_no"=>$phone,"nrc"=>$nrc,"nrc_back_photo"=>$nrc_back,"nrc_front_photo"=>$nrc_front,"relationship"=>$relation,"religion"=>$religion,"job"=>$job,"income"=>$income,"family_members"=>$members,"status"=>"Waiting","created_at"=>date('Y-m-d'));
        $result = $db->insert($Array_sql,$table_name);
    }

    function addAdmin($username,$password,$email)
    {
      echo "FUCJK";
      global $db;
      $table_name = "admin";
        $Array_sql = array("username"=>$username,"password"=>$password,"email"=>$email);
        $result = $db->insert($Array_sql,$table_name);
    }
?>