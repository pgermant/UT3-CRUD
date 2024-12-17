CREATE TABLE vuelos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_vuelo VARCHAR(10) NOT NULL,
    aeropuerto_origen VARCHAR(100) NOT NULL,
    aeropuerto_destino VARCHAR(100) NOT NULL,
    fecha_hora_salida DATETIME NOT NULL,
    fecha_hora_llegada DATETIME NOT NULL,
    capacidad INT NOT NULL,
    precio DECIMAL(10, 2) NOT NULL
);

INSERT INTO vuelos (numero_vuelo, aeropuerto_origen, aeropuerto_destino, fecha_hora_salida, fecha_hora_llegada, capacidad, precio)
VALUES 
('BN101', 'Tenerife Norte', 'Gran Canaria', '2024-12-15 08:00:00', '2024-12-15 08:45:00', 150, 80.00),
('BN202', 'Gran Canaria', 'La Palma', '2024-12-16 10:30:00', '2024-12-16 11:15:00', 120, 95.50),
('BN303', 'La Palma', 'Tenerife Norte', '2024-12-17 14:00:00', '2024-12-17 14:45:00', 100, 75.00);