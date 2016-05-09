<?php

require 'vendor/autoload.php';

$app = new \Slim\Slim();
$app->get('/msgs', 'getMsg');
$app->post('/add_msg', 'addMsg');


$app->run();

function getMsg() {
	$sql = "select * FROM messages ORDER BY id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$data = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($data);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function addMsg() {
	$request = Slim\Slim::getInstance()->request();
	$data = json_decode($request->getBody());
	$sql = "INSERT INTO messages (message) VALUES (:message)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("message", $data->message);
		$stmt->execute();
		$data->id = $db->lastInsertId();
		$db = null;
		echo json_encode($data); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getConnection() {
	$dbhost="127.0.0.1";
	$dbuser="root";
	$dbpass="";
	$dbname="socialbase";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

?>