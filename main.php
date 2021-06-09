<?php
require_once "MysqlStreamDriver.php"; // chama os ajustes técnicos
// conexão do banco que irá receber os dados
$conexao = mysqli_connect('hostname', 'username', 'password', 'database', 'port');
//conexão ssh
$ssh = ssh2_connect('host', 'port');  // ip e porta ssh
ssh2_auth_password($ssh, 'username', 'password'); // usuario e senha ssh
// conexão banco pdv
$stream = ssh2_tunnel($ssh, 'host', 'port');   // ip e porta do banco
$link = new MysqlStreamDriver($stream, 'username', 'password', 'database'); // usuario, senha e nome do banco

//SCRIPT DE ATUALIZAÇÃO
mysqli_query($conexao, "SET FOREIGN_KEY_CHECKS = 0");

mysqli_query($conexao, "TRUNCATE TABLE score_rep.testes");

//consulta
$consulta = $link->query("SELECT id, cpf, name, score, cdata, created_at, updated_at FROM persons");

while ($row = $consulta->fetch_assoc()) {
    // insere na tabela
    mysqli_query($conexao,"INSERT INTO score_rep.persons VALUES ('".$row['id']."','".$row['cpf']."','".$row['name']."','".$row['score']."','".$row['cdata']."','".$row['created_at']."','".$row['updated_at']."')");
}

mysqli_close($conexao);
