<?php

header("Access-Control-Allow-Origin:*");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD'] !== 'GET'):
	echo json_encode([
		'success' => 0, 
		'message' => 'Invalid Request Method.'
	]);
	exit;
endif;


require '../config/Database.php';
$database = new Database();
$conn = $database->connect();
$post_id = null;

if(isset($_GET['id'])){
	$post_id = filter_var($_GET['id'], FILTER_VALIDATE_INT, [
		'options' =>[
			'default' => 'all_posts',
			'min_range' => 1
		]
	]);
}


try{
	$sql = is_numeric($post_id) ? "SELECT * FROM posts WHERE id='$post_id'" : "SELECT * FROM posts";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	if($stmt->rowCount() > 0):
		$data = null;
		if (is_numeric($post_id)){
			$data = $stmt->fetch(PDO::FETCH_ASSOC);
		}
		else{
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		echo json_encode([
			'success' => 1,
			'message'=> $data
		]);
	else:
		echo json_encode([
			'success' => 0,
			'message'=> 'No Result Found'
		]);
	endif;
}

catch(PDOException $e){
	echo json_encode([
		'success'=> 0,
		'message' => $e->getMessage()
	]);
	exit;
}




