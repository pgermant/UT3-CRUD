CREATE TABLE reservas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT,
    vuelo_id INT,
    fecha_hora_reserva DATETIME,
    fecha_hora_salida DATETIME,
    aeropuerto_origen VARCHAR(100),
    aeropuerto_destino VARCHAR(100),
    tipo_tarifa VARCHAR(50),
    estado ENUM('activa', 'inactiva', 'pagada')
);

ALTER TABLE reservas
ADD CONSTRAINT fk_usuario
FOREIGN KEY (usuario_id) REFERENCES usuarios(id);

ALTER TABLE reservas
ADD CONSTRAINT fk_vuelo
FOREIGN KEY (vuelo_id) REFERENCES vuelos(id);