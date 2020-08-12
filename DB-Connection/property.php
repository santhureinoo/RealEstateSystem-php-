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

        function getData($sql) {
            global $conn;        
            $run_client=mysqli_query($conn, $sql);
            return mysqli_fetch_array($run_client);
        }

        function executeQuery($sql) {
            global $conn;
            if (mysqli_query($conn, $sql)) {
                return true;
              } else {
                return false;
              }
        }

        function addProperty($region,$township,$name,$address,$area,$city,$rooms,$type,$ownerid,$ownership,$description,$image) {
            global $db;
            $table_name = "property";
            $ownership = readyToSave($ownership);
            $image = readyToSave($image);
            $Array_sql = array("region"=>$region,"township"=>$township,"name"=>$name,"address"=>$address,"area"=>$area,"status"=>"Waiting","city"=>$city,"rooms"=>$rooms,"type"=>$type,"ownerid"=>$ownerid,"ownership"=>$ownership,"description"=>$description,"image"=>$image,"created_at"=>date("Y-m-d"));
            $result = $db->insert($Array_sql,$table_name);
            header('Location: myproperties.php');
        }

        function editProperty($region,$township,$propertyid,$status,$name = "No Name",$address ="No Address",$area=0,$city='',$rooms=0,$type="",$ownerid=0,$ownership="NULL",$description="NULL",$image="NULL") {
            global $db;
            $table_name = "property";
            $sql = "UPDATE property SET ";
            $putAndoperator = false;
            if(isset($name)) {
                if($putAndoperator) {
                    $sql = $sql. ", ";
                }
                else {
                    $putAndoperator = true;
                }
                $sql = $sql . "name='$name'";
            }
            if(isset($address)) {
                if($putAndoperator) {
                    $sql = $sql. ", ";
                }
                else {
                    $putAndoperator = true;
                }
                $sql = $sql . " address='$address'";
            }
            if(isset($city)) {
                if($putAndoperator) {
                    $sql = $sql. ", ";
                }
                else {
                    $putAndoperator = true;
                }
                $sql = $sql . " city='$city'";
            }
            if(isset($region)) {
                if($putAndoperator) {
                    $sql = $sql. ", ";
                }
                else {
                    $putAndoperator = true;
                }
                $sql = $sql . " region='$region'";
            }
            if(isset($township)) {
                if($putAndoperator) {
                    $sql = $sql. ", ";
                }
                else {
                    $putAndoperator = true;
                }
                $sql = $sql . " township='$township'";
            }
            if(isset($area)) {
                if($putAndoperator) {
                    $sql = $sql. ", ";
                }
                else {
                    $putAndoperator = true;
                }
                $sql = $sql . " area=$area";
            }
            if(isset($rooms)) {
                if($putAndoperator) {
                    $sql = $sql. ", ";
                }
                else {
                    $putAndoperator = true;
                }
                $sql = $sql . " rooms=$rooms";
            }
            if(isset($type)) {
                if($putAndoperator) {
                    $sql = $sql. ", ";
                }
                else {
                    $putAndoperator = true;
                }
                $sql = $sql . " type='$type'";
            }
            if(isset($ownerid)) {
                if($putAndoperator) {
                    $sql = $sql. ", ";
                }
                else {
                    $putAndoperator = true;
                }
                $sql = $sql . " ownerid=$ownerid";
            }
            if(isset($description) && !empty($description)){
                if($putAndoperator) {
                    $sql = $sql. ", ";
                }
                else {
                    $putAndoperator = true;
                }
                $sql = $sql . " description='$description'";
            }
            if(isset($ownership) && $ownership['size'] !== 0) {
                if($putAndoperator) {
                    $sql = $sql. ", ";
                }
                else {
                    $putAndoperator = true;
                }
                $ownership = readyToSave($ownership);
                $sql = $sql . " ownership='$ownership'";
            }
            if(isset($image) && $image['size'] !== 0) {
                if($putAndoperator) {
                    $sql = $sql. ", ";
                }
                else {
                    $putAndoperator = true;
                }
                $image = readyToSave($image);
                $sql = $sql . " image='$image'";
            }
            if(isset($status)) {
                if($putAndoperator) {
                    $sql = $sql. ", ";
                }
                else {
                    $putAndoperator = true;
                }
                if($status == "Rejected") {
                    $sql = $sql . ", status='Waiting'";
                }
            }
            $sql =  rtrim($sql, ', ');
            $sql = $sql . " WHERE id = $propertyid";
            return $db->update($sql) > 0 ? true: false;
            // $Array_sql = array("name"=>$name,"address"=>$address,"area"=>$area,"status"=>"Waiting","city"=>$city,"rooms"=>$rooms,"type"=>$type,"ownerid"=>$ownerid,"ownership"=>$ownership,"description"=>$description,"image"=>$image);
            // $result = $db->insert($Array_sql,$table_name);
        }

        function addProposal($postid,$owner,$tenant) {
            global $db;
            $table_name = "proposal";
            $sql = "SELECT COUNT(*) as activeProposal FROM proposal WHERE postid=".$postid." AND status = 'Approved'";
            $status = $db->query($sql)[0]["activeProposal"] > 0 ? "Pending":"Waiting";
            $Array_sql = array("postid"=>$postid,"ownerid"=>$owner,"tenantid"=>$tenant,"status"=>$status,"created_at"=>date('Y-m-d'));
            $result = $db->insert($Array_sql,$table_name);
            return $result;
        }

        function getPostById($postid) {
            global $db;
            $sql= "SELECT * FROM post p WHERE p.id=$postid";
            $result = $db->query($sql);
            return $result[0];
        }

        function getProposalById($id) {
            global $db;
            $sql= "SELECT * FROM proposal p INNER JOIN post pt On p.postid = pt.id INNER JOIN property ppt ON pt.propertyid=ppt.id WHERE p.id=$id";
            $result = $db->query($sql);
            return $result[0];
        }

        function getContractById($id) {
            global $db;
            $sql= "SELECT * FROM contract c WHERE c.proposalid=$id";
            $result = $db->query($sql);
            return $result[0];
        }

        function getUserById($userid) {
            global $db;
            $sql = "SELECT * FROM user WHERE id=$userid";
            $result = $db->query($sql);
            return $result[0];
        }

        function getAllPostFeature($id){
            global $db;
            $sql = "SELECT pf.amount,f.name FROM post_features pf INNER JOIN feature f ON pf.featureid = f.id WHERE postid = $id";
            return $db->query($sql);
        }

        function getAllPostImage($id){
            global $db;
            $sql = "SELECT image FROM post_images WHERE postid = $id";
            return $db->query($sql);
        }

        function showAllPostFeature($id) {
            global $db;
            $sql = "SELECT pf.amount,f.name FROM post_features pf INNER JOIN feature f ON pf.featureid = f.id WHERE postid = $id";
            $result = $db->query($sql);
            if(isset($result) && $result[0]['amount'] > 0){
                    $count = 3;
                    foreach($result as $feature) { 
                        if($count%3 == 0) {
                            $isclosed =false;
                            echo '<div class="col-md-4 col-sm-6">';
                        }
                        echo '<p><i class="fa fa-check-circle-o"></i>'. $feature["amount"]."-".$feature["name"].'</p></div>';
                    }
            }
            else {
                echo '<div class="col">No Details For This Property</div>';
            }
        }

        function getCities()
        {
            global $conn;
            $get_client="SELECT DISTINCT city from property WHERE status !='Deleted'";
            $run_client=mysqli_query($conn, $get_client);

            while($row_client = mysqli_fetch_array($run_client)){

                $city=$row_client['city'];
                echo "<option value='$city'>$city</option>";
            }
        }

      
        function getTypes()
        {
            global $conn;
            $get_client="SELECT DISTINCT type from property WHERE status != 'Deleted'";
            $run_client=mysqli_query($conn, $get_client);

            while($row_client = mysqli_fetch_array($run_client)){

                $type=$row_client['type'];
                echo "<option value='$type'>$type</option>";
            }
        }

        function getAllProperties() {
            global $db;
            $sql = "SELECT * FROM Property WHERE status!='Deleted'";
            return   $db->query($sql);
        }

        function getBriefProperties() {
            global $db;
            $sql = "SELECT  p.name ,p.description ,u.username,p.created_at, p.status, p.id FROM property p INNER JOIN user u ON p.ownerid=u.id WHERE p.status !='Deleted'";
            $result=[];
            foreach($db->query($sql) as $row) {
                $id = $row["id"];
                $sql = "SELECT Count(*) as existing_post FROM  post WHERE post.propertyid=$id";
                $existing_post = $db->query($sql)[0]["existing_post"];
                if($row["status"] == "Active") {
                    $buttons = ["buttons"=>"<form action='' method='post'><button name='viewProperty' type='submit' value='$id' class='btn btn-primary'>VIew</button>"]; 
                    if($existing_post > 0) {
                            $buttons["buttons"] .= "</form>";
                    }
                    else {
                        $buttons["buttons"] .= "<button name='waitingProperty' type='submit' value='$id' class='btn btn-primary ml-2'>Return Waiting</button><button name='rejectProperty' type='submit' value='$id' class='btn btn-danger ml-2'>Reject</button></form>";
                    }
                    
                }
                else if($row["status"] == "Waiting") {
                    $buttons = ["buttons"=>"<form action='' method='post'><button name='confirmProperty' type='submit' value='$id' class='btn btn-success'>Confirm</button><button name='viewProperty' type='submit' value='$id' class='btn btn-primary ml-2'>VIew</button><button name='rejectProperty' type='submit' value='$id' class='btn btn-danger ml-2'>Reject</button></form>"]; 
                }
                else if($row["status"] == "Occupied") {
                    $buttons = ["buttons"=>"<form action='' method='post'><button name='viewProperty' type='submit' value='$id' class='btn btn-primary'>VIew</button></form>"]; 
                }
                $row = array_merge(array_slice($row,0,5),$buttons,array_slice($row,5));
                array_push($result,$row);
            }
            return $result;
        }

        function getBriefContracts() { 
            global $db;
            $sql = "SELECT o.username as owner,t.username as tenant,c.from_date,c.to_date,c.created_at,c.status,c.id FROM Contract c INNER JOIN Proposal p ON c.proposalid=p.id INNER JOIN user o ON p.ownerid=o.id INNER JOIN user t ON p.tenantid=t.id WHERE c.status !='Deleted'";

            $result=[];
            $resultSet = $db->query($sql) ;
            if($resultSet != null) {
                foreach($db->query($sql) as $row) {
                    $id = $row["id"];
                    if($row["status"] =="Active") {
                        $buttons = ["buttons"=>"<form action='' method='post'><button name='viewContract' type='submit' value='$id' class='btn btn-primary'>VIew</button><button name='rejectContract' type='submit' value='$id' class='btn btn-danger ml-2'>Reject</button></form>"]; 
                    }
                    else if($row["status"] =="Reject") {
                        $buttons = ["buttons"=>"<form action='' method='post'><button name='viewContract' type='submit' value='$id' class='btn btn-primary'>View</button><button name='acceptContract' type='submit' value='$id' class='btn btn-danger ml-2'>Re-Accept</button></form>"]; 
                    }
                    else if($row["status"] =="Over") {
                        $buttons = ["buttons"=>"<form action='' method='post'><button name='viewContract' type='submit' value='$id' class='btn btn-primary'>View</button></form>"]; 
                    }
                    $row = array_merge(array_slice($row,0,6),$buttons,array_slice($row,6));
                    array_push($result,$row);
                }

            }
            return $result;
        }

        function getBriefPosts() { 
            global $db;
            $sql = "SELECT p.description, p.initial_amount, u.username,p.created_at,p.status,p.id FROM post p INNER JOIN user u ON p.ownerid = u.id WHERE p.status !='Deleted'";
           $result=[];
           $resultSet = $db->query($sql) ;
           if($resultSet != null) {
            foreach($resultSet as $row) {
                $id = $row["id"];
                if($row["status"] =="Active") {
                    if(getActiveProposalByPostId($id) > 0) {
                        $buttons = ["buttons"=>"<form action='' method='post'><button name='viewPost' type='submit' value='$id' class='btn btn-primary'>VIew</button></form>"]; 
                    }
                    else {
                        $buttons = ["buttons"=>"<form action='' method='post'><button name='viewPost' type='submit' value='$id' class='btn btn-primary'>VIew</button><button name='deletePost' type='submit' value='$id' class='btn btn-danger ml-2'>Delete</button></form>"]; 
                    }

                    
                }
                else if($row["status"] == "Waiting") {
                    $buttons = ["buttons"=>"<form action='' method='post'><button name='approvePost' type='submit' value='$id' class='btn btn-success'>Confirm</button><button name='viewContract' type='submit' value='$id' class='btn btn-primary ml-2'>VIew</button><button name='deletePost' type='submit' value='$id' class='btn btn-danger ml-2'>Delete</button></form>"]; 
                }
                else  {
                    $buttons = ["buttons"=>"<form action='' method='post'><button name='viewContract' type='submit' value='$id' class='btn btn-primary'>VIew</button><button name='deletePost' type='submit' value='$id' class='btn btn-danger ml-2'>Delete</button></form>"]; 
                }
                $row = array_merge(array_slice($row,0,5),$buttons,array_slice($row,5));
                array_push($result,$row);
            }
           }
           
            return $result;
        }

        function profile($id,$row){
            $profile_data = getUserById($id);
            $encodedImage = base64_encode($profile_data["photo"]);
            $readonly =true;
            ob_start();
                require "../profile_detail.php";
            $content = ob_get_clean();  
                return ' <div class="modal " id="profile_'. $row["id"].'">
                <div class="modal-dialog modal-lg mw-100 w-75">
                  <div class="modal-content">
              
                    
                    <div class="modal-header">
                      <h4 class="modal-title">Profile</h4>
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
              
                    <!-- Modal body -->
                    <div class="modal-body">
                    '.
                   $content
                  .'
                    </div>
              
                    <!-- Modal footer -->
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    </div>
              
                  </div>
                </div>
              </div>
          </div>';
          }

        function getBriefUsers() { 
            global $db;
            $sql = "SELECT u.username as UserName,u.email as Email ,u.created_at,a.username,u.approved_at,u.status,u.id FROM user u LEFT JOIN admin a ON u.adminid=a.id WHERE u.status !='Deleted'";
            $result=[];
            $resultSet = $db->query($sql) ;
            if($resultSet != null) {
                foreach($resultSet as $row) {
                        $id = $row["id"];                    
                           
                    if($row["status"] =="Active") {
                        $buttons = ["buttons"=>"<form action='' method='post'><button name='viewUser' type='button' data-toggle='modal' data-target='#profile_".$id."' value='$id' class='btn btn-primary'>VIew</button><button name='deleteUser' type='submit' value='$id' class='btn btn-danger ml-2'>Delete</button></form>".profile($id,$row)]; 
                    }
                    else if($row["status"] == "Waiting") {
                        $buttons = ["buttons"=>"<form action='' method='post'><button name='approveUser' type='submit' value='$id' class='btn btn-success'>Approve</button><button name='viewUser' type='button' data-toggle='modal' data-target='#profile_".$id."' class='btn btn-primary ml-2'>VIew</button><button name='deleteUser' type='submit' value='$id' class='btn btn-danger ml-2'>Delete</button></form>".profile($id,$row)]; 
                    }
                    $row = array_merge(array_slice($row,0,6),$buttons,array_slice($row,6));
                    array_push($result,$row);
                }
            }
            // print_r($result);
            return $result;
        }

        function getAllCurrentFeature() {
            global $db;
            $sql = "SELECT * FROM feature";
            return $db->query($sql);
        }
        
        function getPropertiesByUser($selectedPostId,$userid){
            global $db;
            $sql  = "SELECT id,name FROM property WHERE ownerid=$userid AND (id=$selectedPostId OR status !='Active') AND status !='Deleted'";
            return $db->query($sql);
        }

        function getPropertyByID($id) {
            global $db;
            $sql = "SELECT * FROM property WHERE id = $id";
            return $db->query($sql)[0];
        }

        function getCountPosts() {
            global $db;
            $sql = "SELECT COUNT(*) as postCount FROM post WHERE status = 'Active'";
            return $db->query($sql)[0]["postCount"];
        }

        function getPagination() {
            $allPostCount = getCountPosts();
            $pageCount = 1;
                while($allPostCount >6) {
                    $pageCount ++;
                    $allPostCount = $allPostCount/6;
                }
                return $pageCount;
        }

        function getPartialProperties($offset,$amount,$region = null, $township = null, $propertyName = null, $city = null,$type = null,$min = 0,$max = 0){
            global $db;
            $extraFilter=" ";
            if(!empty($city) || !empty($region) || !empty($township) || !empty($type) || !empty($region) || !empty($square_feet) || !empty($propertyName)){
                if($city === 'other') {
                    $city = "Property.city != 'Mandalay' AND Property.city != 'Rangoon' AND Property.city !='Nay Pyi Taw'";
                }
                else {
                    $city = "Property.city ='".$city."'";
                }
                $extraFilter= " AND (".$city." OR Property.region='$region' OR Property.township='$township' OR Property.name ='$propertyName' OR Property.type='$type') ";
            }
            if(!empty($min) && !empty($max)) {
                $extraFilter= $extraFilter . "AND (Post.initial_amount BETWEEN $min AND $max) ";
            }
            $sql="SELECT Post.id as id,Property.area,Property.city,Property.township,Property.region,Property.address,Property.name,Property.image,Property.ownership, User.username,Post.initial_amount,Post.created_at FROM Post INNER JOIN Property ON Post.propertyid=Property.id INNER JOIN User ON Property.ownerid=User.id WHERE Post.status ='Active'  $extraFilter  LIMIT $amount OFFSET $offset ";
            $resultSet = $db->query($sql);
            if(isset($resultSet)){
                        foreach($resultSet as $res) {
                            $id = $res['id'];
                            $encodedImage = base64_encode($res["image"]);
                            $subSql = "SELECT * FROM `post_features` as pf INNER JOIN feature as f ON pf.featureid=f.id where pf.postid=$id LIMIT 4";
                            $subResultSet = $db->query($subSql);
                            if(isset($subResultSet)) {
                                    $first_feature = isset($subResultSet[0])?' <p><i class="fa fa-check-circle-o"></i>'.$subResultSet[0]["amount"] .' ' .$subResultSet[0]["name"].'</p>':'';
                                    $second_feature  = isset($subResultSet[1])?' <p><i class="fa fa-check-circle-o"></i>'.$subResultSet[1]["amount"] .' ' .$subResultSet[1]["name"].'</p>':'';
                                    $third_feature = isset($subResultSet[2])?'<p><i class="fa fa-check-circle-o"></i>'.$subResultSet[2]["amount"] .' ' .$subResultSet[2]["name"].'</p>':'';
                            }
                            if($subResultSet[0]['name'] === ''){
                                echo '
                                <div class="col-lg-4 col-md-6">
                                <!-- feature -->
                                <div class="feature-item">
                                    <div class="feature-pic set-bg" style="background-image:url(data:image/png;base64,'.$encodedImage.');">     
                                        <img class="img-fluid" src="data:image/png;base64,'.$encodedImage.'">
                                    </div>
                                    <div class="feature-text">
                                        <div class="text-center feature-title">
                                            <h5>'.$res['name'].'</h5>
                                            <p><i class="fa fa-map-marker"></i> '.$res["address"].', '.$res["city"].', '.$res['township'].', '.$res['region'].'</p>
                                        </div>
                                        <div class="room-info-warp">
                                            <div class="room-info">
                                                <div class="rf-left">
                                                    <p><i class="fa fa-check-circle-o"></i>'.$res["area"].'  Square foot</p>
                                                    
                                                </div>
                                                <div class="rf-right">
                                                   
                                                 
                                                </div>	
                                            </div>
                                            <div class="room-info">
                                                <div class="rf-left">
                                                    <p><i class="fa fa-user"></i> '.$res['username'].'</p>
                                                </div>
                                                <div class="rf-right">
                                                    <p><i class="fa fa-clock-o"></i>'.$res['created_at'].'</p>
                                                </div>	
                                            </div>
                                        </div>
                                        <a href="property-detail.php?id='.$res['id'].'" class="room-price">'.$res['initial_amount'].' Kyats/Month'.'</a>
                                    </div>
                                </div>
                            </div>
                        ';
                            }
                            else {
                                echo '
                                <div class="col-lg-4 col-md-6">
                                <!-- feature -->
                                <div class="feature-item">
                                    <div class="feature-pic set-bg" style="background-image:url(data:image/png;base64,'.$encodedImage.');">     
                                        <img class="img-fluid" src="data:image/png;base64,'.$encodedImage.'">
                                    </div>
                                    <div class="feature-text">
                                        <div class="text-center feature-title">
                                            <h5>'.$res['name'].'</h5>
                                            <p><i class="fa fa-map-marker"></i> '.$res["address"].', '.$res["city"].', '.$res['township'].', '.$res['region'].'</p>
                                        </div>
                                        <div class="room-info-warp">
                                            <div class="room-info">
                                                <div class="rf-left">
                                                    <p><i class="fa fa-check-circle-o"></i>'.$res["area"].'  Square foot</p>
                                                    '.$first_feature.'
                                                </div>
                                                <div class="rf-right">
                                                    '.$second_feature.$third_feature.'
                                                 
                                                </div>	
                                            </div>
                                            <div class="room-info">
                                                <div class="rf-left">
                                                    <p><i class="fa fa-user"></i> '.$res['username'].'</p>
                                                </div>
                                                <div class="rf-right">
                                                    <p><i class="fa fa-clock-o"></i>'.$res['created_at'].'</p>
                                                </div>	
                                            </div>
                                        </div>
                                        <a href="property-detail.php?id='.$res['id'].'" class="room-price">'.$res['initial_amount'].' Kyats/Month'.'</a>
                                    </div>
                                </div>
                            </div>
                        ';
                            }
                        
                }
            }
       
        }  
        function checkStatus() {
            checkContractStatus();
            checkProposalStatus();
            checkPropertyStatus();
        }

        function rejectContract($id) {
            global $db;
            $sql ="Update contract c SET c.status='Over' WHERE c.id=$id";
            $db->update($sql);

            $sql ="SELECT proposalid FROM contract WHERE id=$id";
            $proposalid = $db->query($sql)[0]["proposalid"];
            $sql = "SELECT propertyid FROM post WHERE id =(SELECT postid FROM proposal WHERE id=$proposalid LIMIT 1)";
            $propertyid =$db->query($sql)[0]["propertyid"];

            $sql ="Update property p SET p.status='Active' WHERE id=$propertyid";
            $db->update($sql);
            $sql = "Update proposal p  SET p.status='Over' WHERE id=$proposalid";
            $db->update($sql);
        }

        function checkContractStatus() {
            global $db;
            $today = date("Y-m-d");
            $sql = "Update contract c SET c.status='Active' WHERE '$today' >= from_date && '$today' <= to_date";
           $db->update($sql);
        }
        function checkPropertyStatus() {
            global $db;
            $sql = "Update property p INNER JOIN  post pt On p.id = pt.propertyid INNER JOIN proposal pl On pt.id = pl.postid SET p.status ='Active' WHERE pl.status ='Over'";
            $db->update($sql);
        }
        function checkProposalStatus() { 
            global $db;
            $today = date('Y-m-d');
            $sql = "Update proposal p INNER JOIN contract c On p.id = c.proposalid SET p.status='Over', c.status='Over' WHERE '$today' > c.to_date";
            $db->update($sql);
        }

        function getPostImages($postid) { 
            global $db;
            $sql = "SELECT * FROM post_images WHERE postid=$postid";
            return $db->query($sql);
        }
?>