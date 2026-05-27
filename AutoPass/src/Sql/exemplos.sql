INSERT INTO usuarios (nome,sobrenome,cpf,telefone,email,senha,foto,tipo) VALUES 
('Victor Hernandez','Soares de Almeida','14277907601','19999999999','victornado21a@gmail.com','$2y$10$3I7xK8r1GJ4Y9nTQvL2P5eBvM8N6X1R4A9D7K2L5F8J1S3H6Z0QW','src/img/uploads/victor.png',3),
('Gustavo','De Oliveira','49249021879','19993475751','Gustavodeoliveira3006@gmail.com','$2y$10$8J4P6Q2M9K1L5N7X3A0D6F2H8S4G1R9V2Y5T7W3E1U6I8O4P2','src/img/uploads/gustavo.png',1),
('Gabriel Borges','De Oliveira','40694527882','19993475751','gabrielhdb06@gmail.com','$2y$10$8J4P6Q2M9K1L5N7X3A0D6F2H8S4G1R9V2Y5T7W3E1U6I8O4P2','src/img/uploads/borgues.png',2);


INSERT INTO veiculos (id_usuarios, placa, marca, modelo, cor, ano) VALUES
(1, 'ABC1D23', 'Toyota', 'Corolla', 'Prata', 2020),
(2, 'EFG4H56', 'Honda', 'Civic', 'Preto', 2019),
(3, 'IJK7L89', 'Chevrolet', 'Onix', 'Branco', 2022);

INSERT INTO acessos (id_veiculo, tipo, localizacao, status) VALUES
(1, 'entrada', 'Portaria Principal', 'autorizado'),
(1, 'saida', 'Portaria Principal', 'autorizado'),
(2, 'entrada', 'Portaria Norte', 'autorizado'),
(2, 'saida', 'Portaria Norte', 'autorizado'),
(3, 'entrada', 'Portaria Sul', 'negado'),
(3, 'saida', 'Portaria Sul', 'autorizado');