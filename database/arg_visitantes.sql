CREATE TABLE arg_visita_grupal (
    visita_grupal_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    folio_visita INT NOT NULL,
    nombre_completo VARCHAR(255) NOT NULL,
    ine VARCHAR(50) NOT NULL,
    nss VARCHAR(50) NOT NULL,
    archivo_ine VARCHAR(255) NOT NULL,
    archivo_nss VARCHAR(255) NOT NULL,
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (visita_grupal_id),
    INDEX idx_folio_visita (folio_visita)

) ENGINE=InnoDB
DEFAULT CHARSET=latin1
COLLATE=latin1_swedish_ci;