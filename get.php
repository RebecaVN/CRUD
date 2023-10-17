<?php

include 'config.php';

// Função para obter os usuários do banco de dados
function getStoredUsers($conn) {
    $users = array();
    $sql = "SELECT * FROM hospedagens";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    echo json_encode($users);
}

getStoredUsers($conn);

?>