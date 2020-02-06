<?php
require './env.php';
require './vendor/autoload.php';
// require './controllers/user_controller.php';
require './controllers/article_controller.php';
header("Content-Type: application/json; charset=UTF-8");
// クライアントからのリクエストデータ(json)をデコードして、phpのオブジェクトに格納
$request_json = file_get_contents('php://input');
$request_data = json_decode($request_json, TRUE);
$title = $request_data['title'];
$start = $request_data['start'];
$goal = $request_data['goal'];
$comment = $request_data['comment'];
$lat = $request_data['lat'];
$lng = $request_data['lng'];


$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];
// mysqlのコネクション
$conn = new mysqli(DB_SERVER_NAME, USERNAME, PASSWORD, DB_NAME);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// echo json_encode(["method" => $request_method]);
    switch ($request_method) {

      
        case 'GET':
            // echo json_encode(["message" => "requested"]);
            echo get_articles($conn);
            break;
        case 'PUT':
            echo json_encode(["message" => "put requested"]);
            echo get_articles($conn);
            break;
        case 'POST':
            echo var_dump($request_data);
            echo create_article($conn, $request_data);
            $insert_sql = 'INSERT INTO `logs`(title,start,goal,comment,lat,lng) VALUES ("'.$title.'", "'.$start.'", "'.$goal.'", "'.$comment.'", "'.$lat.'", "'.$lng.'")';
            echo var_dump($insert_sql);
            $conn->query($insert_sql);
            $conn->close();
            break;
    }
