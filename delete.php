<?php
//incluindo o arquivo de configuração com as informações das conexões do banco de dados
//$conn -> variável usada p armazenar a conexão com o banco de dados em scripts
include 'config.php';

// Obtém o ID do usuário a ser excluído
$id = $_POST['id'];

// Exclui o usuário do banco de dados
$sql = "DELETE FROM hospedagens WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo 'success';
} else {
    echo 'error';
}

?>