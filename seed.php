<?php 


$cn = new mysqli("localhost", "root", "123", "stone");
if(mysqli_connect_errno()){
	echo mysqli_connect_error();
}
# a partir do mysql5.7 tem que criar usuario add

sql("CREATE USER 'web'@'localhost' IDENTIFIED BY '123';");
sql("GRANT SELECT,INSERT,UPDATE,DELETE ON *.* TO 'web'@'localhost';");
sql("FLUSH PRIVILEGES;");

sql("CREATE DATABASE `stone`;");
sql("USE `stone`;");


sql("DROP TABLE IF EXISTS `categorias`;");
sql("DROP TABLE IF EXISTS `produtos`;");
sql("DROP TABLE IF EXISTS `clientes`;");
sql("DROP TABLE IF EXISTS `pedidos`;");
sql("DROP TABLE IF EXISTS `itens`;");


$categs = <<<CATEGS
CREATE TABLE `categorias` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
);
CATEGS;

$prods = <<<PRODS
CREATE TABLE `produtos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `categ_id` int(11) DEFAULT NULL,
  `nome` varchar(20) NOT NULL,
  `preco` float NOT NULL,
  `peso` float NOT NULL,
  `foto` varchar(20) DEFAULT NULL,
  `estoque` int(11) DEFAULT NULL,
  `prazo` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
PRODS;

$clientes = <<<CLIENTES
CREATE TABLE `clientes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(20) NOT NULL,
  `email` varchar(80) NOT NULL,
  `senha` char(60) NOT NULL,
  `endereco` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
);
CLIENTES;


$pedidos = <<<PED
CREATE TABLE `pedidos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `datahora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `cli_id` int(11) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `frete` float DEFAULT NULL,
  PRIMARY KEY (`id`)
);
PED;


$itens = <<<ITENS
CREATE TABLE `itens` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `prod_id` int(11) DEFAULT NULL,
  `ped_id` int(11) DEFAULT NULL,
  `qtd` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
);
ITENS;

sql($categs);
sql($prods);
sql($clientes);
sql($pedidos);
sql($itens);



sql("insert into categorias (nome) values ('roupas');");
sql("insert into produtos (nome,categ_id,preco,prazo,peso,estoque,foto) values ('geladeira',1,122.33,'5-15',1,2,'');");
sql("insert into clientes (nome,email,senha,endereco) values ('Gustavo','a@a.com',sha1('123'),'constante r');");



function sql($sql){
	global $cn;
	if($cn->query($sql)){
		echo "<p>OK: $sql</p>";
	} else {
		echo "<br>Erro: ".$cn->error;
	}

}

$cn->close();