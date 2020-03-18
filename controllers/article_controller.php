<?php
function get_articles($conn) {
    $sql = "SELECT * FROM logs";
    $result = $conn->query($sql);
    $response_data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $response_data[] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'start' => $row['start'],
                'goal' => $row['goal'],
                'comment' => $row['comment'],
                'lat' => $row['lat'],
                'lng' => $row['lng'],
            ];
        }
    }
    return json_encode($response_data);
}

function get_article_detail($conn, $article_id)
{
    $stmt = $conn->prepare("SELECT id, user_id, title, body FROM articles WHERE id = ?");
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $user_id, $title, $body); 
    $stmt->fetch();
    $stmt->close();
    if (empty($id)) {
        http_response_code(404);
        return;
    }
    return json_encode([
        'id' => $id,
        'user_id' => $user_id,
        'title' => $title,
        'body' => $body
    ]);
}

function create_article($conn,$request)
{
    
    $title = $request['title'];
    $start = $request['start'];
    $goal = $request['goal'];
    $comment = $request['comment'];
    $lat = $request['lat'];
    $lng = $request['lng'];
    $stmt = $conn->prepare("INSERT INTO logs (title, start, goal, comment,lat,lng) VALUES (?, ?, ?, ?,?,?)");
    $stmt->bind_param("ssssss",$title, $start, $goal, $comment,$lat,$lng);
    $stmt->execute();
    $stmt->close();
    http_response_code(201);
    return;
}