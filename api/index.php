<?php
/**
 * Gerenciador API.
 *
 * @author Gustavo Jantsch <jantsch@gmail.com>
 *
 */

define("DB_HOST", "127.0.0.1");
define("DB_NAME", "pag_brasil");
define("DB_USER", "root");
define("DB_PWD", "");

require_once "transacao.class.php";

$status = Transacao::ST_ERRO;
$mensagem = "Erro no sistema.";
$xmlstr = "<?xml version='1.0' standalone='yes'?><resposta></resposta>";
$xml = new SimpleXMLElement($xmlstr);

try {

    $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PWD);
    $transacao = new Transacao($conn);

    $action = end(explode("/", $_SERVER[REQUEST_URI]));

    switch ($action) {
        case "adicionar":
            $token = isset($_POST["token"]) ? $_POST["token"] : null;
            $nome = isset($_POST["nome"]) ? $_POST["nome"] : null;
            $email = isset($_POST["email"]) ? $_POST["email"] : null;
            $valor = isset($_POST["valor"]) ? $_POST["valor"] : null;

            if ( $id = $transacao->Adicionar($token, $nome, $email, $valor) ) {
                $xml->addChild("status", Transacao::ST_OK);
                $xml->addChild("mensagem", $transacao->getMensagem());
            } else {
                $xml->addChild("status", Transacao::ST_ERRO);
                $xml->addChild("mensagem", $transacao->getMensagem());

            }

            break;

        case "remover":
            $id = isset($_POST["id"]) ? $_POST["id"] : null;
            $token = isset($_POST["token"]) ? $_POST["token"] : null;
            if ( $transacao->Remover($token, $id) ) {
                $xml->addChild("status", Transacao::ST_OK);
                $xml->addChild("mensagem", $transacao->getMensagem());
            } else {
                $xml->addChild("status", Transacao::ST_ERRO);
                $xml->addChild("mensagem", $transacao->getMensagem());

            }

            break;

        default:
            $xml->addChild("status", Transacao::ST_ERRO);
            $xml->addChild("mensagem", "Recurso não encontrado [$action].");

    }

} catch(Exception $ex) {
    $xml->addChild("status", Transacao::ST_ERRO);
    $xml->addChild("mensagem", $ex->getMessage());

}

header("Content-type: text/xml");
echo $xml->asXML();
exit;
