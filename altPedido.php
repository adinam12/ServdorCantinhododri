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

// Retrieve the JSON parameter from the POST request
$jsonParam = json_decode(file_get_contents('php://input'), true);

if (!empty($jsonParam)) {
    // Prepare the data for updating
    $idPedido = isset($jsonParam['idPedido']) ? intval($jsonParam['idPedido']) : 0;
    $quantidade = isset($jsonParam['quantidade']) ? $jsonParam['quantidade'] : '';
    $cdMarmita = isset($jsonParam['Marmita_idMarmita']) ? $jsonParam['Marmita_idMarmita'] : '';

    // Prepare the SQL statement for updating
    $updateQuery = "UPDATE Pedido SET quantidade = '$quantidade', Marmita_idMarmita = '$cdMarmita'
        WHERE idPedido = '$idPedido'";

    if ($con->query($updateQuery) === true) {
        // Update successful
        $response = array(
            'success' => true,
            'message' => 'Pedido atualizado com sucesso!'
        );
        echo json_encode($response);
    } else {
        // Error in update
        $response = array(
            'success' => false,
            'message' => 'Erro ao atualizar o pedido: ' . $con->error
        );
        echo json_encode($response);
    }
} else {
    // No data provided
    $response = array(
        'success' => false,
        'message' => 'Dados insuficientes para atualizar o pedido!'
    );
    echo json_encode($response);
}

$con->close();

?>