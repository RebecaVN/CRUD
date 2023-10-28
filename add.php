<?php
include 'config.php';

// Verifica se os dados do formulário estão presentes
if (
    isset($_POST['userName']) &&
    isset($_POST['checkIn']) &&
    isset($_POST['checkOut']) &&
    isset($_POST['status'])
) {
    // Obtém dados do formulário
    $nome = $_POST['userName'];
    $checkIn = $_POST['checkIn'];
    $checkOut = $_POST['checkOut'];
    $status = $_POST['status'];

    // Insere o usuário no banco de dados usando declaração preparada
    $sql = "INSERT INTO hospedagens (nome, checkIn, checkOut, status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $nome, $checkIn, $checkOut, $status);

    if ($stmt->execute()) {
        $newUserId = $stmt->insert_id;  // Obtém o ID do usuário recém-adicionado
        $response = ['status' => 'success', 'id' => $newUserId];
    } else {
        $response = ['status' => 'error', 'message' => 'Erro ao salvar usuário: ' . $stmt->error];
    }

    $stmt->close();
} else {
    $response = ['status' => 'error', 'message' => 'Parâmetros inválidos.'];
}

// Envia a resposta como JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
