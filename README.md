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
git clone --branch dev-master https://github.com/FiveyTech/Chat-Gemini.git
```

Duplicar o arquivo "dbconfig/.env.example" e renomear para ".env", e adicionar suas informaçoes.<br>
Modifica o arquivo "dbconfig/db.php" adicionar seus dados do banco.<br>

Instalar as dependências do PHP exultando o composer install no diretorio "lib"
```
composer install
```

Suba o arquivo "chatdb.sql" no seu banco de dados.