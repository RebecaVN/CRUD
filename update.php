<?php
//incluindo o arquivo de configuração com as informações das conexões do banco de dados
include 'config.php';
// Definindo variáveis
//isset é para verificar se uma variável está definida e não é nula.
//STMT ->  para representar uma declaração preparada (prepared statement). Declarações preparadas são uma forma de executar consultas SQL de maneira mais segura
$id = isset($_POST['userId']) ? $_POST['userId'] : '';
$nome = isset($_POST['userName']) ? $_POST['userName'] : '';
$checkIn = isset($_POST['checkIn']) ? $_POST['checkIn'] : '';
$checkOut = isset($_POST['checkOut']) ? $_POST['checkOut'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : '';

// Log das variáveis
error_log("ID: " . $id);
error_log("Nome: " . $nome);
error_log("CheckIn: " . $checkIn);
error_log("CheckOut: " . $checkOut);
error_log("Status: " . $status);

// Atualiza o usuário no banco de dados
//$conn -> varíavel usada p armazenar a conexão com o banco de dados em scripts
$sql = "UPDATE hospedagens SET nome=?, checkIn=?, checkOut=?, status=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $nome, $checkIn, $checkOut, $status, $id);


$response = array(); // Resposta a ser enviada

if ($stmt->execute()) {
    error_log('Sucesso ao executar a consulta.');
    $response['status'] = 'success';
} else {
    error_log('Erro ao executar a consulta: ' . $stmt->error);
    $response['status'] = 'error';
    $response['message'] = $stmt->error;
}
$stmt->close();
// Envia a resposta como JSON
header('Content-Type: application/json');
echo json_encode($response);
?>