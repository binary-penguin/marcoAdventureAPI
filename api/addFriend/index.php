<?php

// POST parameters
$request = file_get_contents("php://input");
$post_array = json_decode($request, true);

$email_user = $post_array["email-user"];
$email_friend = $post_array["email-friend"];

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


$query = "SELECT id FROM usuario WHERE correo=". "'" . $email_user . "'";
$id_check = mysqli_query($conn, $query);

// Get row data!
$row = mysqli_fetch_assoc($id_check);
$id_user= $row['id'];


$query2 = "SELECT id FROM usuario WHERE correo=". "'" . $email_friend . "'";
$id_check2 = mysqli_query($conn, $query2);

if (mysqli_num_rows($id_check2) !== 1) 
{
    $message = "1: No friend found with that email";
    echo json_encode(array("message" => $message));
    exit();
}

// Get row data!
$row2 = mysqli_fetch_assoc($id_check2);
$id_friend= $row2['id'];


$query3 = "INSERT INTO amigos (id_usuario, id_amigo) VALUES (" . intval($id_user) . "," . intval($id_friend) . ")"; 
//echo $query3;
$add_friend = mysqli_query($conn, $query3);

if ($add_friend) {
    $message = "2: Friend " . $email_friend . " added with success!!";
    echo json_encode(array("message" => $message));
    exit();
}
else {
    $message = "3: Sth went wrong, maybe he or she('s) already your friend";
    echo json_encode(array("message" => $message));
    exit();
}

