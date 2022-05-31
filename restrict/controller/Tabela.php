<?php
class Tabela
{
    private $message = "";
    private $error = "";

  public function __construct(){
      Transaction::open();
  }
  public function controller()
  {
    try {
      Transaction::get();
    $veiculo = new Crud("veiculo");
    $resultado = $veiculo->select();
    $tabela = new Template("restrict/view/tabela.html");
    if(is_array($resultado)>0){
      $tabela->set("linha", $resultado);
      $this->message = $tabela->saida();
    }else{
      $this->message = $veiculo->getMessage();
        $this->error = $veiculo->getError();
    }
    } catch (\Throwable $th) {
      $this->message = $e->getMessage();
      $this->error = true;
    }
    
  }

  public function remover()
  {
    if(isset($_GET["id"])){
      try {
        $conexao = Transaction::get();
        $id = $conexao->quote($_GET["id"]);
        $veiculo = new Crud("veiculo");
        $resultado = $veiculo->delete("id = $id");
        $this->message = $veiculo->getMessage();
        $this->error = $veiculo->getError();
      } catch (Exception $e) {
        $this->message = $e->getMessage();
        $this->error = true;
      }
    }else{
      $this->message = "Faltando parâmetro!";
      $this->error = true;
    }
    
  }
  public function getMessage()
  {
    if (is_string($this->error)) {
      return $this->message;
    } else {
      $msg = new Template("restrict/view/msg.html");
      if ($this->error) {
        $msg->set("cor", "danger");
      } else {
        $msg->set("cor", "success");
      }
      $msg->set("msg", $this->message);
      $msg->set("uri", "?class=Tabela");
      return $msg->saida();
    }
  }
  public function __destruct()
  {
    Transaction::close();
  }
}