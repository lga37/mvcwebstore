<?php


#se nao esta vazio criamos nossa lista de produtos
echo "<br>";	
if(!empty($produtos)){
    vitrine($produtos);
} else {
    #neste caso nossa consulta nao retornou dados. #echo "<h1>Nenhum registro encontrado</h1>";
    msg("Nenhum registro encontrado","Faça sua busca por palavra-chave, categoria ou codigo numerico do produto");
}
