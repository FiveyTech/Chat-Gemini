<?php
date_default_timezone_set('America/Sao_Paulo');
session_start();


if (session_status() == PHP_SESSION_ACTIVE && isset($_SESSION['email'])) {
    header('Location: functions_nivel.php');
    exit();
}



?>
<html lang="pt-br" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="./assets/" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Chat GP</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="./assets/img/favicon/favicon.ico" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link rel="stylesheet" href="./assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="./assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="./assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="./assets/css/global.css" />
    <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="./assets/vendor/css/pages/page-auth.css" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <link rel="stylesheet" href="./assets/vendors/mdi/css/materialdesignicons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.13/dist/sweetalert2.all.min.js"></script>
    <script src="./assets/vendor/js/helpers.js"></script>
    <script src="./assets/js/config.js"></script>
</head>

<body>
    <!-- Content -->

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <div class="app-brand demo">
                                <img src="assets/img/logo.png" width="180" height="140" alt="Logo">
                            </div>

                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-2" style="text-align: center;">Bem-vindo! 👋</h4>
                        <p class="mb-4" style="text-align: center;">Faça login em sua conta e comece a aventura</p>

                        <?php
                        if (isset($_SESSION['loginerro'])) {
                            echo "<script>Swal.fire({ icon: 'error', title: 'Erro!', html: '" . $_SESSION['loginerro'] . "' });</script>";
                            unset($_SESSION['loginerro']);
                        }
                        if (isset($_SESSION['loginsucesso'])) {
                            echo "<script>Swal.fire({ icon: 'success', title: 'Sucesso!', html: '" . $_SESSION['loginsucesso'] . "' });</script>";
                            unset($_SESSION['loginsucesso']);
                        }
                        ?>
                        <div class="social-login" style="text-align: center;">
                            <form action="valida_google.php" method="post">
                                <button type="submit" class="btn btn-primary" id="valida_google" name="submit" class="google">
                                    <i class='bx bxl-google'></i>
                                    Entrar com Google
                                </button>
                            </form>
                        </div>


                        <form id="formAuthentication" class="mb-3" action="./functions_login.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">EMAIL</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="Digite seu e-mail ou nome de usuário" autofocus />
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">SENHA</label>
                                    <a href="auth-forgot-password.php">
                                        <small>Esqueceu sua senha?</small>
                                    </a>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="senha" class="form-control" name="senha" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remember-me" />
                                    <label class="form-check-label" for="remember-me"> Lembre de mim </label>
                                </div>
                                <BR>
                                <div>
                                    <a href="./cadastra.php">Não tem uma conta?</a>
                                </div>
                            </div>
                            <br>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Entrar</button>
                            </div>
                        </form>

                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>

    <!-- / Content -->
    <script src="./assets/vendor/libs/jquery/jquery.js"></script>
    <script src="./assets/vendor/libs/popper/popper.js"></script>
    <script src="./assets/vendor/js/bootstrap.js"></script>
    <script src="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="./assets/vendor/js/menu.js"></script>
    <script src="./assets/js/pages-account-settings-account.js"></script>
    <script src="./assets/vendor/libs/apex-charts/apexcharts.js"></script>
    <script src="./assets/js/main.js"></script>
    <script src="./assets/js/dashboards-analytics.js"></script>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>


</body>
</html>