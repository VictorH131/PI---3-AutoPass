<?php
    // Configurações do banco de dados
        $host = 'localhost';       // Servidor do banco
        $db   = 'Autopassdb';        // Nome do banco
        $user = 'root';            // Usuário do banco
        $pass = '';                // Senha do banco
        $charset = 'utf8mb4';      // Conjunto de caracteres

        // String de conexão (DSN)
        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        // Opções de configuração do PDO
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lança exceções em caso de erro
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Retorna arrays associativos
            PDO::ATTR_EMULATE_PREPARES   => false,                  // Desativa preparação de statements emulada
        ];

        try {
            // Cria a conexão PDO
            $conn  = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            // Caso dê erro, exibe a mensagem
            die("Erro na conexão com o banco de dados: " . $e->getMessage());
        }
?>
