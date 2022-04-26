<?php
class Tabela
{
    private $message = "";

  public function __construct(){
      Transaction::open();
  }
  public function controller()
  {
    Transaction::get();
    $veiculo = new Crud("veiculo");
    $resultado = $veiculo->select();
    $tabela = new Template("view/tabela.html");
    if(is_array($resultado)>0){
      $tabela->set("linha", $resultado);
      $this->message = $tabela->saida();
    }

  }

  public function remover()
  {
    try {
      $conexao = Transaction::get();
      $id = $conexao->quote($_GET["id"]);
      $veiculo = new Crud("veiculo");
      $resultado = $veiculo->delete("id = $id");
    } catch (Exception $e) {
      echo $e->getMessage();
    }
  }
  public function getMessage(){
    return $this->message;
  }
  public function __destruct()
  {
    Transaction::close();
  }
}