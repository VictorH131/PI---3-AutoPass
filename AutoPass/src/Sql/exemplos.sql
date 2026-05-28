INSERT INTO usuarios
(nome,sobrenome,cpf,telefone,email,senha,foto,tipo)
VALUES
('Victor Hernandez','Soares de Almeida','14277907601','19999999999','victornado21a@gmail.com','$2y$10$3I7xK8r1GJ4Y9nTQvL2P5eBvM8N6X1R4A9D7K2L5F8J1S3H6Z0QW','src/img/uploads/victor.png',3),
('Gustavo','De Oliveira','49249021879','19993475751','Gustavodeoliveira3006@gmail.com','$2y$10$8J4P6Q2M9K1L5N7X3A0D6F2H8S4G1R9V2Y5T7W3E1U6I8O4P2','src/img/uploads/gustavo.png',1),
('Gabriel Borges','De Oliveira','49249021279','19993475751','gabriel@gmail.com','$2y$10$8J4P6Q2M9K1L5N7X3A0D6F2H8S4G1R9V2Y5T7W3E1U6I8O4P2','src/img/uploads/borgues.png',2),
('Lucas','Silva','12345678901','19911111111','lucas@email.com','$2y$10$abc','src/img/uploads/default.png',1),
('Mariana','Souza','12345678902','19911111112','mariana@email.com','$2y$10$abc','src/img/uploads/default.png',2),
('Pedro','Oliveira','12345678903','19911111113','pedro@email.com','$2y$10$abc','src/img/uploads/default.png',1),
('Fernanda','Costa','12345678904','19911111114','fernanda@email.com','$2y$10$abc','src/img/uploads/default.png',1),
('Juliana','Almeida','12345678905','19911111115','juliana@email.com','$2y$10$abc','src/img/uploads/default.png',2),
('Ricardo','Lima','12345678906','19911111116','ricardo@email.com','$2y$10$abc','src/img/uploads/default.png',1),
('Camila','Mendes','12345678907','19911111117','camila@email.com','$2y$10$abc','src/img/uploads/default.png',1);


INSERT INTO veiculos
(id_usuarios,placa,marca,modelo,cor,ano)
VALUES
(1,'ABC1D23','Toyota','Corolla','Prata',2020),
(2,'EFG4H56','Renault ','Kwid ','Preto',2019),
(3,'IJK7L89','Chevrolet','Onix','Branco',2022),
(4,'AAA1B11','Fiat','Uno','Branco',2015),
(5,'BBB2C22','Volkswagen','Gol','Prata',2017),
(6,'CCC3D33','Hyundai','HB20','Azul',2020),
(7,'DDD4E44','Renault','Sandero','Cinza',2018),
(8,'EEE5F55','Ford','Ka','Preto',2019),
(9,'FFF6G66','Toyota','Yaris','Branco',2022),
(10,'GGG7H77','Honda','Fit','Vermelho',2021);



INSERT INTO rfids
(codigo_rfid,id_veiculo,status)
VALUES
('RFID001',1,'ativo'),
('RFID002',2,'ativo'),
('RFID003',3,'bloqueado'),
('RFID004',4,'ativo'),
('RFID005',5,'ativo'),
('RFID006',6,'ativo'),
('RFID007',7,'ativo'),
('RFID008',8,'ativo'),
('RFID009',9,'ativo'),
('RFID010',10,'ativo');



INSERT INTO cancelas
(nome,localizacao,status)
VALUES
('Cancela Principal','Entrada','fechada'),
('Cancela Norte','Setor Norte','aberta'),
('Cancela Sul','Setor Sul','fechada');



INSERT INTO vagas
(codigo,setor,nome,pcd,status)
VALUES
('A01','A','Vaga A01',FALSE,'ocupada'),
('A02','A','Vaga A02',FALSE,'livre'),
('A03','A','Vaga A03',TRUE,'livre'),
('B01','B','Vaga B01',FALSE,'ocupada'),
('B02','B','Vaga B02',FALSE,'livre'),
('B03','B','Vaga B03',FALSE,'reservada');



INSERT INTO tarifas
(nome,valor_hora)
VALUES
('Padrão',8.50),
('Mensal',5.00),
('VIP',15.00);



INSERT INTO estacionamento
(id_veiculo,id_vaga,id_tarifa,hora_entrada,status)
VALUES
(1,1,1,NOW(),'ativo'),
(2,4,1,NOW(),'ativo');

INSERT INTO acessos
(id_veiculo,id_cancela,tipo,status)
VALUES
(1,1,'entrada','autorizado'),
(1,1,'saida','autorizado');

INSERT INTO acessos
(id_veiculo,id_cancela,tipo,status)
VALUES
(1,1,'entrada','autorizado'),
(1,1,'saida','autorizado'),
(1,2,'entrada','autorizado'),
(1,2,'saida','autorizado'),
(2,2,'entrada','autorizado'),
(2,2,'saida','autorizado'),
(2,1,'entrada','autorizado'),
(3,3,'entrada','negado'),
(3,1,'entrada','autorizado'),
(3,1,'saida','autorizado'),
(4,1,'entrada','autorizado'),
(4,1,'saida','autorizado'),
(4,3,'entrada','autorizado'),
(5,2,'entrada','autorizado'),
(5,2,'saida','autorizado'),
(6,3,'entrada','autorizado'),
(6,3,'saida','autorizado'),
(7,1,'entrada','autorizado'),
(7,1,'saida','autorizado'),
(8,2,'entrada','autorizado'),
(8,2,'saida','autorizado'),
(9,3,'entrada','autorizado'),
(9,3,'saida','autorizado'),
(10,1,'entrada','autorizado'),
(10,1,'saida','autorizado');




INSERT INTO sensores
(codigo,tipo,setor,status)
VALUES
('S001','presenca','A','online'),
('S002','ultrassonico','A','online'),
('S003','camera','Entrada Principal','online'),
('S004','camera','Saída Principal','online'),
('S005','presenca','B','offline');



INSERT INTO eventos_sistema
(tipo,descricao,nivel)
VALUES
('entrada','Veículo entrou','info'),
('rfid','RFID inválido detectado','alerta'),
('sensor','Sensor desconectado','erro');



INSERT INTO notificacoes
(id_usuario,titulo,mensagem)
VALUES
(1,'Entrada registrada','Seu veículo entrou no estacionamento'),
(2,'Pagamento','Pagamento confirmado'),
(3,'Alerta','Tentativa de acesso negada');



INSERT INTO pagamentos
(id_estacionamento,valor,metodo,status,data_pagamento)
VALUES
(1,25.50,'pix','pago',NOW()),
(2,15.00,'cartao','pendente',NULL);



INSERT INTO historico
(id_cliente,acao)
VALUES
(1,'Usuário criado'),
(2,'Veículo cadastrado'),
(3,'Acesso negado');