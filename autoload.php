<?php
 
spl_autoload_register(function($nomeClasse){
  $diretorioBase = dirname(__DIR__,1);
  $src = $diretorioBase . DIRECTORY_SEPARATOR;
  $caminho = $src . str_replace('\\',DIRECTORY_SEPARATOR,$nomeClasse). '.php';
  if(file_exists($caminho)) {
    require_once $caminho;
  }
});