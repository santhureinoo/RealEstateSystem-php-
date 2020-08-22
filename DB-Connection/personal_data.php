<?php
        require_once(dirname(__FILE__).'/mysqli_class.php');
        require_once(dirname(__FILE__).'/mysqli_conf.php');
        require_once(dirname(__FILE__).'/../util/imageConvert.php');

        $db = new ViralDB();
        
        function getProfile($email){
            global $db;
            $sql = "SELECT * FROM user WHERE email='$email' and status !='Deleted'";
             $result = $db->query($sql);
             return $result;
        }

        function getPosts($ownerid){
            global $db;
            $sql = "SELECT  p.id,pt.name,p.description,p.initial_amount,p.status,p.created_at FROM post p INNER JOIN property pt ON p.propertyid=pt.id WHERE p.ownerid=$ownerid AND p.status!='Deleted'";
             $result = $db->query($sql);
             return isset($result)?$result:null;
        }

        function isPostDeletable($postid) {
            global $db;
            $sql = "SELECT COUNT(*) as activeProposal FROM proposal WHERE postid=$postid AND (status !='Over' OR status !='Rejected' OR status !='Waiting')";
            return $db->query($sql)[0]["activeProposal"];
        }
        function getProperties($ownerId){
            global $db;
            $sql = "SELECT * FROM property WHERE ownerid=$ownerId AND status !='Deleted'";
             $result = $db->query($sql);
             return isset($result)?$result:null;
        }
        function getProposals($ownerid){
            global $db;
            $sql = "SELECT p.id,p.postid,pt.description, o.id as ownerid, o.username as owner , p.status,pt.created_at FROM proposal p INNER JOIN post pt ON p.postid=pt.id INNER JOIN user o ON p.ownerid=o.id WHERE p.tenantid=$ownerid and p.status !='Deleted'";
            $result = $db->query($sql);
             return isset($result)?$result:null;
        }
        function getInbox($ownerid){
            global $db;
            $sql = "SELECT p.id,p.postid,pt.description,t.id as tenantid, t.username as tenant , p.status,pt.created_at FROM proposal p INNER JOIN post pt ON p.postid=pt.id INNER JOIN user t ON p.tenantid=t.id WHERE p.ownerid=$ownerid and p.status !='Deleted'";
            $result = $db->query($sql);
             return isset($result)?$result:null;
        }
        function getActiveProposalByPostId($id) {
            global $db;
            $sql = "SELECT COUNT(*) as activeProposal from proposal WHERE postid = $id AND status='active'";
            if ($db->query($sql)[0]["activeProposal"] > 0) {
               return true;
            }
            return false;
        }

        function getActivePostByPropertyID($id) {
            global $db;
            $sql = "SELECT COUNT(*) as activePost from post WHERE propertyid = $id AND status='active'";
            if ($db->query($sql)[0]["activePost"] > 0) {
               return true;
            }
            return false;
        }

        function setPropertyActiveById($id) {
            global $db;
            $sql = "UPDATE property SET status='Active' WHERE id=$id";
            if($db->update($sql) == 0) {
                return " This property can't be updated";
            }
        }
        function setPropertyWaitingById($id) {
            global $db;
            $sql = "UPDATE property SET status='Waiting' WHERE id=$id";
            if($db->update($sql) == 0) {
                return " This property can't be updated";
            }
        }
        function deletePostById($id) {
            global $db;
            $sql = "UPDATE post SET status = 'Deleted' where id=$id ";
            if(getActiveProposalByPostId($id)){
                return "You can't Delete this post because some proposals are currently active.";
            }
            else {
                if($db->update($sql) == 0 ) {
                    return "Your Deleted Id doesn't exist";
                }
                return null;
            }
        }
        function deleteUserById($id) {
            global $db;
            $sql = "UPDATE user SET status='Deleted' where id=$id  and status='Active'";
            if(getActiveProposalByPostId($id)){
                return "You can't Delete this post because some proposals are currently active.";
            }
            else {
                if($db->update($sql) == 0 ) {
                    return "Your Deleted Id doesn't exist";
                }
                return null;
            }
        }
        function deletePorpertyById($id) {
            global $db;
            $sql = "UPDATE property SET status='Deleted' where id=$id  and status='Active' OR status='Waiting'";
            if(getActiveProposalByPostId($id)){
                return "You can't Delete this property because some posts are currently active.";
            }
            else {
                if($db->update($sql) == 0 ) {
                    return "Your Deleted Id doesn't exist";
                }
                return null;
            }
        }
        function deleteProposalById($id) {
            global $db;
            $sql = "UPDATE proposal SET status ='Deleted' where id=$id and status !='Deleted'";
           echo $sql;
            if($db->update($sql) == 0 ) {
                return "Your proposal can't be deleted.";
            }
            $sql = "UPDATE proposal SET status='Waiting' WHERE status !='Rejected' AND status !='Deleted' AND postid = (SELECT postid from proposal WHERE id=$id LIMIT 1)";
            if($db->update($sql) == 0 ) {
                return "Other proposal can't be deleted.";
            }
            return null;
        }

        function rejectPropertyById($id) {
            global $db;
            $sql = "UPDATE property SET status='Rejected' where id=$id  and status!='Occupied'";
            if(getActiveProposalByPostId($id)){
                return "You can't Delete this property because some posts are currently active.";
            }
            else {
                if($db->update($sql) == 0 ) {
                    return "Your Deleted Id doesn't exist";
                }
                return null;
            }
        }
        function approveProposal($id,$postid) {
            global $db;
            $sql = "UPDATE proposal SET status='Approved' WHERE id = $id";
            if($db->update($sql) == 0 ) {
                return "This Proposal Can't be approved.";
            }
            else {
                $sql="UPDATE proposal SET status='Pending' WHERE postid=$postid and id != $id and status !='Rejected";
                $db->update($sql);
            }
        }

        function rejectApprovedProposal($id,$postid) {
            global $db;
            $sql = "UPDATE proposal SET status='Rejected' WHERE id = $id";
            if($db->update($sql) == 0 ) {
                return "This Proposal Can't be rejected.";
            }
            else {
                $sql="UPDATE proposal SET status='Waiting' WHERE postid=$postid and id != $id and status !='Rejected'";
                $db->update($sql);
            }
        }
        function rejectProposal($id) {
            global $db;
            $sql = "UPDATE proposal SET status='Rejected' WHERE id = $id";
            if($db->update($sql) == 0 ) {
                return "This Proposal Can't be rejected.";
            } 
        }
        function reapplyRejectedProposal($id, $postid) {
            global $db;
            $sql = "SELECT COUNT(*) as isApproved FROM proposal WHERE postid=$postid and id != $id and status ='Approved'";
            if($db->query($sql)[0]["isApproved"] > 0) {
                $sql = "UPDATE proposal SET status='Pending' WHERE id=$id";  
            }
            else {
                $sql = "UPDATE proposal SET status='Waiting' WHERE id=$id";
            }
            $db->update($sql);
        }

        function confirmApprovedProposal($id) {
            global $db;
            $sql = "UPDATE proposal SET status='Confirmed' WHERE id = $id";
            if($db->update($sql) == 0 ) {
                return "This Proposal Can't be Confirmed.";
            } 
            $sql = "UPDATE property SET status='InActive' WHERE id = $id";
            if($db->update($sql) == 0 ) {
                return "This Proposal Can't be Confirmed.";
            } 
        }

        function insertContract($proposalid,$amount,$from,$to,$members,$offline_evidence) {
            global $db;
            $table_name = "contract";
            $from_date = date('Y-m-d',strtotime($from));
            $to_date = date('Y-m-d',strtotime($to));
            $today = date('Y-m-d');
            $today = date('Y-m-d',strtotime($today));
            $status = "Waiting";
            $offline_evidence = readyToSave($offline_evidence);
            echo $from_date;
            echo $today;
            if($today >= $from_date && $today <=$to_date) {
                    $status = "Active";
            }
            $Array_sql = array("proposalid"=>$proposalid,"amount"=>$amount,"from_date"=>$from_date,"to_date"=>$to_date,"members"=>$members,"status"=>$status,"evidence"=>$offline_evidence,"created_at"=>date("Y-m-d"));
            $result = $db->insert($Array_sql,$table_name);
            return $result;
        }

        function getStatusesForIndex(){
            global $db;
            $totalStatus = array("properties"=>0, "contracts"=>0, "users"=>0);
            $sql = ' SELECT COUNT(*) as properties FROM property';
            $res = $db->query($sql);
            if(isset($res) && $res[0]["properties"] > 0){
                $totalStatus['properties'] = $res[0]["properties"];
            }
            $sql = ' SELECT COUNT(*) as contracts FROM contract';
            $res = $db->query($sql);
            if(isset($res) && $res[0]["contracts"] > 0){
                $totalStatus['contracts'] = $res[0]["contracts"];
            }
            $sql = ' SELECT COUNT(*) as users FROM user';
            $res = $db->query($sql);
            if(isset($res) && $res[0]["users"] > 0){
                $totalStatus['users'] = $res[0]["users"];
            }
            $sql = ' SELECT COUNT(*) as posts FROM post';
            $res = $db->query($sql);
            if(isset($res) && $res[0]["posts"] > 0){
                $totalStatus['posts'] = $res[0]["posts"];
            }

            return $totalStatus;
        }

        function finalConfirmed($id,$postid) {
            global $db;
            $sql = "UPDATE proposal SET status='Completed' WHERE id =$id";
            echo $db->update($sql);
         
              
                $sql = "UPDATE proposal SET status='Expired' WHERE id != $id and postid =$postid";
                $db->update($sql);
                $sql = "UPDATE post SET status='Expired' WHERE id =$postid";
                $db->update($sql);
                $sql = "UPDATE property SET status='Occupied' WHERE id =(SELECT propertyid FROM post WHERE id =$postid)";
                 $db->update($sql);
        }

        function approveUser($userid,$adminid) {
            global $db;
            $today = date("Y-m-d");
            $sql = "UPDATE  user SET status='Active',adminid=$adminid,approved_at=$today WHERE id = $userid";
            $db->update($sql);
        }

        function approvePost($postid) {
            global $db;
            $today = date("Y-m-d");
            $sql = "UPDATE  post SET status='Active'  WHERE id = $postid";
            $db->update($sql);
        }

        function checkPropertyDeletable($propertyid){
            global $db;
            $sql = "SELECT Count(*) as existingPost FROM post WHERE propertyid = $propertyid";
            $res = $db->query($sql);
            if(isset($res) && $res[0]["existingPost"] > 0)
            return false;
            else
            return true;
        }

        function getProfileStatus($userid) {
            global $db;
            $info = [[]];
            $info["rent"] = 0;
            $info["renting"]  = 0;
            $info["contract"] = 0;
            $info["post"] = 0;
            $sql = "SELECT COUNT(*) as tenantCount FROM proposal WHERE tenantid = $userid";
            $res = $db->query($sql);
            if(isset($res))
                $info["rent"] =$res[0]["tenantCount"];
            $sql = "SELECT COUNT(*) as ownerCount FROM proposal WHERE ownerid = $userid";
            $res = $db->query($sql);
            if(isset($res))
                $info["renting"] =$res[0]["ownerCount"];
            $sql = "SELECT COUNT(*) as contractCount FROM proposal WHERE tenantid = $userid OR ownerid = $userid";
            $res = $db->query($sql);
            if(isset($res))
                $info["contract"] =$res[0]["contractCount"];
            $sql = "SELECT COUNT(*) as postCount FROM post WHERE ownerid = $userid";
            $res = $db->query($sql);
            if(isset($res))
                $info["post"] =$res[0]["postCount"];
            
            return $info;
        }
?>