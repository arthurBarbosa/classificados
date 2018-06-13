<?php 

session_start();

global $pdo;

try{
	$pdo = new PDO("mysql:dbname=classificado", "root", "1234");
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
	echo "Erro ao conectar com o banco de dados." .$e->getMessage();
	exit;
}


 ?>