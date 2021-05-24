<?php

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	    $ip_address = $_SERVER['REMOTE_ADDR'];
	    $user_agent = $_SERVER['HTTP_USER_AGENT'];
	    $date = date('Y-m-d H:i:s');
	    $page_url = $_SERVER['HTTP_REFERER'];
	    

	    $servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "task";

		// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		if (!$conn) {
			die("Connection failed: " . mysqli_connect_error());
		}

		// check and create table if not exist
		$query = "SELECT id FROM stats LIMIT 1";
		$result = mysqli_query($conn, $query);


		if(empty($result)) {
			
            $query = "CREATE TABLE stats (
                      id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                      ip_address VARCHAR(255) NOT NULL,
                      user_agent VARCHAR(255) NOT NULL,
                      view_date DATETIME NOT NULL,
                      page_url VARCHAR(255) NOT NULL,
                      views_count INT(11) NOT NULL
                      )";
            $result = mysqli_query($conn, $query);
            if(!$result) {
            	die("Create table error.");
            }
		}

		$sql = "SELECT * FROM stats WHERE ip_address = '$ip_address' AND user_agent = '$user_agent' AND page_url = '$page_url' LIMIT 1";
		$result = mysqli_query($conn, $sql);

		if (mysqli_num_rows($result) > 0) {
			//update exist data
			$row = mysqli_fetch_assoc($result);
			$count = (int) $row['views_count'] + 1;
			$sql = "UPDATE stats SET view_date = '$date', views_count = $count WHERE id = " . $row['id'];
			if ($conn->query($sql) != TRUE) {
				echo json_encode(['success' => 0]);
				exit;
			}
		} else {
			// insert new row
			$sql = "INSERT INTO stats (ip_address, user_agent, view_date, page_url, views_count)
					VALUES ('$ip_address', '$user_agent', '$date', '$page_url', 1)";

			if ($conn->query($sql) != TRUE) {
				echo json_encode(['success' => 0]);
				exit;
			}
		}

		$conn->close();
		echo json_encode(['success' => 1]);
		exit;

	}
	
	
	if(basename($_SERVER['HTTP_REFERER']) == 'index1.html') {
		echo file_get_contents('https://upload.wikimedia.org/wikipedia/commons/thumb/7/77/Google_Images_2015_logo.svg/1200px-Google_Images_2015_logo.svg.png');
		exit;
	} else if(basename($_SERVER['HTTP_REFERER']) == 'index2.html') {
		echo file_get_contents('https://www.kasper.by/uploads/articles_img/1455700604123961.jpg');
		exit;
	}

?>