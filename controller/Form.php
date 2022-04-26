<?php
class Form
{
  private $message = "";
  public function __construct(){
    Transaction::open();
  }
  public function controller()
  {
    $form = new Template('view/form.html');
    $this->message = $form->saida();
  }
  public function salvar(){
    if(isset($_POST["marca"]) && isset($_POST["modelo"]) && isset($_POST["motor"])){
      try {
        $conexao = Transaction::get();
        $veiculo = new Crud("veiculo");
        $marca = $conexao->quote($_POST["marca"]);
        $modelo = $conexao->quote($_POST["modelo"]);
        $motor = $conexao->quote($_POST["motor"]);
        $resultado = $veiculo->insert("marca, modelo, motor", "$marca, $modelo, $motor");
      } catch (Exception $e) {
        echo $e->getMessage();
      }
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