<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = '127.0.0.1:3306';
$username = 'root';
$password = 'Adinam1234';
$dbname = 'Cantinhododri';

$con = new mysqli($servername, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Retrieve the JSON parameter
$jsonParam = json_decode(file_get_contents('php://input'), true);

if (!empty($jsonParam)) {
    // Prepare the WHERE clause
    $whereClause = ' WHERE ';
    foreach ($jsonParam as $field => $value) {
        if ($value != '' && $value != '0') {
            $whereClause .= "$field = '$value' AND ";
        }
    }
    $whereClause = rtrim($whereClause, ' AND ');

    // Prepare the SQL statement
    $consulta = "SELECT idPedido, quantidade, Marmita_idMarmita, Carrinho_idCarrinho
                 FROM Pedido $whereClause";

    $result = $con->query($consulta);

    $json = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Convert character encoding for each field
            foreach ($row as &$value) {
                $value = mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
            }

            $usuario = array(
                "idPedido" => $row['idPedido'],
                "quantidade" => $row['quantidade'],
                "Marmita_idMarmita" => $row['Marmita_idMarmita'],
                "Carrinho_idCarrinho" => $row['Carrinho_idCarrinho'],
            );
            $json[] = $usuario;
        }
    } else {
        $usuario = array(
            "idPedido" => 0,
            "quantidade" => 0,
            "Marmita_idMarmita" => 0,
            "Carrinho_idCarrinho" => 0,
        );
        $json[] = $usuario;
    }

    if ($json) {
        $encoded_json = json_encode($json);
        if ($encoded_json === false) {
            echo "Error encoding JSON: " . json_last_error_msg();
        } else {
            header('Content-Type: application/json; charset=utf-8');
            echo $encoded_json;
        }
    } else {
        echo "Empty JSON data.";
    }

    $result->free_result();
}

$con->close();

?>