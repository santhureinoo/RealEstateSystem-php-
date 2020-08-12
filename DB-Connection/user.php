<?php
        require_once(dirname(__FILE__).'/mysqli_class.php');
        require_once(dirname(__FILE__).'/mysqli_conf.php');
        require_once(dirname(__FILE__).'/../util/imageConvert.php');

        $db = new ViralDB();
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "estatedb";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        
         function updateUser($photo,$name,$email,$password,$ph_no,$nrc,$address,$city,$job,$income,$relationship,$religion,$family_members){
            global $db;
            $userid= $_SESSION['userid'];
            $photoPart = "";
            if(isset($photo) && $photo["name"] !== "") {
                $photo = readyToSave($photo);
               $photoPart = "photo='$photo',";
            }
            
            $sql = "UPDATE user SET password='$password',".$photoPart."username= '$name',email='$email',ph_no='$ph_no',nrc='$nrc',address='$address',city='$city',job='$job',income=$income,relationship=$relationship,religion='$religion',family_members='$family_members' WHERE id=$userid";
            
        }

           

        ?>