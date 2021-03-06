<?php
        require_once(dirname(__FILE__).'/mysqli_class.php');
        require_once(dirname(__FILE__).'/mysqli_conf.php');

        $db = new ViralDB();
        function getLatestPost($sessionUser) {
            global $db;
            $today = date('Y-m-d');
            $sql="SELECT p.id,p.description,pt.name,pt.address,pt.city,p.initial_amount,pt.image,p.postType FROM post p INNER JOIN property pt ON p.propertyid = pt.id WHERE pt.ownerid!=$sessionUser and p.status ='Active' and p.created_at >= $today LIMIT 6";

            return $db->query($sql);

        }

        function getPostsFromCity() { 
            global $db;
            if(!isset($_SESSION["userid"])){
                return null;
            }
            $userid = $_SESSION["userid"];
            $sql="SELECT city from user WHERE user.id = $userid";
            $result = $db->query($sql);
            if(isset($result)) {
                getPostsByCityName($result[0]["city"]);
            }
            return null;
        }

        function getNumbersFromCities($sessionUser) {
            global $db;
            $sql = "SELECT city,COUNT(city) as number FROM PROPERTY WHERE PROPERTY.ownerid != $sessionUser group by city ";
            $result = $db->query($sql);
            $cityPropertyList = [[]];
            $cityPropertyList["other"] = 0;
            if(isset($result)) {
                foreach($result as $res){
                    if($res["city"] !== 'Mandalay' && $res["city"] !== 'Rangoon' && $res["city"] !== "Nay Pyi Taw") {
                        $cityPropertyList["other"] = $cityPropertyList["other"] + $res["number"];
                    }
                    else {
                        $cityPropertyList[$res["city"]] = $res["number"];
                    }
                    
                }
            }
           return $cityPropertyList;
        }

        function getPostsByCityName($cityName){
            global $db;

            $sql="SELECT Post.id as id,Property.area,Property.city,Property.address,Property.name,Property.image,Property.ownership, User.username,Post.description, Post.initial_amount,Post.created_at FROM Post INNER JOIN Property ON Post.propertyid=Property.id INNER JOIN User ON Property.ownerid=User.id WHERE Post.status ='Active' AND Property.city= '$cityName' LIMIT 6 ";
            
            $resultSet = $db->query($sql);
            
            if(isset($resultSet)){
                    echo '
                        <section class="feature-section spad">
                        <div class="container">
                            <div class="section-title text-center">
                                <h3>Properties In Your City</h3>
                                <p>Browse houses and flats to rent in your area</p>
                            </div>
                            <div class="row">';
                        foreach($resultSet as $res) {
                            $id = $res['id'];
                            $encodedImage = base64_encode($res["image"]);
                            $subSql = "SELECT * FROM `post_features` as pf INNER JOIN feature as f ON pf.featureid=f.id where pf.postid=$id LIMIT 4";
                            $subResultSet = $db->query($subSql);
                            $saleOrRent = $res["postType"] === "Sale"? "<div style='position:absolute;' class='sale-notic'>FOR SALE</div>  ": "<div style='position:absolute;' class='rent-notic'>FOR RENT</div>  ";
                            $month =  $res["postType"] !== "Sale"?'/Month':'';
                            if(isset($subResultSet)) {
                                    $first_feature = isset($subResultSet[0])?' <p><i class="fa fa-check-circle-o"></i>'.$subResultSet[0]["amount"] .' ' .$subResultSet[0]["name"].'</p>':'';
                                    $second_feature  = isset($subResultSet[1])?' <p><i class="fa fa-check-circle-o"></i>'.$subResultSet[1]["amount"] .' ' .$subResultSet[1]["name"].'</p>':'';
                                    $third_feature = isset($subResultSet[2])?'<p><i class="fa fa-check-circle-o"></i>'.$subResultSet[2]["amount"] .' ' .$subResultSet[2]["name"].'</p>':'';
                            }
                            echo '
                                
                                <div class="col-lg-4 col-md-6">
                                <!-- feature -->
                                <div class="feature-item">
                                    <div class="feature-pic set-bg" style="background-image:url(data:image/png;base64,'.$encodedImage.');">     
                                        '.$saleOrRent.' 
                                        <img class="img-fluid" src="data:image/png;base64,'.$encodedImage.'">
                                    </div>
                                    <div class="feature-text">
                                        <div class="text-center feature-title">
                                            <h5>'.$res['description'].'</h5>
                                            <p><i class="fa fa-map-marker"></i> '.$res["address"].', '.$res["city"].'</p>
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
                                        <a href="property-detail.php?id='.$res['id'].'" class="room-price">'.$res['initial_amount'].' Kyats'.$month.'</a>
                                    </div>
                                </div>
                            </div>
                           
                        ';
                }
                echo ' </div>
                </div>
                </section>';
            }
            else{
                return null;
            }
        }   
?>