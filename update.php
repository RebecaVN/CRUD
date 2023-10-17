<?php
include 'config.php';

// Definindo variáveis
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

// Manipula a imagem
$image = '';  // Adicione lógica para manipular a imagem aqui, se necessário

// Atualiza o usuário no banco de dados
$sql = "UPDATE hospedagens SET nome=?, checkIn=?, checkOut=?, status=?, image=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssi", $nome, $checkIn, $checkOut, $status, $image, $id);

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