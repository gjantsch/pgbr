-- by Gustavo Jantsch <jantsch@gmail.com>
--
-- NOTA: apesar do modelo apresentar os nomes das tabelas em letras maiúsculas
-- optei por letras minusculas como constava na query que popula o banco de dados
-- por eu considerar que o código fica mais legível dessa maneira.
--

CREATE DATABASE IF NOT EXISTS pag_brasil
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE pag_brasil;

-- remove tabelas se existirem
-- não alterar a ordem dos DROPs
DROP TABLE IF EXISTS tb_orders;
DROP TABLE IF EXISTS tb_merchants;

CREATE TABLE tb_merchants (
  xid INTEGER AUTO_INCREMENT PRIMARY KEY,
  xnome VARCHAR(50),
  xtoken VARCHAR(32)
) ENGINE=INNODB;

CREATE TABLE tb_orders (
    xid INTEGER AUTO_INCREMENT PRIMARY KEY,
    xid_merchant INTEGER NOT NULL,
    xnome VARCHAR(50),
    xemail VARCHAR(30),
    xvalor NUMERIC(6,2),
    FOREIGN KEY (xid_merchant) REFERENCES tb_merchants(xid)
) ENGINE=INNODB;

-- popula tabelas
INSERT INTO tb_merchants (xid, xnome, xtoken) VALUES
  ('1', 'Merchant 1', '152c51b4b2b57d7091c4c2185d85cf2f'),
  ('2', 'Merchant 2', 'd598ff54a88c1a356a48a6b0936b2dc3'),
  ('3', 'Merchant 3', 'c3c0ba8554a20d3f206faffcd76e5641');