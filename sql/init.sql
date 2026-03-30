CREATE DATABASE IF NOT EXISTS perfumeria_db;
USE perfumeria_db;

CREATE TABLE IF NOT EXISTS perfumistas (
    id_perfumista INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    especialidad VARCHAR(255),
    bio TEXT,
    premios TEXT,
    inicial CHAR(1),
    url_foto VARCHAR(255),
    fragantica_url VARCHAR(255),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS perfiles (
    id_perfil VARCHAR(36) PRIMARY KEY,
    nombre_usuario VARCHAR(50) UNIQUE NOT NULL,
    contrasena VARCHAR(255) NOT NULL,
    avatar_url VARCHAR(255),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS fragancias (
    id_fragancia INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(150) NOT NULL,
    marca VARCHAR(100) NOT NULL,
    id_perfumista INT,
    genero VARCHAR(50),
    concentracion VARCHAR(50),
    estacion VARCHAR(100),
    hora_dia VARCHAR(50),
    familia VARCHAR(100),
    notas_salida TEXT,
    notas_corazon TEXT,
    notas_fondo TEXT,
    clasificacion DECIMAL(3,2),
    recuento_revisiones INT DEFAULT 0,
    url_imagen VARCHAR(255),
    anio INT,
    descripcion TEXT,
    partidos TEXT,
    fragantica_url VARCHAR(255),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_fragancia_perfumista FOREIGN KEY (id_perfumista) 
        REFERENCES perfumistas(id_perfumista) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS reseñas (
    id_reseña INT AUTO_INCREMENT PRIMARY KEY,
    id_fragancia INT NOT NULL,
    id_usuario VARCHAR(36) NOT NULL,
    nombre_usuario VARCHAR(50),
    calificacion_general INT,
    duracion_calificacion INT,
    clasificacion_aldea INT,
    valor_calificacion INT,
    comentario TEXT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reseña_fragancia FOREIGN KEY (id_fragancia) 
        REFERENCES fragancias(id_fragancia) ON DELETE CASCADE,
    CONSTRAINT fk_reseña_usuario FOREIGN KEY (id_usuario) 
        REFERENCES perfiles(id_perfil) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS favoritos (
    id_favorito INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario VARCHAR(36) NOT NULL,
    id_fragancia INT NOT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_fav_usuario FOREIGN KEY (id_usuario) 
        REFERENCES perfiles(id_perfil) ON DELETE CASCADE,
    CONSTRAINT fk_fav_fragancia FOREIGN KEY (id_fragancia) 
        REFERENCES fragancias(id_fragancia) ON DELETE CASCADE
);

INSERT INTO perfumistas (id_perfumista, nombre, especialidad, bio) VALUES
(1, 'Alberto Morillas', 'Perfumista maestro', 'Creador de fragancias icónicas como Acqua di Giò y CK One'),
(2, 'Christine Nagel', 'Directora de creación', 'Especialista en notas florales y amaderadas'),
(3, 'Jacques Cavallier', 'Maestro perfumista', 'Creador de fragancias para Louis Vuitton'),
(4, 'Olivier Polge', 'Perfumista principal', 'Conocido por su trabajo en Chanel y fragancias elegantes'),
(5, 'Dominique Ropion', 'Perfumista independiente', 'Conocido por fragancias intensas y complejas');

INSERT INTO fragancias (nombre, marca, id_perfumista, genero, concentracion, familia, notas_salida, notas_corazon, notas_fondo, clasificacion, recuento_revisiones, anio, descripcion) VALUES

('Chanel N°5', 'Chanel', 4, 'Femenino', 'Eau de Parfum', 'Floral Aldehídica', 'Aldehídos, neroli, bergamota', 'Jazmín, rosa, lirio de los valles', 'Vainilla, sándalo, almizcle', 4.5, 1250, 1921, 'El perfume icónico de Chanel, un clásico atemporal con un bouquet floral sofisticado.'),
('La Vie Est Belle', 'Lancôme', 1, 'Femenino', 'Eau de Parfum', 'Floral Dulce', 'Cassis, pera', 'Iris, jazmín, flor de azahar', 'Praliné, vainilla, pachulí, haba tonka', 4.4, 1100, 2012, 'Un himno a la felicidad y la belleza de la vida.'),
('Black Orchid', 'Tom Ford', 5, 'Femenino', 'Eau de Parfum', 'Floral Oriental', 'Trufa negra, bergamota, ciruela', 'Orquídea negra, especias, flor de loto', 'Pachulí, incienso, vainilla, chocolate', 4.6, 850, 2006, 'Una fragancia luxosa y sensual con un corazón de orquídea negra.'),
('Flowerbomb', 'Viktor & Rolf', 3, 'Femenino', 'Eau de Parfum', 'Floral Oriental', 'Bergamota, té verde, osmanthus', 'Jazmín, rosa, fresia', 'Pachulí, almizcle, vainilla', 4.5, 900, 2005, 'Una explosión floral que envuelve los sentidos en un bouquet seductor.'),
('Sweet Tooth', 'Sabrina Carpenter', 2, 'Femenino', 'Eau de Parfum', 'Gourmand Dulce', 'Vainilla, caramelo, bergamota', 'Crema batida, praliné, jazmín', 'Almizcle, ámbar, sándalo', 4.3, 450, 2023, 'Una fragancia dulce y tentadora que evoca los postres favoritos de Sabrina.'),
('Coconut Peach', 'Sabrina Carpenter', 2, 'Femenino', 'Eau de Parfum', 'Frutal Tropical', 'Durazno, coco, piña', 'Flor de tiaré, jazmín, neroli', 'Vainilla, almizcle, madera de cedro', 4.4, 520, 2024, 'Una explosión tropical de durazno y coco que captura la esencia veraniega.'),
('Crystal Lake', 'Sabrina Carpenter', 2, 'Femenino', 'Eau de Parfum', 'Floral Acuática', 'Bergamota, pera, notas acuáticas', 'Peonía, lirio de los valles, jazmín', 'Almizcle blanco, ámbar, sándalo', 4.2, 380, 2024, 'Una fragancia fresca y cristalina que evoca lagos serenos y momentos de tranquilidad.'),
('Midnight Bloom', 'Sabrina Carpenter', 2, 'Femenino', 'Eau de Parfum', 'Floral Oriental', 'Ciruela, bergamota, pimienta rosa', 'Jazmín, rosa, orquídea', 'Pachulí, vainilla, almizcle', 4.5, 600, 2024, 'Una fragancia misteriosa y seductora que florece en la oscuridad de la noche.'),
('Velvet Rose', 'Sabrina Carpenter', 2, 'Femenino', 'Eau de Parfum', 'Floral Amaderada', 'Rosa, frambuesa, bergamota', 'Jazmín, rosa, violeta', 'Sándalo, almizcle, ámbar', 4.3, 450, 2024, 'Una fragancia lujosa y aterciopelada que envuelve los sentidos en un abrazo floral.'),
('Golden Hour', 'Sabrina Carpenter', 2, 'Femenino', 'Eau de Parfum', 'Frutal Floral', 'Mandarina, durazno, bergamota', 'Jazmín, rosa, flor de azahar', 'Almizcle, ámbar, madera de cedro', 4.4, 500, 2024, 'Una fragancia radiante que captura la magia de la hora dorada con notas frutales y florales.'), 
('Sauvage', 'Dior', 2, 'Masculino', 'Eau de Toilette', 'Amaderada Aromática', 'Pimienta de Sichuan, bergamota', 'Lavanda, pimienta, vetiver, pachulí', 'Ambroxan, cedro, lábdano', 4.7, 1500, 2015, 'Una composición poderosa y fresca inspirada en paisajes desérticos al atardecer.'),
('Bleu de Chanel', 'Chanel', 3, 'Masculino', 'Eau de Parfum', 'Amaderada Aromática', 'Cítricos, menta, jengibre', 'Jazmín, incienso, vetiver', 'Sándalo, pachulí, lábdano, ámbar', 4.5, 1200, 2010, 'La elegancia masculina de Chanel en una fragancia fresca y sofisticada.'),
('Acqua di Giò', 'Giorgio Armani', 1, 'Masculino', 'Eau de Toilette', 'Acuática Aromática', 'Bergamota, neroli, mandarina', 'Jazmín, rosas, romero', 'Pachulí, almizcle, cedro', 4.4, 1350, 1996, 'Una fragancia fresca que evoca el mar Mediterráneo.'),
('Dior Homme Intense', 'Dior', 4, 'Masculino', 'Eau de Parfum', 'Amaderada Floral', 'Lavanda, bergamota', 'Iris, ámbar, cacao', 'Vetiver, vainilla, cedro', 4.6, 920, 2007, 'Una interpretación intensa y sofisticada de la masculinidad.'),
('Fahrenheit', 'Dior', 4, 'Masculino', 'Eau de Toilette', 'Amaderada Aromática', 'Bergamota, limón, mandarina', 'Nuez moscada, jazmín, cedro', 'Sándalo, pachulí, cuero', 4.3, 1100, 1988, 'Una fragancia icónica que combina notas frescas y cálidas con un toque de misterio.'),
('CK One', 'Calvin Klein', 1, 'Unisex', 'Eau de Toilette', 'Cítrica Aromática', 'Piña, bergamota, mandarina', 'Violeta, jazmín, nuez moscada', 'Vainilla, ámbar, sándalo, almizcle', 4.2, 1400, 1994, 'La primera fragancia unisex que revolucionó la industria.'),
('Tom Ford Oud Wood', 'Tom Ford', 3, 'Unisex', 'Eau de Parfum', 'Amaderada Oriental', 'Pimienta rosa, cardamomo', 'Oud, sándalo, vetiver', 'Ámbar, vainilla, haba tonka', 4.8, 780, 2007, 'Una exquisita combinación de maderas preciosas del mundo.'),
('Maison Francis Kurkdjian Baccarat Rouge 540', 'Maison Francis Kurkdjian', 5, 'Unisex', 'Extrait de Parfum', 'Ambarada Floral', 'Azafrán, jazmín', 'Almizcle, madera de cedro', 'Ámbar gris, resinas', 4.9, 650, 2015, 'Una fragancia luminosa y sofisticada creada para celebrar el cristal Baccarat.'),
('Le Labo Santal 33', 'Le Labo', 2, 'Unisex', 'Eau de Parfum', 'Amaderada Cuero', 'Cardamomo, iris, violeta', 'Ambrox, aldehydes', 'Sándalo, cedro, papiro, cuero', 4.5, 890, 2011, 'El aroma del oeste americano, evocando el desierto y la libertad.');
