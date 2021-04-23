<?php
// POST parameters
$request = file_get_contents("php://input");
$post_array = json_decode($request, true);

$email = $post_array["email"];


$host = '127.0.0.1';
$user = 'azure';
$password = '6#vWHD_$';
$db = 'game';
$port = 49868;
$scores_global = [];
$user_friends = [];
$friends_scores = [];

$conn = mysqli_connect($host, $user, $password, $db, $port);

if (mysqli_connect_errno()) {
    echo json_encode(array("mail" => "NONE", "score" => "0", "date" => "Connection to Db failed"));
    exit();
}

// 1. Get current user id

$query = "SELECT id FROM usuario WHERE correo=". "'" . $email . "'";
$id_check = mysqli_query($conn, $query);
//echo $query;


// 1.1 Get row data!
$row = mysqli_fetch_assoc($id_check);
$id_user= $row['id'];


// 2. Get all user friends
$query = "SELECT usuario.correo, amigos.id_amigo, amigos.id_usuario
            FROM usuario 
            JOIN amigos
            ON amigos.id_amigo=usuario.id
            WHERE amigos.id_usuario=" . intval($id_user);
//echo $query;

$result = mysqli_query($conn, $query);

// No scores registered
if (mysqli_num_rows($result) < 1) {
    echo json_encode(array("mail" => "NONE", "score" => "NONE", "date" => "NONE"));
    exit();
}

// 3. Create array of arrays
while ($row = mysqli_fetch_assoc($result)) {
    $user_friends[] = array("mail" => $row["correo"], "id" => strval($row["id_amigo"]));
}


// 4. Loop through each friend and get its top scores
foreach ($user_friends as $friend) {
    $query = "SELECT * FROM partidas WHERE id_usuario=" . $friend["id"] . " ORDER BY partidas.puntaje ASC, partidas.fecha ASC ";
    //echo $query;

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $friends_scores[] = array("score" => strval($row["puntaje"]), "email" => $friend["mail"], "date" => $row["fecha"]);
    }
}

// 3. Serialize JSON
echo json_encode($friends_scores);
exit();
