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
    // Prepare the data for insertion
    $quantidade = isset($jsonParam['quantidade']) ? intval($jsonParam['quantidade']) : 0;
    $cdMarmita = isset($jsonParam['Marmita_idMarmita']) ? intval($jsonParam['Marmita_idMarmita']) : 0;
    $cdCarrinho = isset($jsonParam['Carrinho_idCarrinho']) ? intval($jsonParam['Carrinho_idCarrinho']) : 0;

    // Prepare the SQL statement for insertion
    $insertQuery = "INSERT INTO pedido (quantidade, Marmita_idMarmita, Carrinho_idCarrinho) 
		VALUES ('$quantidade', '$cdMarmita', '$cdCarrinho')";

    if ($con->query($insertQuery) === true) {
        // Insertion successful
        $response = array(
            'success' => true, 
            'message' => 'Pedido inserido com sucesso!'
        );
        echo json_encode($response);
    } else {
        // Error in insertion
        $response = array(
            'success' => false,
            'message' => 'Erro no registro do pedido: ' . $con->error
        );
        echo json_encode($response);
    }
} else {
    // No data provided
    $response = array(
        'success' => false,
        'message' => 'Dados insuficientes para o registro do pedido!'
    );
    echo json_encode($response);
}

$con->close();

?>