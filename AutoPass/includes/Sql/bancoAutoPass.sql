CREATE DATABASE Autopassdb;

use Autopassdb;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) NOT NULL,
    sobrenome VARCHAR(100) NOT NULL,
    cpf VARCHAR(11) UNIQUE NOT NULL,
    telefone VARCHAR(13),
    email VARCHAR(150) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,

    foto VARCHAR(255)NOT NULL,
    tipo numeric(1) DEFAULT 1 NOT NULL,

    status ENUM('ativo', 'desativado', 'bloqueado') DEFAULT 'ativo',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);