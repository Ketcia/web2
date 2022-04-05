<?php
    //autoload
    spl_autoload_extensions('.php');
    function classLoader($class){
        $nomeArquivo = $class . '.php';
        $pastas = array('controller','model');
        foreach($pastas as $pasta){
            $arquivo = "{$pasta}/{$nomeArquivo}";
            if(file_exists($arquivo)){
                require_once($arquivo);
            }
        }
    }
    spl_autoload_register('classLoader');
    
    //front controller

    class Aplicacao{
        public static function run(){
            if(isset($_GET["acao"])){
                echo $_GET["acao"];
            }
        }
    }
    Aplicacao::run();
?>