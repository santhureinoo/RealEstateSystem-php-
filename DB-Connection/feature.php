<?php
        if(!isset($db)) {
                $db = new ViralDB();
        }

        function findFeatureByName($name) {
            global $db;
            $sql = "SELECT id FROM feature where name='$name'";
            if($db->query($sql)[0]["id"] > 0) {
                return $db->query($sql)[0]["id"];
            }
            else {
                return 0;
            }
            
        }

        function addFeature($name) {
            global $db;
            $table_name = "feature";
            $Array_sql=array("name"=>$name);
            $inserted_id = $db->insert($Array_sql,$table_name);
            return $inserted_id;
        }
?>