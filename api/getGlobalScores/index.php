<?php

$host = '127.0.0.1';
$user = 'azure';
$password = '6#vWHD_$';
$db = 'game';
$port = 49868;
$scores_global = [];

$conn = mysqli_connect($host, $user, $password, $db, $port);

if (mysqli_connect_errno()) {
    echo json_encode(array("mail" => "NONE", "score" => "0", "date" => "Connection to Db failed"));
    exit();
}

// 1. Get the historic top 3 scores
$query = "SELECT usuario.correo, partidas.puntaje, partidas.fecha 
          FROM partidas 
          INNER JOIN usuario
          WHERE usuario.id=partidas.id_usuario
          ORDER BY partidas.puntaje ASC,
                   partidas.fecha ASC";
//echo $query;

$result = mysqli_query($conn, $query);

// No scores registered
if (mysqli_num_rows($result) < 1) {
    echo json_encode(array("mail" => "NONE", "score" => "0", "date" => "NONE"));
    exit();
}



// 3. Create array of arrays
while ($row = mysqli_fetch_assoc($result)) {
    $scores_global[] = array("mail" => $row["correo"], "score" => strval($row["puntaje"]), "date" => $row["fecha"]);
}

// 3. Serialize JSON
echo json_encode($scores_global);
exit();
