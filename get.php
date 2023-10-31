<?php
//incluindo o arquivo de configuração com as informações das conexões do banco de dados
include 'config.php';
// Função para obter os usuários do banco de dados
function getStoredUsers($conn) {
    //Declaração de um array 
    $users = array();
    //consulta SQL para obter usuários
    //$conn ->variável usada p armazenar a conexão com o banco de dados em scripts
    $sql = "SELECT * FROM hospedagens";
    $result = $conn->query($sql);

    //Execução da consulta SQL
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
//Conversão do array para JSON
    echo json_encode($users);
}
//passando a conexão com o banco de dados como argumento
getStoredUsers($conn);
?>