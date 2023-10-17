<?php

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