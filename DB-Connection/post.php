<?php
        // require_once('./util/imageConvert.php');
        // include('../util/imageConvert.php');
        if(!isset($db)) {
                $db = new ViralDB();
        }
        function addPost($propertyid,$amount,$description,$ownerid,$images,$featureNames,$featureAmounts){
                global $db;
                $table_name = "post";

               //Save Post
               $Array_sql = array("propertyid" => $propertyid,"initial_amount"=>$amount,"description"=>$description,"ownerid"=>$ownerid,"status"=>"Waiting","created_at"=>date('Y-m-d'));
                $result_id = $db->insert($Array_sql,$table_name);
                
                //Save Post Features
                for($i=0; $i<count($featureNames);$i++){
                        $feature_id = findFeatureByName($featureNames[$i]);
                        if($feature_id <= 0 ) {
                             $feature_id =  addFeature($featureNames[$i]);
                        }
                        $table_name="post_features";
                        $Array_sql  = array("featureid"=>$feature_id,"postid"=>$result_id,"amount"=>$featureAmounts[$i]);
                        $db->insert($Array_sql,$table_name);
                       
                }

                //Save Post Images
                foreach($images as $image) {
                        $table_name="post_images";
                        echo $image;
                        echo "____________";
                        $Array_sql = array("image" => file_get_contents($image), "postid"=>$result_id);
                        $db->insert($Array_sql,$table_name);
                }
                // header('Location: myposts.php');
        }

        function editPost($postid,$propertyid,$amount,$description,$ownerid,$images,$featureNames,$featureAmounts){
                global $db;
                $table_name = "post";

               //Save Post
               $Array_sql = array("propertyid" => $propertyid,"initial_amount"=>$amount,"description"=>$description,"ownerid"=>$ownerid,"status"=>"Waiting","created_at"=>date('Y-m-d'));
                $result_id = $db->insert($Array_sql,$table_name);
                
                //Save Post Features
                for($i=0; $i<count($featureNames);$i++){
                        $feature_id = findFeatureByName($featureNames[$i]);
                        if($feature_id <= 0 ) {
                             $feature_id =  addFeature($featureNames[$i]);
                        }
                        $table_name="post_features";
                        $Array_sql  = array("featureid"=>$feature_id,"postid"=>$result_id,"amount"=>$featureAmounts[$i]);
                        $db->insert($Array_sql,$table_name);
                       
                }

                //Save Post Images
                foreach($images as $image) {
                        $table_name="post_images";
                        echo $image;
                        echo "____________";
                        $Array_sql = array("image" => file_get_contents($image), "postid"=>$result_id);
                        $db->insert($Array_sql,$table_name);
                }
                // header('Location: myposts.php');
        }
?>
    