<?php
class Form
{
  private $message = "";
  private $error = "";
  public function __construct(){
    Transaction::open();
  }
  public function controller()
  {
    $form = new Template('restrict/view/form.html');
    $form->set("id", "");
    $form->set("marca", "");
    $form->set("motor", "");
    $form->set("modelo", "");
    $this->message = $form->saida();
  }
  public function salvar()
  {
    if (isset($_POST["marca"]) && isset($_POST["modelo"]) && isset($_POST["motor"])) {
      try {
        $conexao = Transaction::get();
        $veiculo = new Crud("veiculo");
        $marca = $conexao->quote($_POST["marca"]);
        $modelo = $conexao->quote($_POST["modelo"]);
        $motor = $conexao->quote($_POST["motor"]);
        if (empty($_POST["id"])) {
          $veiculo->insert(
            "marca, modelo, motor",
            "$marca, $modelo, $motor"
          );
        } else {
          $id = $conexao->quote($_POST["id"]);
          $veiculo->update(
            "marca = $marca, modelo = $modelo, motor = $motor",
            "id = $id"
          );
        }
        $this->message = $veiculo->getMessage();
        $this->error = $veiculo->getError();
      } catch (Exception $e) {
        echo $e->getMessage();
        
      }
    }
  }
  public function editar()
  {
    if (isset($_GET["id"])) {
      try {
        $conexao = Transaction::get();
        $id = $conexao->quote($_GET["id"]);
        $veiculo = new Crud("veiculo");
        $resultado = $veiculo->select("*", "id = $id");
        if (!$veiculo->getError()) {
          $form = new Template("restrict/view/form.html");
          foreach ($resultado[0] as $cod => $valor) {
            $form->set($cod, $valor);
          }
          $this->message = $form->saida();
        } else {
          $this->message = $veiculo->getMessage();
          $this->error = true;
        }
      } catch (Exception $e) {
        $this->message = $e->getMessage();
        $this->error = true;
      }
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