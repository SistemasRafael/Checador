DELIMITER $$
DROP FUNCTION IF EXISTS cursos_vigentes_prov$$
CREATE DEFINER=`root`@`localhost` FUNCTION `cursos_vigentes_prov`(`prov_id` INT) RETURNS varchar(250) CHARSET utf8 COLLATE utf8_general_ci
BEGIN

DECLARE cursos VARCHAR(250);
DECLARE curso1 VARCHAR(150);
DECLARE curso2 VARCHAR(150);
DECLARE curso3 VARCHAR(150);

SET curso1 = IFNULL((SELECT IFNULL(CONCAT(cu.nombre, ' Expira: ', es.expira, ' '), ' ')
FROM 
arg_evaluacion_stickers es
LEFT JOIN arg_cursos AS cu
	ON es.curso_id = cu.curso_id
WHERE 
es.usuario_prov = prov_id
AND es.activo = 1
AND es.curso_id = 1
), ' ');

SET curso2 = IFNULL((SELECT IFNULL(CONCAT(cu.nombre, ' Expira: ', es.expira, ' '), ' ')
FROM 
arg_evaluacion_stickers es
LEFT JOIN arg_cursos AS cu
	ON es.curso_id = cu.curso_id
WHERE 
es.usuario_prov = prov_id
AND es.activo = 1
AND es.curso_id = 2), '');

SET curso3 = IFNULL((SELECT IFNULL(CONCAT(cu.nombre, ' Expira: ', es.expira), ' ') 
FROM 
arg_evaluacion_stickers es
LEFT JOIN arg_cursos AS cu
	ON es.curso_id = cu.curso_id
WHERE 
es.usuario_prov = prov_id
AND es.activo = 1
AND es.curso_id = 3), ' ');
   
   SET cursos = CONCAT(curso1, curso2, curso3);
   
   RETURN cursos;
END$$
DELIMITER ;

DELIMITER $$
DROP FUNCTION IF EXISTS total_inscritos$$
CREATE DEFINER=`root`@`localhost` FUNCTION `total_inscritos`(`cal_id_sel` INT) RETURNS int(150)
BEGIN
    DECLARE usuarios_inscritos SMALLINT;
    
    SET usuarios_inscritos = (SELECT COUNT(*) AS total
                             FROM
                                 arg_calendario_cursos
                             WHERE
                                  cal_id = cal_id_sel);

    RETURN usuarios_inscritos;
END$$
DELIMITER ;

DELIMITER $$
DROP FUNCTION IF EXISTS usuarios_minas$$
CREATE DEFINER=`root`@`localhost` FUNCTION `usuarios_minas`(`u_id_emp` INT) RETURNS varchar(150) CHARSET utf8 COLLATE utf8_general_ci
BEGIN
DECLARE minas_asignadas VARCHAR(150);
DECLARE existe SMALLINT;

SET existe = (SELECT COUNT(*) FROM arg_usuarios_directivas WHERE u_id = u_id_emp AND directiva_id = 1);

IF (existe <> 0) THEN
BEGIN
	SET minas_asignadas = (SELECT
                             (CASE
                                   WHEN ud.valor = '0' THEN 'LC,SA,EC'
                                   WHEN ud.valor = '1' THEN 'LC'
                                   WHEN ud.valor = '2' THEN 'SA'
                              	   WHEN ud.valor = '3' THEN 'EC'	
                                   WHEN ud.valor = '1,2' THEN 'LC, SA'
                                   WHEN ud.valor = '2,3' THEN 'SA,EC'
                                END) AS minas
                       FROM
                            arg_usuarios_directivas ud
                        WHERE
                            u_id = u_id_emp
                            AND ud.directiva_id = 1);
END;
ELSE
BEGIN
    SET minas_asignadas = 'SIN ASIGNAR';
END;
END IF;

RETURN minas_asignadas;
END$$
DELIMITER ;
