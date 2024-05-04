<!-- Menu -->
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand">
        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none" style="margin-top: 100px;">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>
    <br>
    <ul class="menu-inner py-1">
        <!-- Dashboard -->
        <li class="menu-item active">
            <a href="#" id="newChatButton" class="menu-link sidebar__link">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon-md">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M16.7929 2.79289C18.0118 1.57394 19.9882 1.57394 21.2071 2.79289C22.4261 4.01184 22.4261 5.98815 21.2071 7.20711L12.7071 15.7071C12.5196 15.8946 12.2652 16 12 16H9C8.44772 16 8 15.5523 8 15V12C8 11.7348 8.10536 11.4804 8.29289 11.2929L16.7929 2.79289ZM19.7929 4.20711C19.355 3.7692 18.645 3.7692 18.2071 4.2071L10 12.4142V14H11.5858L19.7929 5.79289C20.2308 5.35499 20.2308 4.64501 19.7929 4.20711ZM6 5C5.44772 5 5 5.44771 5 6V18C5 18.5523 5.44772 19 6 19H18C18.5523 19 19 18.5523 19 18V14C19 13.4477 19.4477 13 20 13C20.5523 13 21 13.4477 21 14V18C21 19.6569 19.6569 21 18 21H6C4.34315 21 3 19.6569 3 18V6C3 4.34314 4.34315 3 6 3H10C10.5523 3 11 3.44771 11 4C11 4.55228 10.5523 5 10 5H6Z" fill="currentColor"></path>
                </svg>
                <div data-i18n="Analytics">New Chat</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">Informações</span></li>
		<!-- Lista de Histórico com rolagem -->
    <div class="menu-item" style="max-height: calc(100vh - 300px); overflow-y: auto;">
        <ul class="menu-inner py-1">
        <!-- PHP para listar históricos -->
        <?php
        try {
            // Preparando e executando a consulta SQL
            $stmt = $conexao->prepare("SELECT id, nome, mainid FROM historico WHERE byid = :iduser");
            $stmt->bindParam(':iduser', $_SESSION['iduser'], PDO::PARAM_INT);
            $stmt->execute();

            // Obtendo os resultados
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Exibindo os nomes do histórico como botões
            foreach ($resultados as $resultado) {
                $historicalName = $resultado['nome'];
                $mainid = $resultado['mainid'];
                echo '<li class="menu-item">';
                echo '<div class="d-flex align-items-center">';
                // Modificação aqui: Adicionando o nome do histórico como parâmetro na URL
                echo '<a href="ativar-historico.php?historicalName=' . urlencode($historicalName) . '&mainid=' . urlencode($mainid) . '" class="sidebar__link menu-link btn btn-outline-primary mr-2">';
                echo '<div data-i18n="Sair">' . $historicalName . '</div>';
                echo '</a>';
                // Botão de exclusão com ícone de lixeira e confirmação
                echo '<button class="btn btn-danger delete-historical" data-historical-id="' . $resultado['id'] . '">';
                echo '<i class="bx bx-trash"></i>'; // Ícone de lixeira do Boxicons
                echo '</button>';
                echo '</div>';
                echo '</li>';
            }
        } catch (PDOException $e) {
            echo 'Erro ao buscar dados do histórico: ' . $e->getMessage();
        }
        ?>
           </ul>
    </div>


       <!-- Botão Sair fixo no final -->
    <div class="menu-item" style="position: fixed; bottom: 20px;">
        <a href="./logout.php" class="menu-link btn btn-primary">
            <i class="menu-icon tf-icons bx bxs-log-in"></i>
            <div data-i18n="Sair">Sair</div>
        </a>
    </div>
</aside>