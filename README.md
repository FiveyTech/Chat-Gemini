## CHAT GEMINI

## Pré-requisitos

O projeiro usa:

 * PHP 8.2 ou superior
 * Composer
 * BANCO DE DADOS MYSQL/MARIADB
 * google/apiclient
 * erusev/parsedown
 * vlucas/phpdotenv
 * predis/predis

## Instalação

Para instalar o Chat gemini, siga estas etapas:


clone o repositorio.

```
git clone --branch <branch_name> <repository_url>
```

Duplicar o arquivo "dbconfig/.env.example" e renomear para ".env", e adicionar suas informaçoes.<br>
Modifica o arquivo "dbconfig/db.php" adicionar seus dados do banco.<br>

Instalar as dependências do PHP
```
composer install
```

Suba o arquivo "chatdb.sql" no seu banco de dados.