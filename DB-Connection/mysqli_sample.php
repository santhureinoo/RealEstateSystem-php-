<?php

include(dirname(__FILE__).'/mysqli_class.php');
include(dirname(__FILE__).'/mysqli_conf.php');

$db = new ViralDB();

//GET ALL ROW (EXAMPLE)
$sql = "SELECT * FROM table_name";
$result = $db->query($sql);

//UPDATE (EXAMPLE)
$con = $db->con();
$table_name = "table_name";
$name = "John";
$email = "john@test.com";
$id = 22;
$sql = 'UPDATE '.$table_name.' SET name = \''.mysqli_real_escape_string($con, $name).'\', email = \''.mysqli_real_escape_string($con, $email).'\' WHERE id = '.$id.'';
$result = $db->update($sql);

/***********************
* INSERT row (EXAMPLE) *
* INSERT by Array      *
***********************/
$table_name = "table_name";
$name = "Robert";
$email = "Robert@test.com";
$Array_sql = array("name"=>$name,"email"=>$email);
$result = $db->insert($Array_sql,$table_name);


/***********************
* DELETE row (EXAMPLE) *
***********************/
$id = 1;
$sql = 'DELETE FROM table_name WHERE id = '.$id.'';
$result = $db->delete($sql);
$db->close();


?>