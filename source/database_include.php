<?php
function doDB() {
	global $mysqli;

	//connect to server and select database; you may need it
	$mysqli = mysqli_connect("ns1121.websiteservername.com", "yoyo_noodle", "RvpXul_iss72", "yoyo_noodleDB");

	//if connection fails, stop script execution
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
}
?>