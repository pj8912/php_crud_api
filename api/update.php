<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");
header("Access-Control-Allow-Headers: Content-Type, Access-Contorl-Allow-Headers, Authorization, X-Requested-With");

if($_SERVER['REQUEST_METHOD'] !== 'POST'):
	echo json_encode([
		'success' => 0,
		'message' => 'Invalid Request Method. Use GET'
	]);
	exit;
endif;

require '../config/Database.php';
$database = new Database();
$conn = $database->connect();

$data = json_decode(file_get_contents("php://input"));

if(!isset($data->id)){
	echo json_encode(['success'=>0,
		'message'=>'Please provide post id'
	]);
	exit;
}
try{
	$fetch_post = "SELECT * FROM posts WHERE id=: post_id";
	$fetch_stmt = $conn->prepare($fetch_post);

	$fetch_stmt->bindParam(':post_id', $data->id, PDO::PARAM_INT);
	
	$fetch_stmt->execute();

	if($fetch_stmt->rowCount > 0):
		$row = $fetch_stmt->fetch(PDO::FETCH_ASSOC);
		$post_title = isset($data->title) ? $data->title: $row['title'];
		$post_body = isset($data->body)? $data->body : $row['body'];
		$post_author = isset($data->author) ? $data->author : $row['author'];

		$sql = "UPDATE posts SET title = :title, body = :body, author = :author WHERE id= :id";
		$update = $conn->prepare($sql);
		$update->bindParam(':title', htmlspecialchars(strip_tags($post_title)), PDO::PARAM_STR);
		$update->bindParam(':body', htmlspecialchars(strip_tags($post_body)), PDO::PARAM_STR );
		$update->bindParam(':author', htmlspecialchars(strip_tags($post_author)), PDO::PARAM_STR);
		$update->bindParam(':id', $data->id, PDO::PARAM_INT);	

		if($update->execute()){
			echo json_encode([
				'success' => 1,
				'message' => 'Post updated successfully'
			]);
			exit;
		}

		echo json_encode([
			'success' => 0,
			'message'=> 'Post Not Updated!!'
		]);
		exit;
	else:
		echo json_encode([
                        'success' => 0,
                        'message'=> 'Invalid Id'
		]);
		exit;
	endif;
}

catch(PDOException $e){
	echo json_encode([
		'success' => 0,
		'message' => $e->getMessage()
	]);
	exit;
}

