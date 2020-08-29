<?php

class ViralDB
{
	public  $con;
	public 	$mysqli_Key;
	public 	$mysqli_Value;
	public  $sql;
	public  $id;
	public 	$table;
	private $row_count;

	function __construct()
	{
		if(HOST == "" OR USERNAME == "" OR DBNAME == ""){
			die("Please Enter DATABASE details in mysqli_conf.php file");
		}
		$this->con = new mysqli(HOST,USERNAME,PASSWORD,DBNAME);
		if($this->con->connect_errno > 0){
			die('Unable to connect to database [' . $this->con->connect_error . ']');
		}
	}

	public function query($sql){
		$result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));

		/* DEBUG TRUE OR FALSE BY CONFIG FILE*/
		if(DEBUG == true AND php_sapi_name() == "cli"){
			echo "\n";
			echo ":::::::::::::::::::::::::\n";
			echo ":: mysqli_query Result ::\n";
			echo ":::::::::::::::::::::::::\n";
			echo "\n";
			print_r($result);
		}

		if($result->num_rows < 1){

			/* DEBUG TRUE OR FALSE BY CONFIG FILE*/
			if(DEBUG == true AND php_sapi_name() == "cli"){
				echo "\n";
				echo "::::::::::::::::::::::::::::::::\n";
				echo ":: No Row Found by This Query ::\n";
				echo "::::::::::::::::::::::::::::::::\n";
				echo "\n";
			}
			return null;
		}

		$result = mysqli_fetch_all ($result,MYSQLI_ASSOC);

		/* DEBUG TRUE OR FALSE BY CONFIG FILE*/
		if(DEBUG == true AND php_sapi_name() == "cli"){
			echo "\n";
			echo "::::::::::::::::::::::::::::::::\n";
			echo ":: mysqli_fetch_object Result ::\n";
			echo "::::::::::::::::::::::::::::::::\n";
			echo "\n";
			print_r($result);
		}

		return $result;
	}


	public function delete($sql){
		$result = mysqli_query($this->con,$sql);
		if(DEBUG == true AND php_sapi_name() == "cli"){
			echo $sql."\n";
		}
		return $this->con->affected_rows;
	}


	public function update($sql){
		$result = mysqli_query($this->con,$sql);
		if( mysqli_query($this->con,$sql)){
			//success
			return $this->con->affected_rows;
	   
	   } else {
		  
		   return mysqli_error($this->con);
	   }
		// if(DEBUG == true AND php_sapi_name() == "cli"){
		// 	echo $sql."\n";
		// }
		
	}


	public function insert($Array_sql,$tbl){
		$con = $this->con;
		$mysqli_Key = "";
		$mysqli_Value = "";
		foreach($Array_sql as $key => $sql_value)
		{
		  $mysqli_Value .= '\''.mysqli_real_escape_string($con, $sql_value).'\'';
		  $mysqli_Value .= ",";

		  $mysqli_Key .= ''.$key.'';
		  $mysqli_Key .= ",";
		}

		$mysqli_Value = rtrim($mysqli_Value, ",");
		$mysqli_Key = rtrim($mysqli_Key, ",");

		$sql = 'INSERT INTO '.$tbl.' ('.$mysqli_Key.') VALUES('.$mysqli_Value.')';
		if(DEBUG == true AND php_sapi_name() == "cli"){
			echo $sql."\n";
		}
		$result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
		if($result) {
			$last_id = mysqli_insert_id($con);
			return $last_id;
		}
		return null;
	}

	/*old
		public function insert($sql){
			$result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));
			if(DEBUG == true AND php_sapi_name() == "cli"){
				echo $sql."\n";
			}
			return $result;
		}

	*/

	public function find($id,$table){
		$sql = "SELECT * FROM $table WHERE id = $id";
		$result = mysqli_query($this->con,$sql) or die(mysqli_error($this->con));

		/* DEBUG TRUE OR FALSE BY CONFIG FILE*/
		if(DEBUG == true AND php_sapi_name() == "cli"){
			echo "\n";
			echo ":::::::::::::::::::::::::\n";
			echo ":: mysqli_query Result ::\n";
			echo ":::::::::::::::::::::::::\n";
			echo "\n";
			print_r($result);
		}

		if($result->num_rows < 1){

			/* DEBUG TRUE OR FALSE BY CONFIG FILE*/
			if(DEBUG == true AND php_sapi_name() == "cli"){
				echo "\n";
				echo "::::::::::::::::::::::::::::::::\n";
				echo ":: No Row Found by This Query ::\n";
				echo "::::::::::::::::::::::::::::::::\n";
				echo "\n";
			}
			return  '';
		}

		$result = mysqli_fetch_object($result);

		/* DEBUG TRUE OR FALSE BY CONFIG FILE*/
		if(DEBUG == true AND php_sapi_name() == "cli"){
			echo "\n";
			echo "::::::::::::::::::::::::::::::::\n";
			echo ":: mysqli_fetch_object Result ::\n";
			echo "::::::::::::::::::::::::::::::::\n";
			echo "\n";
			print_r($result);
		}

		return $result;
	}

	public function close(){
		return $this->con->close();
	}

	public function con(){
		return $this->con;
	}

	function readyToSave($image) {
		return addslashes(file_get_contents($image['tmp_name'])); //SQL Injection defence!
	}

}
?>