<h1>Aplicacao desenvolvida para a PI de Desenvolvimento OO - Clinica</h1> <br/>

<p>Requisitos:</p>
<p>Docker e docker compose;</p>
<p>Apache/PHP;</p>
<p>Alterar e renomear o arquivo docker-compose.example.yml;</p>
<p>Caso seja alterado alguma informacao de acesso ao banco de dados, sera necessario editar o arquivo EntityMangerCreator.php;</p>
<p>Alterar e renomear o arquivo .env.example e inserir a url no qual voce colocou o projeto.</p>

<hr/>

<p>Startar o apache (Pode-se utilizar laragon ou xampp);</p>
<p>ir ate a pasta raiz do projeto e digitar o seguinte comando: docker-compose -f docker-compose.yml up -d</p>
<p>Rodar os seguintes comandos para gerar as proxies das entidades e criar o banco de dados: </p>
```sh
php bin/doctrine orm:clear-cache:metadata 
php bin/doctrine orm:generate-proxies 
php bin/doctrine orm:schema-tool:create 
```