<?php
    ;
    $extensao_url= strtolower(pathinfo($_SERVER["REQUEST_URI"], PATHINFO_EXTENSION));
    if(in_array($extensao_url, array("png","jpg","jpeg","gif","bmp","tif"))){
        $image_erro='./img/ERRO IMAGEM NÃO DISPONÍVEL.svg';
        $mime=mime_content_type($image_erro);
        header("Content-Type: $mime");
        echo (file_get_contents('./img/ERRO IMAGEM NÃO DISPONÍVEL.svg'));
    } 
    else{
        $erro="Página não Encontrada";
        include "error.php";
    }    
 ?>