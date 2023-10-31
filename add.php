<?php
//incluindo o arquivo de configuração com as informações das conexões do banco de dados
include 'config.php';
// Verifica se os dados do formulário estão presentes
//o POST é um método utilizado para enviar ddados para o servidor de forma que os dados não são visíveis na URL
//isset é para verificar se uma variável está definida e não é nula.
//STMT ->  para representar uma declaração preparada (prepared statement). Declarações preparadas são uma forma de executar consultas SQL de maneira mais segura
//$conn -> varíavel usada p armazenar a conexão com o banco de dados em scripts
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
//Fechamento da declaração preparada p poder liberar os recursos
    $stmt->close();
} else {
    $response = ['status' => 'error', 'message' => 'Parâmetros inválidos.'];
}

// Envia a resposta como JSON, útil pq o script tá sendo chamado por meio de uma requisição AJAX
header('Content-Type: application/json');
echo json_encode($response);
?>
