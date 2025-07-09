Create Database Cozinha;

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
    
CREATE TABLE Produto(
    Cod_produto INT PRIMARY KEY AUTO_INCREMENT,
    Nome_produto VARCHAR(100),
    Cod_img VARCHAR(100),
    Descricao_produto TEXT
);

CREATE TABLE Variacao(
    Cod_variacao INT PRIMARY KEY AUTO_INCREMENT,
    Cod_produto INT,
    Nome_variacao VARCHAR(100),
    Preco DECIMAL(10, 2),
    FOREIGN KEY (Cod_produto) REFERENCES Produto(Cod_produto)
);


CREATE  TABLE Conta(
    Cod_conta INT PRIMARY KEY AUTO_INCREMENT,
    Nome_cliente VARCHAR(100),
    Data_hora DATETIME,
    Status ENUM('preparando', 'pronta') DEFAULT 'preparando',
    Senha INT
);

CREATE TABLE Pedido(
    Cod_pedido INT PRIMARY KEY AUTO_INCREMENT,
    Cod_conta INT,
    Cod_produto INT,
    Cod_variacao INT,
    Qtd INT,
    Data_hora DATETIME,
    FOREIGN KEY (Cod_conta) REFERENCES Conta(Cod_conta),
    FOREIGN KEY (Cod_produto) REFERENCES Produto(Cod_produto),
    FOREIGN KEY (Cod_variacao) REFERENCES Variacao(Cod_variacao)
);

INSERT INTO Conta (Nome_cliente, Data_hora, Status, Senha)
VALUES 
    ('Cliente 1', NOW(), 'preparando', 1),
    ('Cliente 2', NOW(), 'pronta', 2);

INSERT INTO Pedido (Cod_conta, Cod_produto, Cod_variacao, Qtd, Data_hora)
VALUES 
    (1, 1, 2, 2, NOW()),
    (1, 2, 3, 1, NOW()),
    (1, 3, 4, 1, NOW()),
    (2, 1, 2, 1, NOW()),
    (2, 3, 4, 1, NOW());

