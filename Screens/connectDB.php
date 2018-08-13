<?php
	$host="127.0.0.1";
	$port=3306;
	$socket="";
	$user="root";
	$password="myiamlabsql";
	$dbname="user_study";

	$GLOBALS['conn'] = new mysqli($host, $user, $password, $dbname, $port, $socket)
		or die ('Could not connect to the database server' . mysqli_connect_error());

	//$con->close();
	date_default_timezone_set("America/New_York");
	
	
	function savePageLog($pid, $page_name){
		$date = date("Y-m-d H:i:s");
		$time = round(microtime(true) * 1000);
	
		$sql = "INSERT INTO page_log (`participant_id`, `page`, `time`, `date`) "
		."VALUES (".$pid.", '".$page_name."','".$time."','".$date."')";
	
		//echo $sql;
	
		execSQL($sql);
	}
	
	function execSQL($sql){
		if ($GLOBALS['conn']->query($sql) === TRUE) {
			//echo "New record created successfully<br>";
		} else {
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
	}
	
	function getSelect($sql){
		$result = getConn()->query($sql);
		if ($result->num_rows > 0) {
			return $result->fetch_assoc();
		}
		return null;
	}
	
	function getConn(){
		return $GLOBALS['conn'];
	}
?>