CREATE DATABASE Autopassdb;

use Autopassdb;

CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    sobrenome VARCHAR(100) NOT NULL,
    cpf VARCHAR(11) UNIQUE NOT NULL,
    telefone VARCHAR(13),
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    foto VARCHAR(255) NOT NULL,
    tipo numeric(1) DEFAULT 1 NOT NULL,
    status ENUM(
        'ativo',
        'desativado',
        'bloqueado'
    ) DEFAULT 'ativo',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE notificacoes (
    id_notificacao INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    titulo VARCHAR(100),
    mensagem TEXT,
    data_envio DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios (id_usuario)
);

CREATE TABLE veiculos (
    id_veiculo INT AUTO_INCREMENT PRIMARY KEY,
    id_usuarios INT,
    placa VARCHAR(10) UNIQUE,
    marca VARCHAR(50),
    modelo VARCHAR(50),
    cor VARCHAR(50),
    ano INT,
    status ENUM(
        'ativo',
        'inativo',
        'bloqueado'
    ) DEFAULT 'ativo',
    FOREIGN KEY (id_usuarios) REFERENCES usuarios (id_usuario)
);

CREATE TABLE acessos (
    id_acesso INT AUTO_INCREMENT PRIMARY KEY,
    id_veiculo INT NOT NULL,
    id_cancela INT NOT NULL,
    tipo ENUM('entrada', 'saida'),
    data_acesso DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('autorizado', 'negado'),
    FOREIGN KEY (id_veiculo) REFERENCES veiculos (id_veiculo),
    FOREIGN KEY (id_cancela) REFERENCES cancelas (id_cancela)
);

CREATE TABLE rfids (
    id_rfid INT AUTO_INCREMENT PRIMARY KEY,
    codigo_rfid VARCHAR(100) UNIQUE NOT NULL,
    id_veiculo INT,
    status ENUM(
        'ativo',
        'bloqueado',
        'inativo'
    ) DEFAULT 'ativo',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_veiculo) REFERENCES veiculos (id_veiculo)
);

CREATE TABLE cancelas (
    id_cancela INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50),
    localizacao VARCHAR(100),
    status ENUM(
        'aberta',
        'fechada',
        'manutencao'
    ) DEFAULT 'fechada'
);

CREATE TABLE eventos_sistema (
    id_evento INT AUTO_INCREMENT PRIMARY KEY,
    tipo VARCHAR(50),
    descricao TEXT,
    nivel ENUM('info', 'alerta', 'erro') DEFAULT 'info',
    data_evento DATETIME DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE setores (
    id_setor INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(10) UNIQUE NOT NULL,
    ativo BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE vagas (
    id_vaga INT AUTO_INCREMENT PRIMARY KEY,
    id_setor INT NOT NULL,
    codigo VARCHAR(20) UNIQUE,
    nome VARCHAR(50),
    ativo BOOLEAN DEFAULT TRUE,
    pcd BOOLEAN DEFAULT FALSE,
    status ENUM('livre', 'ocupada') DEFAULT 'livre',
    x INT DEFAULT 0,
    y INT DEFAULT 0,
    rotacao INT DEFAULT 0,
    FOREIGN KEY (id_setor) REFERENCES setores (id_setor)
);

CREATE TABLE estacionamento (
    id_estacionamento INT AUTO_INCREMENT PRIMARY KEY,
    id_veiculo INT,
    id_vaga INT,
    id_tarifa INT,
    hora_entrada DATETIME,
    hora_saida DATETIME,
    tempo_total DECIMAL(10, 2),
    valor_total DECIMAL(10, 2),
    status ENUM('ativo', 'finalizado', 'pago') DEFAULT 'ativo',
    FOREIGN KEY (id_veiculo) REFERENCES veiculos (id_veiculo),
    FOREIGN KEY (id_vaga) REFERENCES vagas (id_vaga),
    FOREIGN KEY (id_tarifa) REFERENCES tarifas (id_tarifa)
);

CREATE TABLE tarifas (
    id_tarifa INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50),
    valor_hora DECIMAL(10, 2),
    ativo BOOLEAN DEFAULT TRUE
);

CREATE TABLE pagamentos (
    id_pagamento INT AUTO_INCREMENT PRIMARY KEY,
    id_estacionamento INT,
    valor DECIMAL(10, 2),
    metodo ENUM('pix', 'cartao', 'dinheiro'),
    status ENUM('pendente', 'pago') DEFAULT 'pendente',
    data_pagamento DATETIME,
    FOREIGN KEY (id_estacionamento) REFERENCES estacionamento (id_estacionamento)
);

CREATE TABLE historico (
    id_historico INT AUTO_INCREMENT PRIMARY KEY,
    id_cliente INT,
    acao TEXT,
    data_acao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cliente) REFERENCES usuarios (id_usuario)
);

CREATE TABLE sensores (
    id_sensor INT AUTO_INCREMENT PRIMARY KEY,
    codigo VARCHAR(50),
    tipo VARCHAR(50),
    setor VARCHAR(20),
    status ENUM('online', 'offline') DEFAULT 'online'
);


CREATE TABLE enderecos (
    id_endereco INT AUTO_INCREMENT PRIMARY KEY,

    id_usuario INT NOT NULL,

    cep VARCHAR(9),
    rua VARCHAR(150),
    numero VARCHAR(20),
    complemento VARCHAR(100),
    bairro VARCHAR(100),
    cidade VARCHAR(100),
    estado VARCHAR(2),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (id_usuario)
    REFERENCES usuarios(id_usuario)
    ON DELETE CASCADE
);