# gmail-message-laravel-api
This api collects data from an e-mail address @gmail and stores the data in a database.

Aqui você encontrará os passos e comandos necessários para executar API de coleta de mensagens do GMAIL com Laravel.

1. Criar banco de dados MySQL.
gmail_message_db

2. Instalar Composer no computador (caso não tenha)

3. Abrir diretório do projeto e executar o comando abaixo no terminal para criar as tabelas do banco de dados.
php artisan migrate 

4. Executar o comando abaixo para executar a API.
php artisan serve

5. Os e-mails estão sendo coletados do e-mail de teste sergiotesteapi@gmail.com
senha: !234Qwer
Para trocar para outro e-mail é preciso autenticar o novo e-mail nos serviços do Google. Isso pode ser feito executando o comando quickstart.php. Após executar o Google irá fornecer uma URL para gerar o token. Após gerar token é preciso voltar o terminal e inserir o novo token. Feito isso você está habilitado a utilizar o Api com o novo e-mail.

6. Rotas
http://localhost:8000/api/gmail/   
{Obter os e-mails}
http://localhost:8000/api/client/   
{Exibir os clientes coletados}
http://localhost:8000/api/message 
{Exibir as mensagens coletadas}
http://localhost:8000/api/client/{id}/message/  
{Exibir as mensagens de um determinado cliente}

