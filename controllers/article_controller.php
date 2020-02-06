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
// function create_article($conn, $request, $login_user) {
//     // ログインユーザー情報をチェック
//     if (empty($login_user) || empty($login_user['id'])) {
//         http_response_code(400);
//         return;
//     }
//     $title = $request['title'];
//     $body = $request['body'];
//     // バリデーション
//     if (empty($title) || empty($body)) {
//         return json_encode([
//             'message' => '記事のタイトルと本文は、必須項目です'
//         ]);
//     }
//     // "プリペアードステートメント"でググる
//     $stmt = $conn->prepare("INSERT INTO articles (user_id, title, body) VALUES (?, ?, ?)");
//     $stmt->bind_param("iss", $login_user['id'], $title, $body);
//     $stmt->execute();
//     $stmt->close();
//     http_response_code(201);
//     return;
// }
/**
 * 一つの記事の詳細を取得する
 */
function get_article_detail($conn, $article_id)
{
    $stmt = $conn->prepare("SELECT id, user_id, title, body FROM articles WHERE id = ?");
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $user_id, $title, $body); // 変な書き方な気がするけどな
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
    $stmt = $conn->prepare("INSERT INTO logs (title, start, goal, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss",$title, $start, $goal, $comment);
    $stmt->execute();
    $stmt->close();
    http_response_code(201);
    return;
}