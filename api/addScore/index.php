<?php
// POST parameters
$request = file_get_contents("php://input");
$post_array = json_decode($request, true);

$email = $post_array["email"];
$score = $post_array["score"];
$date = $post_array["date"];
$arr_scores = [];

//echo "Mail: " . $email . "\n";

$host = '127.0.0.1';
$user = 'azure';
$password = '6#vWHD_$';
$db = 'game';
$port = 49868;
$message = "";

$conn = mysqli_connect($host, $user, $password, $db, $port);

if (mysqli_connect_errno()) {
    $message = "0: Connection failed";
    echo json_encode(array("message" => $message));
    exit();
}

// 1. Get current user id

$query = "SELECT id FROM usuario WHERE correo=". "'" . $email . "'";
$id_check = mysqli_query($conn, $query);
//echo $query;


// 1.1 Get row data!
$row = mysqli_fetch_assoc($id_check);
$id_user= $row['id'];



// 2. Get the historic top 3 scores
$query1 = "SELECT * FROM partidas WHERE id_usuario=" . intval($id_user) . " ORDER BY partidas.puntaje ASC";
//echo $query;

$result = mysqli_query($conn, $query1);

// No scores registered
if (mysqli_num_rows($result) < 3) {

    $query2 = "INSERT INTO partidas (id_usuario, puntaje, fecha) VALUES ("  . intval($id_user) . "," . intval($score) . ",'" . $date . "')"; 
    $results = mysqli_query($conn, $query2);

    $message = "1: Less than 3 scores, so score added!!";
    echo json_encode(array("message" => $message));
    exit();
}

else {
    $query3 = "SELECT MIN(puntaje) FROM partidas WHERE id_usuario=" . intval($id_user);
    //echo $query3;
    $result = mysqli_query($conn, $query3);

    // 3. Create array of arrays
    while ($row = mysqli_fetch_assoc($result)) {
        $min_score = $row["MIN(puntaje)"]; // 5, 50, 100
    }

    if (intval($score) > intval($min_score)) { // 120
        $query4 = "UPDATE partidas SET puntaje=" . intval($score) . " WHERE id_usuario=" . intval($id_user) . " AND puntaje=" . intval($min_score);
       
        $result4 = mysqli_query($conn, $query4);

        if ($result4) {
            $message = "2: Score " . strval($score). " added!!";
            echo json_encode(array("message" => $message));
            exit();
        }
        else {
            $message = "4: Sth went wrong with " . strval($score). " score!!";
            echo json_encode(array("message" => $message));
            exit();
        }
       
    }
    $message = "3: Score " . strval($score). " not added to top scores!!";
    echo json_encode(array("message" => $message));
    exit();
}