<?php

/**
 * Created by PhpStorm.
 * @author Gustavo Jantsch <jantsch@gmail.com>
 *
 */
class Transacao
{

    CONST ST_OK = "sucesso";
    CONST ST_ERRO = "erro";

    private $db;
    private $status;
    private $mensagem;

    public function __construct($db)
    {

        if ($db instanceof PDO) {
            // testa conexão com banco de dados
            $st = $db->prepare("SELECT 1 FROM tb_merchants LIMIT 1");
            $st->execute();
            $data = $st->fetchAll(PDO::FETCH_ASSOC);
            if (!is_array($data)) {
                throw new Exception("Erro ao acessar tabelas.");
            }
            // banco ok
            $this->db = $db;

        } else {
            throw new Exception("Banco de dados inválido.");
        }

    }

    /**
     * Obtem mensagem gerada.
     *
     * @return mixed
     */
    public function getMensagem() {
        return $this->mensagem;
    }

    /**
     * Adiciona transação.
     *
     * @param $id
     * @param $nome
     * @param $email
     * @param $valor
     * @return bool
     */
    public function Adicionar($token, $nome, $email, $valor)
    {
        $token = trim(filter_var($token, FILTER_SANITIZE_STRING));
        $nome = trim(filter_var($nome, FILTER_SANITIZE_STRING));
        $email = trim(filter_var($email, FILTER_SANITIZE_EMAIL));
        $valor = (float) $valor;
        $this->status = self::ST_ERRO;

        if (empty($token) || $valor <= 0 || empty($nome) || empty($email)) {
            $this->mensagem = "Valores inválidos.";

        }

        $st = $this->db->prepare("SELECT xid FROM tb_merchants WHERE xtoken=?");
        $st->execute(array($token));

        if ($data = $st->fetchAll(PDO::FETCH_ASSOC)) {
            $xid_merchant = $data[0]["xid"];
            $st = $this->db->prepare("INSERT INTO tb_orders (xid_merchant, xnome, xemail, xvalor) VALUES (?, ?, ?, ?)");

            if ($st->execute(array($xid_merchant, $nome, $email, $valor))) {
                $id = $this->db->lastInsertId();
                $this->status = self::ST_OK;
                $this->mensagem = "Transação concluída com id $id.";

            } else {
                $this->mensagem = "Não foi possível inserir dados.";
            }

        } else {
            $this->mensagem = "Token inválido.";
        }

        return $this->status == self::ST_OK;
    }

    public function Remover($token, $id)
    {
        $id = (int)$id;
        $token = trim(filter_var($token, FILTER_SANITIZE_STRING));

        if ($id <= 0 || empty($token)) {
            $this->mensagem = "Valores inválidos.";

        } else {
            $st = $this->db->prepare("SELECT xid FROM tb_merchants WHERE xtoken=?");
            $st->execute(array($token));
            if ($data = $st->fetchAll(PDO::FETCH_ASSOC)) {
                $xid_merchant = $data[0]["xid"];

                $st = $this->db->prepare("DELETE FROM tb_orders WHERE xid=? AND xid_merchant=?");

                if ($st->execute(array($id, $xid_merchant))) {
                    $this->status = self::ST_OK;
                    $this->mensagem = "Transação $id removida.";

                } else {
                    $this->mensagem = "Não foi possível remover transação.";
                }

            } else {
                $this->mensagem = "Token inválido.";
            }
        }

        return $this->status == self::ST_OK;

    }

}