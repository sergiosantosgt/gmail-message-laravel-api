# gmail-message-laravel-api
This api collects data from an e-mail address @gmail and stores the data in a database.

Aqui você encontrará os passos e comandos necessários para executar API de coleta de mensagens do GMAIL com Laravel.

1. Criar banco de dados MySQL.
gmail_message_db
CREATE DATABASE `gmail_message_db` /*!40100 COLLATE 'utf8_general_ci' */

2. Instalar Composer no computador (caso não tenha)

3. Executar o comando abaixo para baixar as dependências do projeto.

composer install

4. Abrir diretório do projeto e executar o comando abaixo no terminal para criar as tabelas do banco de dados.

php artisan migrate 

5. Executar o comando abaixo para executar a API.

php artisan serve

6. Os e-mails estão sendo coletados do e-mail de teste sergiotesteapi@gmail.com
senha: !234qwer
Para trocar para outro e-mail é preciso autenticar o novo e-mail nos serviços do Google. Isso pode ser feito executando o comando 
php quickstart.php no dirétório do projeto
Após executar o Google irá fornecer uma URL para gerar o token. Após gerar token é preciso voltar no terminal e inserir o novo token. Feito isso você está habilitado a utilizar a Api com o novo e-mail.

7. Routes
http://localhost:8000/api/gmail/   
{Obter os e-mails}

http://localhost:8000/api/client/   
{Exibir os clientes coletados}

http://localhost:8000/api/message 
{Exibir as mensagens coletadas}

http://localhost:8000/api/client/{id}/message/  
{Exibir as mensagens de um determinado cliente}

8. Gmail client
O arquivo responsável pela integração com o gmail está em: app/Integrations/gmail-client.php

9. Gmail Controller
O Controlador está em: app/Integrations/gmail-client.php

10. Configuration file
O arquivo de configuração é o .env localizado na raiz do projeto.

