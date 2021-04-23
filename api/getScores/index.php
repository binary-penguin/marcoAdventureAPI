<?php

// POST parameters
$request = file_get_contents("php://input");
$post_array = json_decode($request, true);

$email_user = $post_array["email"];

$host = '127.0.0.1';
$user = 'azure';
$password = '6#vWHD_$';
$db = 'game';
$port = 49868;
$scores_user = [];

$conn = mysqli_connect($host, $user, $password, $db, $port);

if (mysqli_connect_errno()) {
    echo json_encode(array("score" => "0", "date" => "Connection to Db failed"));
    exit();
}


// 1. Get current user id

$query = "SELECT id FROM usuario WHERE correo=". "'" . $email_user . "'";
$id_check = mysqli_query($conn, $query);
//echo $query;


// 1.1 Get row data!
$row = mysqli_fetch_assoc($id_check);
$id_user= $row['id'];

//echo $id_user;

// 2. Get the historic top 3 scores
$query = "SELECT * FROM partidas WHERE id_usuario=" . intval($id_user) . " ORDER BY partidas.puntaje ASC";
//echo $query;

$result = mysqli_query($conn, $query);

// No scores registered
if (mysqli_num_rows($result) < 1) {
    echo json_encode(array("score" => "0", "date" => "NONE"));
    exit();
}



// 3. Create array of arrays
while ($row = mysqli_fetch_assoc($result)) {
    $scores_user[] = array("score" => strval($row["puntaje"]), "date" => $row["fecha"]);
}

// 3. Serialize JSON
echo json_encode($scores_user);
exit();
