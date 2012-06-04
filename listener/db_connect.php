<?php

function runQuery($sql) {

		@ $db = new mysqli('localhost', 'track_user', 'vBn5Ql', 'track');

		if (mysqli_connect_errno())
		{
			echo "Error: Could not connect to database. Please try again";
			exit;
		}

		$result = $db->query($sql);

		if (!$result)
			die ("DB_ERROR: " . $db->error . "\n" . $sql);

		$db->close();

		return $result;


	}
	
	function runQueryFLSMS($sql) {

		@ $db = new mysqli('localhost', 'root', '', 'flsms');

		if (mysqli_connect_errno())
		{
			echo "Error: Could not connect to database. Please try again";
			exit;
		}

		$result = $db->query($sql);

		if (!$result)
			die ("DB_ERROR: " . $db->error . "\n" . $sql);

		$db->close();

		return $result;


	}

?>