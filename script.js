//código dentro da função anônima será executado assim que o DOM (Document Object Model) estiver completamente carregado

//
document.addEventListener('DOMContentLoaded', function () {
    let editingUserId = null; //-> usada para rastrear o ID do usuário em edição
    const addUserButton = document.querySelector('#userModal .modal-body #btnSave'); //->Representa o botão de salvar dentro do modal de usuário
    const userForm = document.querySelector('#userModal .modal-body form'); // -> é o formulário dentro do modal
    const userList = document.querySelector('.table tbody'); // -> tabela de usuários

    //utilizando jQuery para realizar uma requisição AJAX para o servidor
    //O servidor retorna dados de usuários, que são processados e adicionados à tabela de usuários
    //A resposta é esperada ser um JSON que é convertido para um array de objetos JS
    function loadUsersFromDatabase() {
        $.ajax({
            url: 'get.php',
            method: 'GET',
            success: function (response) {
                console.log('Resposta do servidor ao carregar usuários:', response);
                try {
                    const users = JSON.parse(response);
                    users.forEach(user => addUserToTable(user.id, user.nome, user.checkIn, user.checkOut, user.status));
                } catch (error) {
                    console.error('Erro ao processar a resposta do servidor:', error);
                }
            },
            error: function (xhr, status, error) {
                console.error('Erro ao carregar usuários:', error);
                console.log('Resposta completa do servidor:', xhr.responseText);
            }
        });
    }
    //adiciona ou atualiza uma linha na tabela de usuários com os dados fornecidos.
    function addUserToTable(id, nome, checkIn, checkOut, status) {
        const existingRow = userList.querySelector(`tr[data-id="${id}"]`);

        // Remove a linha existente se o usuário já estiver na tabela
        if (existingRow) {
            existingRow.remove();
        }

        const newRow = document.createElement('tr');
        newRow.dataset.id = id; // Adiciona o atributo data-id
        newRow.innerHTML = `
            <td>${id}</td>
            <td><a href="#" class="username">${nome}</a></td>
            <td>${checkIn}</td>
            <td>${checkOut}</td>
            <td>${status}</td>
            <td>
                <a href="#" class="settings" title="Settings" data-toggle="tooltip"><i class="fa fa-pencil"></i></a>
                <a href="#" class="delete" title="Delete" data-toggle="tooltip"><i class="fa fa-trash"></i></a>
            </td>
        `;

        userList.appendChild(newRow);

        newRow.querySelector('.settings').addEventListener('click', editUser);
        newRow.querySelector('.delete').addEventListener('click', deleteUser);
    }
    //event listener -> reage ao clique no botão de adicionar usuário, coleta os dados do formulário, e realiza uma requisição AJAX para o servidor
    addUserButton.addEventListener('click', function () {
        const userId = userForm.querySelector('#userId').value;
        const formData = new FormData(userForm);
        formData.append('userId', userId);

        console.log('Dados a serem enviados:', formData);

        $.ajax({
            url: userId ? 'update.php' : 'add.php',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json', // Adicione esta linha para indicar que a resposta é JSON
            success: function (response) {
                console.log('Resposta do servidor após adição do usuário:', response);
                if (response.status === 'success') {
                    console.log('Usuário adicionado ou atualizado com sucesso.');

                    // Remove a linha existente se o usuário já estiver na tabela
                    const existingRow = userList.querySelector(`tr[data-id="${userId}"]`);
                    if (existingRow) {
                        existingRow.remove();
                    }

                    // Adiciona o usuário à tabela
                    addUserToTable(response.id, formData.get('userName'), formData.get('checkIn'), formData.get('checkOut'), formData.get('status'));
                    userForm.reset();
                    $('#userModal').modal('hide');
                } else {
                    console.error('Erro ao salvar usuário:', response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Erro ao salvar usuário:', error);

                // Log da resposta completa do servidor
                console.log('Resposta completa do servidor:', xhr.responseText);

                // Informações adicionais do objeto xhr
                console.log('Status:', xhr.status);
                console.log('Status Text:', xhr.statusText);
            }
        });
    });

    // Adiciona um event listener aos botões "Editar"
    // Adiciona um event listener ao botão "Salvar" no modal de edição
    const saveButton = document.querySelector('#userModal #btnSave');
    saveButton.addEventListener('click', function () {
        const userId = userForm.querySelector('#userId').value;
        const existingRow = userList.querySelector(`tr[data-id="${userId}"]`);

        if (existingRow) {
            // Atualiza os dados na tabela se o usuário já existir na tabela
            existingRow.cells[1].querySelector('.username').textContent = userForm.querySelector('#userName').value;
            existingRow.cells[2].textContent = userForm.querySelector('#checkIn').value;
            existingRow.cells[3].textContent = userForm.querySelector('#checkOut').value;
            existingRow.cells[4].textContent = userForm.querySelector('#status').value;
        } else {
            // Adiciona o usuário à tabela se não existir
            addUserToTable(userId, userForm.querySelector('#userName').value, userForm.querySelector('#checkIn').value, userForm.querySelector('#checkOut').value, userForm.querySelector('#status').value);
        }

        // Fecha o modal
        $('#userModal').modal('hide');
    });

    function editUser() {
        const row = this.closest('tr');
        editingUserId = row.dataset.id;
        const id = row.dataset.id;

        // Remove a linha antiga da tabela
        const existingRow = userList.querySelector(`tr[data-id="${id}"]`);
        if (existingRow) {
            existingRow.remove();
        }

        // Preenche o formulário com os dados do usuário para edição
        userForm.querySelector('#userId').value = id;
        userForm.querySelector('#userName').value = row.cells[1].querySelector('.username').textContent;
        userForm.querySelector('#checkIn').value = row.cells[2].textContent;
        userForm.querySelector('#checkOut').value = row.cells[3].textContent;
        userForm.querySelector('#status').value = row.cells[4].textContent;

        // Abre o modal
        $('#userModal').modal('show');

        // Adicione um event listener ao modal para limpar o editingUserId quando o modal é fechado
        $('#userModal').on('hidden.bs.modal', function () {
            editingUserId = null;
        });
    }

    // Adiciona um event listener aos botões "Excluir"
    function deleteUser() {
        const row = this.closest('tr');
        const id = row.cells[0].textContent;

        // Remove a linha da tabela
        row.parentNode.removeChild(row);

        // Exclui o usuário do banco de dados
        $.ajax({
            url: 'delete.php',
            method: 'POST',
            data: { id: id },
            success: function (response) {
                console.log(response);
                if (response === 'success') {
                    loadUsersFromDatabase();  // Atualiza a tabela
                    userForm.reset();
                    $('#userModal').modal('hide');
                } else {
                    console.error('Erro ao salvar usuário:', response);
                }
            },
        });
    }

    // Carrega os usuários ao carregar a página
    loadUsersFromDatabase();
});
