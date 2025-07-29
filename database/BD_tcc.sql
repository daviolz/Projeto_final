CREATE DATABASE Projeto_final;
USE Projeto_final;

CREATE TABLE Usuario (
    Cod_usuario INT PRIMARY KEY AUTO_INCREMENT,
    Login VARCHAR(80),
    Senha VARCHAR(80),
    Nivel INT
);

INSERT INTO Usuario (Login, Senha, Nivel) 
VALUES 
    ('gerente@email.com', '123', 1),
    ('cozinha@email.com', '123', 2);

CREATE TABLE IF NOT EXISTS Comanda(
    Cod_comanda INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Data_hora Timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Valor_total Decimal(10,2) NOT NULL DEFAULT 0.00,
    Senha INT,
    Pagamento ENUM('A pagar', 'paga') NOT NULL DEFAULT 'A pagar',
    Status ENUM ('esperando', 'preparando', 'pronto' , 'cancelada') NOT NULL DEFAULT 'esperando'
);

CREATE TABLE IF NOT EXISTS Produto(
    Cod_produto INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Nome_produto VARCHAR(100),
    Tipo_produto ENUM('bebida', 'salgado', 'doce','combo') NOT NULL,
    Descricao_produto TEXT DEFAULT NULL,
    Imagem_produto VARCHAR(255) DEFAULT NULL
    
);

CREATE TABLE IF NOT EXISTS Produto_Variacao(
    Cod_variacao INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Cod_produto INT NOT NULL,
    Nome_variacao VARCHAR(100) NOT NULL,
    Preco DECIMAL(10,2) NOT NULL,
    Status ENUM('disponivel', 'indisponivel') NOT NULL DEFAULT 'disponivel',
    FOREIGN KEY (Cod_produto) REFERENCES Produto(Cod_produto)
);

INSERT INTO Produto (Nome_produto, Tipo_produto, Descricao_produto, Imagem_produto) VALUES
('Pastel', 'salgado', 'Um delicioso pastel frito recheado à sua escolha', 'img/pastel.png'),
('Coxinha', 'salgado', 'Coxinha quente e crocante com diversas opções de recheio','img/coxinha.png'),
('Hamburguer', 'salgado', 'Hambúrguer assado com pão macio.','img/hamburguer.png'),
('Empada', 'salgado','Empada recheada, envolta em uma massa crocante de dar água na boca!', 'img/empada.png'),
('Enrolado de Salsicha',  'salgado', 'O popular enroladinho de salsicha com masse leve e com um formato único!', 'img/enrolado_salsicha.png'),
('Pão de Queijo', 'salgado', 'Pão de queijo saindo direto do forno, a marca registrada do Mineiro sô!', 'img/pao_queijo.png'),
('Quibe', 'salgado', 'Quibe frito com temperos especiais, crocante por fora e macio por dentro', 'img/quibe.png'),
('Esfirra', 'salgado', 'Esfirra Triangular assada com diversos sabores', 'img/esfirra.png'),
('Refrigerante 350ml', 'bebida', 'Refrigerante gelado de 350ml, perfeito para acompanhar seu lanche','img/refrigerante.png'),
('Suco Natural 300ml', 'bebida', 'Suco natural feito na hora.', 'img/suco_natural.png'),
('Energético', 'bebida', 'Bebida energética para dar um gás em seus estudos.', 'img/energetico.png'),
('Vitamina 500ml', 'bebida', 'Vitamina de frutas frescas.', 'img/vitamina_500ml.png'),
('Água Mineral 500ml',  'bebida', 'Água mineral gelada.', 'img/agua_mineral_500ml.png'),
('Café', 'bebida', 'Café preto e passado na hora.', 'img/cafe.png'),
('Caldo de Cana 500ml',  'bebida', 'Caldo de Cana Doce com uma pitada de limão.', 'img/caldo_cana.png'),
('Achocolatado 200ml', 'bebida', 'Achocolatado Doce e Suave.', 'img/achocolatado.png'),
('Pudim Fatia', 'doce', 'Fatia de Pudim Tradicional de Leite Condensado.', 'img/pudim.png'),
('Bala Fini 90g', 'doce', 'Pacote de bala Fini, para adoçar seu lanche', 'img/bala_fini_90g.png'),
('Brownie',  'doce', 'Brownie de chocolate macio.', 'img/brownie.png'),
('Beijinho de rato', 'doce', 'Doce de coco tradicional, com um formato único.', 'img/beijinho_de_rato.png'),
('Bolo de Pote', 'doce', 'Bolo de pote com diversos sabores.', 'img/bolo_de_pote.png'),
('Brigadeiro', 'doce', 'Brigadeiro de chocolate tradicional, Irrecusável.', 'img/brigadeiro.png'),
('Docile Pastile',  'doce', 'Pastilhas doces Docile.', 'img/docile_pastile.png'),
('Chiclets',  'doce', 'Chicletes variados.', 'img/chiclets.png'),
('Combo Esfirrado', 'combo', 'Combo especial que vem com esfirra e bebida.', 'img/combo_esfirrado.png'),
('Combo Amostradinho',  'combo', 'Combo variado com salgados e doces. Para aqueles que fazem aquelas combinações incomuns', 'img/combo_amostradinho.png'),
('Combo Salgas com Refresco','combo', 'Um clássico combo para um lanche leve.', 'img/combo_salgas_com_refresco.png'),
('Combo Asiático Hidratado', 'combo', 'Combo que acompanha Pastel e Caldo de Cana.', 'img/combo_asiatico_hidratado.png'),
('Combo Mineirinho', 'combo', 'Combo mineiro clássico da tarde, pão de queijo e café preto.', 'img/combo_mineirinho.png'),
('Combo Comes & Beb''s', 'combo', 'Combo completo para a família, do jeitinho para dar aquele comes e bebes.', 'img/combo_comes_e_bebs.png');

CREATE TABLE IF NOT EXISTS Pedido(
    Cod_pedido INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    Cod_comanda INT NOT NULL,
    Cod_variacao INT NOT NULL,
    Qte INT NOT NULL,
    Data_hora TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    Valor_pedido DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (Cod_comanda) REFERENCES Comanda(Cod_comanda),
    FOREIGN KEY (Cod_variacao) REFERENCES Produto_Variacao(Cod_variacao)
);


INSERT INTO Produto_Variacao (Cod_produto, Nome_variacao, Preco) VALUES
(1, 'Pastel de Carne', 3.50),
(1, 'Pastel de Queijo', 3.50),
(2, 'Coxinha de Frango', 6.50),
(2, 'Coxinha de Frango c/ Catupiry', 7.00),
(2, 'Coxinha de Frango c/Bacon' , 7.50),
(2, 'Coxinha de Frango c/Cheddar', 7.00),
(2, 'Coxinha de Frango c/ Requeijão', 7.50),
(3, 'Hamburguer Assado', 10.00),
(4, 'Empada de Frango', 6.50),
(4, 'Empada de Palmito', 7.00),
(4, 'Empada de Frango c/Catupiry', 7.00),
(4, 'Empada de Frango c/Bacon', 7.50),
(4, 'Empada de Frango c/Cheddar', 7.00),
(4, 'Empada de Carne Seca c/Catupiry', 8.00),
(5, 'Enrolado de Salsicha Tradicional', 4.50),
(6, 'Pão de Queijo Tradicional', 3.00),
(6, 'Pão de Queijo com Recheio', 4.00),
(6, 'Pão de Queijo com Bacon', 4.50),
(6, 'Pão de Queijo com Catupiry', 4.00),
(6, 'Pão de Queijo com Cheddar', 4.50),
(6, 'Pão de Queijo com Presunto e Queijo', 5.00),
(7, 'Quibe', 6.50),
(8, 'Esfirra de Carne Moída', 10.00),
(8, 'Esfirra de Frango', 10.00),
(9, 'Refrigerante Coca-Cola', 5.00), 
(9, 'Refrigerante Guaraná', 5.00),
(9, 'Refrigerante Sprite', 5.00), 
(9, 'Refrigerante Coca-Cola Zero', 5.00),  
(9, 'Refrigerante Sprite', 5.00), 
(9, 'Refrigerante Fanta Laranja', 5.00), 
(9, 'Refrigerante Fanta Uva', 5.00),
(9, 'Refrigerante Pepsi', 5.00), 
(9, 'Refrigerante Sukita', 5.00), 
(9, 'Refrigerante Guaraná Jesus', 5.00), 
(10, 'Suco de Laranja', 5.50),
(10, 'Suco de Limão', 5.50),
(10, 'Suco de Abacaxi', 5.50),
(10, 'Suco de Maracujá', 5.50),
(10, 'Suco de Uva', 5.50),
(11, 'Energético Red Bull', 10.00),
(11, 'Energético Monster', 10.00),
(12, 'Vitamina de Banana', 10.00),
(12, 'Vitamina de Morango', 10.00),
(12, 'Vitamina de Abacate', 10.00),
(13, 'Água Mineral com Gás', 3.00),
(13, 'Água Mineral sem Gás', 3.00),
(14, 'Café Preto Tradicional', 1.50),
(14, 'Café com Leite', 2.00),
(15, 'Caldo de Cana Tradicional', 5.00),
(16, 'Achocolatado Tradicional', 4.00),
(17, 'Pudim Tradicional', 7.00),
(18, 'Bala Fini Beijinho', 8.00),
(18, 'Bala Fini Banananiha', 8.00),
(18, 'Bala Fini Tubes', 8.00),
(18, 'Bala Fini Dentadura', 8.00),
(18, 'Bala Fini Sortida', 8.00),
(19, 'Brownie de Chocolate', 6.00),
(20, 'Beijinho de Rato Tradicional', 5.00),
(21, 'Bolo de Pote de Chocolate', 8.00),
(22, 'Brigadeiro Tradicional', 3.50),
(23, 'Docile Pastile Sortida', 1.00),
(24, 'Chiclets Sortido', 1.00),
(25, 'Combo Esfirrado Completo', 13.50),
(26, 'Combo Amostradinho Variado', 18.99),
(27, 'Combo Salgas com Refresco Completo', 20.00),
(28, 'Combo Asiático Hidratado Completo', 10.00),
(29, 'Combo Mineirinho Clássico', 5.98),
(30, 'Combo Comes & Beb''s Completo', 16.90);

