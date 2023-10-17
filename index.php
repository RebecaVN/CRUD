<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "suitselect";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

function loadUsersFromDatabase($conn)
{
    $sql = "SELECT * FROM hospedagens";
    $result = $conn->query($sql);

    $users = array();

    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    return $users;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["userId"])) {
        $userId = $_POST["userId"];
        $userName = $_POST["userName"];
        $checkIn = $_POST["checkIn"];
        $checkOut = $_POST["checkOut"];
        $status = $_POST["status"];

        if ($userId) {
            $sql = "UPDATE hospedagens SET nome=?, checkIn=?, checkOut=?, status=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $userName, $checkIn, $checkOut, $status, $userId);
        } else {
            $sql = "INSERT INTO hospedagens (nome, checkIn, checkOut, status) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $userName, $checkIn, $checkOut, $status);
        }

        if ($stmt->execute()) {
            echo "Operação realizada com sucesso.";
        } else {
            echo "Erro na operação: " . $stmt->error;
        }
    } else {
        echo "Parâmetros inválidos.";
    }
}

$sql = "SELECT * FROM hospedagens";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Suit Select Resort</title>
    <link rel="icon" href="Beach Resort.png" sizes="48x48" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
    <div class="background-container">
        <div>
            <h1>
                <img class="logo" src="Beach Resort.png" alt="Logo">
            </h1>
        </div>
        <!-- Contêiner principal -->
        <div class="container-xl">
            <!-- Tabela responsiva -->
            <div class="table-responsive">
                <div class="table-wrapper">
                    <!-- Título da tabela -->
                    <div class="table-title">
                        <div class="row">
                            <div class="col-sm-5">
                                <!-- Título da página -->
                                <h2><b>Lista De Hospedagens</b></h2>
                            </div>
                        </div>
                    </div>

                    <!-- Tabela principal -->
                    <table class="table table-striped table-hover">
                        <thead>
                            <!-- Cabeçalho da tabela -->
                            <tr>
                                <th>#</th>
                                <th>Nome</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Conteúdo da tabela será preenchido dinamicamente com JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal para adicionar/editar usuário -->
        <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <!-- Título do modal -->
                        <h5 class="modal-title" id="exampleModalLabel">Adicionar/Editar Usuário</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulário para adicionar/editar usuário -->
                        <form id="userForm" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="userName">Nome:</label>
                                <!-- Campo de entrada para o nome do usuário -->
                                <input type="text" class="form-control" id="userName" name="userName" required>
                                <!-- Campo oculto para armazenar o ID do usuário -->
                                <input type="hidden" id="userId">
                            </div>
                            <div class="form-group">
                                <label for="checkIn">Check-in:</label>
                                <!-- Campo de entrada para o check-in -->
                                <input type="text" class="form-control" id="checkIn" name="checkIn" required>
                            </div>
                            <div class="form-group">
                                <label for="checkOut">Check-out:</label>
                                <!-- Campo de entrada para o check-out -->
                                <input type="text" class="form-control" id="checkOut" name="checkOut" required>
                            </div>
                            <div class="form-group">
                                <label for="status">Status:</label>
                                <!-- Campo de entrada para o status -->
                                <input type="text" class="form-control" id="status" name="status" required>
                            </div>
                            <!-- Botão para salvar as alterações ou adições -->
                            <button type="button" class="btn btn-primary" id="btnSave">Salvar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-7">
            <!-- Botão para adicionar novo usuário, abre o modal -->
            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#userModal"><i class="fa fa-plus"></i>
                Adicionar Novo</a>
        </div>
        <!-- Rodapé -->
        <footer>
            <p>&copy; Suit select </p>
        </footer>
    </div>

    <!-- Inclusão de bibliotecas JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="index.js"></script>
    <script src="script.js"></script>
</body>

</html>

<?php
// Adicionado para fechar a conexão com o banco de dados
$conn->close();
?>
