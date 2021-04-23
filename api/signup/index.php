<?php

// POST parameters
$request = file_get_contents("php://input");
$post_array = json_decode($request, true);

$email = $post_array["email"];
$name = $post_array["name"];
$psw = $post_array["password"];
$location = $post_array["location"];
$gender = $post_array["gender"];
$bd = $post_array["birthdate"];

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


$query = "INSERT INTO usuario (correo, nombre, psw, localizacion, sexo, fecha_nac) VALUES (" . 
                                    "'" . $email . "'," .
                                    "'" . $name . "'," .
                                    "'" . $psw . "'," .
                                    "'" . $location . "'," .
                                    "'" . $gender . "'," .
                                    "'" . $bd . "')";

//echo $query;

if (mysqli_query($conn, $query)) {
    $message = "2: Signup succesfull!!";
    echo json_encode(array("message" => $message));
    exit();
}
else {
    $message = "3: Signup failed";
    echo json_encode(array("message" => $message));
    exit();
}
