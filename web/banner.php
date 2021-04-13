<?php 
$log_file = './log.txt';

// Try connect to our database
try {
	$host     = 'mysql';
	$database = 'infuse_media';
	$user     = 'root';
	$pass     = 'pass';

    $dbh = new PDO('mysql:host=' . $host . ';dbname=' . $database, $user, $pass);

	// Set options
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	$message = "DataBase Error: " . $e->getMessage();

	if (file_exists($log_file)) {
	  $fh = fopen($log_file, 'a');
	} else {
	  $fh = fopen($log_file, 'w');
	}

	// Add error message in log file
	fwrite($fh, $message . PHP_EOL);
	fclose($fh);
} catch (Exception $e) {
	$message = "General Error: " .  $e->getMessage();

	if (file_exists($log_file)) {
	  $fh = fopen($log_file, 'a');
	} else {
	  $fh = fopen($log_file, 'w');
	}

	// Add error message in log file
	fwrite($fh, $message . PHP_EOL);
	fclose($fh);
}

// Insert visitor information to database
if(isset($dbh)){
	try {
		// Get Visitor Data
		$data = [
			'ip_address'  => $_SERVER['REMOTE_ADDR'],
			'user_agent'  => $_SERVER['HTTP_USER_AGENT'],
			'page_url'    => $_SERVER['HTTP_REFERER'],
		];

		$stmt = $dbh->prepare('SELECT * FROM customer WHERE ip_address=:ip_address AND user_agent=:user_agent AND page_url=:page_url');
		$stmt->execute($data);
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if(!$row) {
			// Insert Visitor Data
			$sql = "INSERT INTO customer (ip_address, user_agent, page_url) VALUES (:ip_address, :user_agent, :page_url)";
			$stmt= $dbh->prepare($sql);
			$stmt->execute($data);
		} else {
			// Update Visitor Data
			$sql = 'UPDATE customer SET views_count=views_count+1 WHERE id=:id';
			$stmt= $dbh->prepare($sql);
			$stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
			$stmt->execute();
		}
	} catch (PDOException $e) {
		$message = "DataBase Error: The visitor could not be added." . PHP_EOL . $e->getMessage();

		if (file_exists($log_file)) {
		  $fh = fopen($log_file, 'a');
		} else {
		  $fh = fopen($log_file, 'w');
		}

		// Add error message in log file
		fwrite($fh, $message . PHP_EOL);
		fclose($fh);
	} catch (Exception $e) {
		$message = "General Error: The visitor could not be added." . PHP_EOL . $e->getMessage();

		if (file_exists($log_file)) {
		  $fh = fopen($log_file, 'a');
		} else {
		  $fh = fopen($log_file, 'w');
		}

		// Add error message in log file
		fwrite($fh, $message . PHP_EOL);
		fclose($fh);
	}
}

// Show Image to visitor
$src_image = './image/reflection.jpg';

// Produce proper Image
header("Content-type: image/jpeg");

echo file_get_contents($src_image);
?>
