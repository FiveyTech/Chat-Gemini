-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 04/05/2024 às 01:30
-- Versão do servidor: 8.0.27-18
-- Versão do PHP: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `chatgpphp`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `accounts`
--

CREATE TABLE `accounts` (
  `id` int NOT NULL,
  `nome` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `senha` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nivel` varchar(300) COLLATE utf8mb4_general_ci NOT NULL DEFAULT '3'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `accounts`
--

INSERT INTO `accounts` (`id`, `nome`, `email`, `senha`, `nivel`) VALUES
(2, 'Jefferson', 'sshturbovpn@gmail.com', '$2y$10$lp.GlJ/oJYVkaDHZesyhMO2swhJ9snKo7oLHCKA8I5mq9czzcqpbe', '3');

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico`
--

CREATE TABLE `historico` (
  `id` int NOT NULL,
  `byid` int NOT NULL,
  `mainid` int DEFAULT NULL,
  `nome` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `historico` text COLLATE utf8mb4_general_ci NOT NULL,
  `status` varchar(300) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `historico`
--

INSERT INTO `historico` (`id`, `byid`, `mainid`, `nome`, `historico`, `status`) VALUES
(53, 2, 844218, '16/04/2024 01:12', '[{\"role\":\"user\",\"text\":\"Ola\"},{\"role\":\"model\",\"text\":\"<p>Ol\\u00e1. Em que posso ajud\\u00e1-lo hoje?<\\/p>\"}]', 'desativado'),
(54, 2, 434046, '18/04/2024 01:34', '[{\"role\":\"user\",\"text\":\"#!\\/bin\\/bash\\n\\n# Verifica se o script est\\u00e1 sendo executado como root\\nif [[ $EUID -ne 0 ]]; then\\n   echo \\\"Este script deve ser executado como root\\\" \\n   exit 1\\nfi\\n\\n# Fun\\u00e7\\u00e3o para gerar uma senha aleat\\u00f3ria\\ngenerate_random_password() {\\n    < \\/dev\\/urandom tr -dc _A-Z-a-z-0-9 | head -c\\\"${1:-16}\\\"\\n    echo\\n}\\n\\n# Fun\\u00e7\\u00e3o para exibir uma mensagem de confirma\\u00e7\\u00e3o\\nconfirm() {\\n    read -r -p \\\"$1 [S\\/n] \\\" response\\n    case \\\"$response\\\" in\\n        [nN][oO]|[nN])\\n            false\\n            ;;\\n        *)\\n            true\\n            ;;\\n    esac\\n}\\n\\n# Atualiza o \\u00edndice de pacotes\\necho \\\"Atualizando o \\u00edndice de pacotes...\\\"\\nsudo apt update\\n\\n# Instala o software-properties-common para adicionar reposit\\u00f3rios\\necho \\\"Instalando o software-properties-common...\\\"\\nsudo apt install -y software-properties-common\\n\\n# Adiciona os reposit\\u00f3rios do Nginx e do PHP\\necho \\\"Adicionando os reposit\\u00f3rios do Nginx e do PHP...\\\"\\nsudo add-apt-repository -y ppa:ondrej\\/nginx\\nsudo LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej\\/php\\n\\n# Atualiza novamente o \\u00edndice de pacotes ap\\u00f3s adicionar os reposit\\u00f3rios\\necho \\\"Atualizando novamente o \\u00edndice de pacotes...\\\"\\nsudo apt update\\n\\n# Instala\\u00e7\\u00e3o do Node.js 20\\necho \\\"Instalando o Node.js 20...\\\"\\ncurl -fsSL https:\\/\\/deb.nodesource.com\\/setup_20.x | sudo -E bash -\\nsudo apt install -y nodejs\\n\\n# Instala\\u00e7\\u00e3o do Nginx\\necho \\\"Instalando o Nginx...\\\"\\nsudo apt install -y nginx\\n\\n# Instala\\u00e7\\u00e3o do PHP 8.2\\necho \\\"Instalando o PHP 8.2...\\\"\\nsudo apt install -y php8.2 php8.2-fpm php8.2-mysql php8.2-cli php8.2-mbstring php8.2-curl php8.2-zip php8.2-gd php8.2-xml php8.2-bcmath\\n\\n# Instala\\u00e7\\u00e3o do MySQL\\necho \\\"Instalando o MySQL...\\\"\\nsudo apt install -y mysql-server\\n\\n# Gerar uma senha aleat\\u00f3ria para o MySQL\\nmysql_password=$(generate_random_password)\\n\\n# Configura\\u00e7\\u00e3o autom\\u00e1tica do MySQL\\necho \\\"Configurando o MySQL...\\\"\\nsudo mysql -e \\\"ALTER USER \'root\'@\'localhost\' IDENTIFIED WITH mysql_native_password BY \'\'; FLUSH PRIVILEGES;\\\"\\nsudo mysql -e \\\"SET PASSWORD FOR \'root\'@\'localhost\' = \'$mysql_password\';\\\"\\nsudo mysql -e \\\"UPDATE mysql.user SET authentication_string = PASSWORD(\'$mysql_password\') WHERE User = \'root\';\\\"\\nsudo mysql -e \\\"DROP USER IF EXISTS \'\'@\'localhost\';\\\"\\nsudo mysql -e \\\"DROP USER IF EXISTS \'\'@\'$(hostname)\';\\\"\\nsudo mysql -e \\\"DROP DATABASE IF EXISTS test;\\\"\\nsudo mysql -e \\\"DELETE FROM mysql.db WHERE Db=\'test\' OR Db=\'test\\\\_%\';\\\"\\nsudo mysql -e \\\"FLUSH PRIVILEGES;\\\"\\n\\n# Instala\\u00e7\\u00e3o do phpMyAdmin para o PHP 8.2\\necho \\\"Instalando o phpMyAdmin para o PHP 8.2...\\\"\\necho \\\"phpmyadmin phpmyadmin\\/dbconfig-install boolean true\\\" | sudo debconf-set-selections\\necho \\\"phpmyadmin phpmyadmin\\/reconfigure-webserver multiselect none\\\" | sudo debconf-set-selections\\necho \\\"phpmyadmin phpmyadmin\\/mysql\\/app-pass password $mysql_password\\\" | sudo debconf-set-selections\\necho \\\"phpmyadmin phpmyadmin\\/mysql\\/admin-pass password $mysql_password\\\" | sudo debconf-set-selections\\nsudo apt install -y phpmyadmin php8.2-mbstring\\n\\n# Configura\\u00e7\\u00e3o do Nginx para o phpMyAdmin\\necho \\\"Configurando o Nginx para o phpMyAdmin...\\\"\\nsudo ln -s \\/usr\\/share\\/phpmyadmin \\/var\\/www\\/html\\/phpmyadmin\\n\\n# Configura\\u00e7\\u00e3o do Nginx para o phpMyAdmin\\nsudo tee \\/etc\\/nginx\\/sites-available\\/phpmyadmin > \\/dev\\/null <<EOF\\nserver {\\n    listen 80;\\n    listen [::]:80;\\n\\n    root \\/var\\/www\\/html;\\n\\n    index index.php index.html index.htm;\\n\\n    server_name _;\\n\\n    location \\/ {\\n        try_files $uri $uri\\/ =404;\\n    }\\n\\n    location ~ \\\\.php$ {\\n        include snippets\\/fastcgi-php.conf;\\n        fastcgi_pass unix:\\/run\\/php\\/php8.2-fpm.sock;\\n    }\\n\\n    location \\/phpmyadmin {\\n        root \\/usr\\/share\\/;\\n        index index.php index.html index.htm;\\n\\n        location ~ ^\\/phpmyadmin\\/(.+\\\\.php)$ {\\n            try_files $uri =404;\\n            root \\/usr\\/share\\/;\\n            fastcgi_pass unix:\\/run\\/php\\/php8.2-fpm.sock;\\n            fastcgi_index index.php;\\n            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\\n            include fastcgi_params;\\n        }\\n    }\\n\\n    location ~ \\/\\\\.ht {\\n        deny all;\\n    }\\n}\\nEOF\\n\\n# Ativa o site do phpMyAdmin\\nsudo ln -s \\/etc\\/nginx\\/sites-available\\/phpmyadmin \\/etc\\/nginx\\/sites-enabled\\/\\n\\n# Reinicia o Nginx para aplicar as altera\\u00e7\\u00f5es\\necho \\\"Reiniciando o Nginx...\\\"\\nsudo systemctl reload nginx\\n\\necho \\\"Todas as instala\\u00e7\\u00f5es e configura\\u00e7\\u00f5es foram conclu\\u00eddas com sucesso.\\\"\\necho \\\"Senha aleat\\u00f3ria para o MySQL: $mysql_password\\\"\\n\\ncomo corrigir esse codigo para garantir a instala\\u00e7\\u00e3o do php 8.2 no phpmyadmin\"},{\"role\":\"model\",\"text\":\"<p>Para garantir a instala\\u00e7\\u00e3o do PHP 8.2 no phpMyAdmin, \\u00e9 necess\\u00e1rio especificar a vers\\u00e3o desejada no arquivo de configura\\u00e7\\u00e3o do Nginx. Substitua esta linha:<\\/p>\\n<pre><code>fastcgi_pass unix:\\/run\\/php\\/php8.2-fpm.sock;<\\/code><\\/pre>\\n<p>por esta:<\\/p>\\n<pre><code>fastcgi_pass unix:\\/run\\/php\\/php8.2-fpm.sock;\\ninclude fastcgi_params;\\nfastcgi_param PHP_VERSION 8.2;<\\/code><\\/pre>\\n<p>Esta altera\\u00e7\\u00e3o garante que o phpMyAdmin use especificamente o PHP 8.2 como interpretador do PHP.<\\/p>\"},{\"role\":\"user\",\"text\":\"manda o arquivo completo com a modifica\\u00e7\\u00e3o e corre\\u00e7\\u00e3o \\nem geral\"},{\"role\":\"model\",\"text\":\"<p><strong>Arquivo de configura\\u00e7\\u00e3o do Nginx para phpMyAdmin com corre\\u00e7\\u00e3o para uso do PHP 8.2:<\\/strong><\\/p>\\n<pre><code class=\\\"language-nginx\\\">server {\\n    listen 80;\\n    listen [::]:80;\\n\\n    root \\/var\\/www\\/html;\\n\\n    index index.php index.html index.htm;\\n\\n    server_name _;\\n\\n    location \\/ {\\n        try_files $uri $uri\\/ =404;\\n    }\\n\\n    location ~ \\\\.php$ {\\n        include snippets\\/fastcgi-php.conf;\\n        fastcgi_pass unix:\\/run\\/php\\/php8.2-fpm.sock;\\n        include fastcgi_params;\\n        fastcgi_param PHP_VERSION 8.2;\\n    }\\n\\n    location \\/phpmyadmin {\\n        root \\/usr\\/share\\/;\\n        index index.php index.html index.htm;\\n\\n        location ~ ^\\/phpmyadmin\\/(.+\\\\.php)$ {\\n            try_files $uri =404;\\n            root \\/usr\\/share\\/;\\n            fastcgi_pass unix:\\/run\\/php\\/php8.2-fpm.sock;\\n            fastcgi_index index.php;\\n            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;\\n            include fastcgi_params;\\n        }\\n    }\\n\\n    location ~ \\/\\\\.ht {\\n        deny all;\\n    }\\n}<\\/code><\\/pre>\\n<p><strong>Modifica\\u00e7\\u00f5es e corre\\u00e7\\u00f5es:<\\/strong><\\/p>\\n<ul>\\n<li>\\n<p>Foi adicionada a linha <code>fastcgi_param PHP_VERSION 8.2;<\\/code> no bloco <code>location ~ \\\\.php$<\\/code>. Isso garante que o phpMyAdmin use especificamente o PHP 8.2 como interpretador do PHP.<\\/p>\\n<\\/li>\\n<li>\\n<p>A diretiva <code>include fastcgi_params;<\\/code> foi adicionada ao bloco <code>location ~ ^\\/phpmyadmin\\/(.+\\\\.php)$<\\/code>. Isso garante que os par\\u00e2metros FastCGI necess\\u00e1rios sejam passados para o phpMyAdmin.<\\/p>\\n<\\/li>\\n<\\/ul>\"},{\"role\":\"user\",\"text\":\"manda o codigo completo\"},{\"role\":\"model\",\"text\":\"Desculpe, houve um erro ao processar sua pergunta.\"},{\"role\":\"user\",\"text\":\"manda o codigo completo\"},{\"role\":\"model\",\"text\":\"Desculpe, houve um erro ao processar sua pergunta.\"},{\"role\":\"user\",\"text\":\"manda todo o codigo com a melhorias\"},{\"role\":\"model\",\"text\":\"Desculpe, houve um erro ao processar sua pergunta.\"}]', 'desativado'),
(55, 2, 985437, '26/04/2024 00:00', '[{\"role\":\"user\",\"text\":\"<script>\\n        document.getElementById(\'loginForm\').addEventListener(\'submit\', function(event) {\\n            event.preventDefault();\\n\\n            const userId = document.getElementById(\'userId\').value;\\n            const password = document.getElementById(\'password\').value;\\n\\n            fetch(\'\\/authenticate\', {\\n                method: \'POST\',\\n                headers: {\\n                    \'Content-Type\': \'application\\/json\',\\n                },\\n                body: JSON.stringify({ userId, password })\\n            })\\n            .then(response => response.json())\\n            .then(data => {\\n                Swal.fire({\\n                    title: data.message,\\n                    icon: data.message === \'Autentica\\u00e7\\u00e3o bem-sucedida\' ? \'success\' : \'error\'\\n                });\\n            })\\n            .catch(error => console.error(\'Erro ao enviar o formul\\u00e1rio:\', error));\\n        });\\n    <\\/script>\\n\\nmodifica para que ser for longado com sucesso enviar para a pagina home\"},{\"role\":\"model\",\"text\":\"<script>\\n        document.getElementById(\'loginForm\').addEventListener(\'submit\', function(event) {\\n            event.preventDefault();\\n\\n            const userId = document.getElementById(\'userId\').value;\\n            const password = document.getElementById(\'password\').value;\\n\\n            fetch(\'\\/authenticate\', {\\n                method: \'POST\',\\n                headers: {\\n                    \'Content-Type\': \'application\\/json\',\\n                },\\n                body: JSON.stringify({ userId, password })\\n            })\\n            .then(response => response.json())\\n            .then(data => {\\n                Swal.fire({\\n                    title: data.message,\\n                    icon: data.message === \'Autentica\\u00e7\\u00e3o bem-sucedida\' ? \'success\' : \'error\'\\n                });\\n\\n                if (data.message === \'Autentica\\u00e7\\u00e3o bem-sucedida\') {\\n                    window.location.href = \'\\/home\';\\n                }\\n            })\\n            .catch(error => console.error(\'Erro ao enviar o formul\\u00e1rio:\', error));\\n        });\\n    <\\/script>\"}]', 'desativado');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `historico`
--
ALTER TABLE `historico`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `historico`
--
ALTER TABLE `historico`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
