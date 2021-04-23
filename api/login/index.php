<?php

// POST parameters
$request = file_get_contents("php://input");
$post_array = json_decode($request, true);

$email = $post_array["email"];
$psw = $post_array["password"];

//echo "Mail: " . $email . "\n";
//echo "Psw: " . $psw . "\n";

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


$query = "SELECT * FROM usuario WHERE correo=". "'" . $email . "'";
$id_check = mysqli_query($conn, $query);

if (mysqli_num_rows($id_check) !== 1) 
{
    $message = "1: User not found";
    echo json_encode(array("message" => $message));
    exit();
}

// Get row data!
$row = mysqli_fetch_assoc($id_check);
$db_psw = $row['psw'];

if (strcmp($db_psw, $psw)===0)
{
    $message = "2: Login succesfull!!";
    echo json_encode(array("message" => $message));
    exit();
}
else {
    $message = "3: Incorrect psw";
    echo json_encode(array("message" => $message));
    exit();
}
