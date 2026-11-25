DELIMITER $$
DROP PROCEDURE IF EXISTS Visor_personas_uid$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `Visor_personas_uid`(IN `tipo` SMALLINT)
SELECT
     org.nombre AS empresa
    ,arg_usuarios.u_id
    ,arg_usuarios.nombre
    ,arg_usuarios.email
    ,seg.imss as seguro
    ,ine.imss as ine
    ,lic.imss as licencia
FROM
	arg_usuarios
    LEFT JOIN usuarios_doc seg
    	ON arg_usuarios.u_id = seg.u_id
        AND seg.tipo_id = 1
    LEFT JOIN  usuarios_doc ine
    	ON arg_usuarios.u_id = ine.u_id
        AND ine.tipo_id = 2
    LEFT JOIN  usuarios_doc lic
    	ON arg_usuarios.u_id = lic.u_id
        AND lic.tipo_id = 3
    LEFT JOIN arg_organizaciones org
    	ON org.org_id = arg_usuarios.org_id
WHERE
	arg_usuarios.division = 'proveedor'
    AND arg_usuarios.u_id = (CASE WHEN tipo = 0 THEN arg_usuarios.u_id ELSE tipo END)
ORDER BY
	arg_usuarios.nombre$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS arg_curso_finalizar$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `arg_curso_finalizar`(IN `trn_id` BIGINT, IN `u_id` INT)
INSERT INTO arg_usuarios_cursos(trn_id, u_id, tipo_induccion, curso_id, fecha)
SELECT
	cu.trn_id, cu.u_id, cu.tipo_induccion, cs.curso_id, cc.fecha
FROM
	arg_calendario_usuarios cu
    LEFT JOIN arg_calendario_cursos cc
    	ON cu.cal_id = cc.cal_id
    LEFT JOIN arg_cursos cs
    	ON cs.tipo_induccion = cu.tipo_induccion
WHERE
	cu.trn_id = trn_id$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS arg_cursos_inscritos$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `arg_cursos_inscritos`(IN `cal_id` BIGINT)
SELECT COUNT(cu.cal_id)
FROM
	arg_calendario_usuarios cu  
WHERE
 	cu.cal_id = cal_id$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS arg_entradasProcesar$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `arg_entradasProcesar`(IN `trn_id` INT, IN `fecha_ini` VARCHAR(50), IN `fecha_fin` VARCHAR(50), IN `unidad` SMALLINT, IN `usuario` INT, IN `usuario_atie` INT, IN `usuario_resp` INT, IN `veh_id` INT, IN `comentarios` VARCHAR(250))
    SQL SECURITY INVOKER
BEGIN 
DECLARE folioentrada VARCHAR(250);
DECLARE fechaactual VARCHAR(150);
SELECT fechaactual = '21/09/2020';
SELECT folioentrada = (SELECT serie FROM arg_empr_unidades WHERE unidad_id = unidad); 
SELECT folioentrada = 'lc00002';
INSERT INTO arg_entradas (trn_id, folio, fecha, fecha_inicio, fecha_final, unidad_id, usuario_id, usuario_id_atie, usuario_id_resp, veh_id, comentario)
VALUES (trn_id, folioentrada, fechaactual, fecha_ini, fecha_fin, unidad, usuario, usuario_atie, usuario_resp, veh_id, comentario);
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS arg_generar_calendarios$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `arg_generar_calendarios`(IN `unidad_id` INT, IN `year_sel` INT, IN `month_sel` INT, IN `day_sel` VARCHAR(15))
BEGIN
DECLARE fecha_inicial VARCHAR(12);
DECLARE fecha_final VARCHAR(12);
DECLARE fecha_act VARCHAR(12);
DECLARE i INT;
DECLARE id INT;
DECLARE dia_fin INT;
DECLARE dia_let VARCHAR(25);

SET i = 1;
SET id = 10;
SET fecha_inicial = CONCAT(year_sel, '-', month_sel, '-01');
SET fecha_final = LAST_DAY(fecha_inicial);

SELECT DAY(fecha_final) INTO dia_fin;
SET fecha_act = fecha_inicial;

WHILE (i<=dia_fin) 
DO
SELECT DATE_FORMAT(fecha_act, '%W') INTO dia_let;
    IF (dia_let = day_sel) THEN    	
		set id = id+1;
        insert INTO arg_calendario_cursos (id, cal_id,nombre,dia, mes, ejercicio, fecha,max_part)
        values (id, id, dia_let, i, month_sel, year_sel, fecha_act, 10);
    END IF;
    set fecha_act = date_add(fecha_act, interval 1 day);	
	set i = i+1;
END WHILE;
    

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS arg_prc_entradaProcesar$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `arg_prc_entradaProcesar`(IN `trn_id` INT)
    DETERMINISTIC
BEGIN
DECLARE folioentrada VARCHAR(250);
SELECT folioentrada = (SELECT serie FROM arg_empr_unidades WHERE unidad_id = 1); 
SELECT folioentrada = CONCAT(folioentrada,'0001');
SELECT folioentrada;
INSERT INTO arg_entradas(trn_id, folio, fecha, fecha_inicial, fecha_final, unidad_id, usuario_id, usuario_id_atie, usuario_id_resp, veh_id, comentarios)
VALUES (2, 'lc000001', '22/09/2020','24/09/2020', '25/09/2020', 1, 1, 2, 3, 1, 'este');
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS arg_prc_llenarTablaNotificaciones$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `arg_prc_llenarTablaNotificaciones`()
INSERT INTO  `arg_bitacora_notificaciones` (org_id, empresa, u_id, proveedor, email, enviado)
SELECT 
	 og.org_id
    ,og.nombre
    ,au.u_id
    ,au.nombre
    ,'danira.romero@argonautgold.com'
    ,0
FROM
	arg_organizaciones og
    LEFT JOIN arg_usuarios au
    	ON og.org_id = au.org_id
WHERE
    sisdo_pro = 0
    AND au.u_id > 0$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS arg_registrar_user$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `arg_registrar_user`(IN `u_id` INT, IN `email` VARCHAR(150), IN `username` VARCHAR(150), IN `passw` VARCHAR(250), IN `correo` VARCHAR(100), IN `org_id` INT, IN `activo` INT, IN `division` VARCHAR(50), IN `licencia` VARCHAR(50), IN `fecha_lic` VARCHAR(50), IN `ine` VARCHAR(50), IN `fecha_ine` VARCHAR(50), IN `imss` VARCHAR(50), IN `fecha_imss` VARCHAR(50))
BEGIN
INSERT INTO arg_usuarios (u_id, codigo, nombre, clave, email, org_id, activo, division, codigo_reset) 
VALUES(u_id, email, username, passw, email,org_id,activo,division,'');          
INSERT INTO arg_usuarios_documentos (u_id, tipo_id, nombre, fecha_expira, path) 
VALUES(u_id, 3, licencia,fecha_lic, ''); 
INSERT INTO arg_usuarios_documentos (u_id, tipo_id, nombre, fecha_expira, path) 
VALUES(u_id, 2, ine,fecha_ine, ''); 
INSERT INTO arg_usuarios_documentos (u_id, tipo_id, nombre, fecha_expira, path) 
VALUES(u_id, 1, imss,fecha_imss, ''); 
                                            
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS arg_vigencia_procesar$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `arg_vigencia_procesar`(IN `trn_id` INT, IN `curso_id` INT, IN `u_id` INT)
UPDATE arg_usuarios_cursos 
	SET folio_sti = 44554
    , expira = DATE_ADD(fecha, INTERVAL 6 MONTH)
WHERE 
	arg_usuarios_cursos.trn_id = trn_id
    AND arg_usuarios_cursos.curso_id = curso_id$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS expirar_stickers$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `expirar_stickers`(IN `activos` INT)
BEGIN

CREATE TEMPORARY TABLE fechas_stickers(
      sticker VARCHAR(50),
      proveedor INT,
      curso_id SMALLINT,
      fecha_expira DATE DEFAULT NULL,
      fecha_expira_n DATE DEFAULT NULL,
      fecha_actual DATE DEFAULT NULL,
      diferencia_dias INT DEFAULT 0
    );

INSERT INTO fechas_stickers (sticker, proveedor, curso_id, fecha_expira, fecha_expira_n, fecha_actual)
SELECT 
    sticker, usuario_prov, curso_id, STR_TO_DATE(expira, "%d-%m-%Y"), CAST(expira AS DATE), (CURRENT_DATE())
FROM
    arg_evaluacion_stickers 
WHERE
   activo = 1 
   AND expira IS NOT NULL
   AND expira <> '';

UPDATE fechas_stickers
  SET diferencia_dias = TIMESTAMPDIFF(DAY, fecha_expira, fecha_actual);

/*
SELECT 
     ps.activo, ps.usuario_prov, ps.curso_id, ps.sticker, ps.expira, fs.diferencia_dias
FROM
   fechas_stickers fs
   LEFT JOIN arg_evaluacion_stickers ps
   	  ON fs.proveedor = ps.usuario_prov
      AND fs.sticker = ps.sticker
      AND fs.curso_id = ps.curso_id
WHERE
   ps.activo = 1
   AND fs.diferencia_dias >= 0;
*/

UPDATE fechas_stickers fs
   LEFT JOIN arg_evaluacion_stickers ps
   	  ON fs.proveedor = ps.usuario_prov
      AND fs.sticker = ps.sticker
      AND fs.curso_id = ps.curso_id      
   SET  ps.activo = 0
WHERE
   ps.activo = 1
   AND fs.diferencia_dias >= 0;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS finalizar_visitas_iniciadas$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `finalizar_visitas_iniciadas`()
BEGIN
  DECLARE det_done BOOLEAN DEFAULT FALSE;
  DECLARE trn_id_visita INTEGER;
  
DECLARE cursor2 CURSOR FOR
       SELECT     
          ed.trn_id_rel
       FROM 
          arg_entradas_dias ed
          LEFT JOIN arg_entradas en
	         ON ed.trn_id_rel = en.trn_id
       WHERE 
           ed.estado_id = 4 
           AND ed.fecha = CURDATE();
  

          DECLARE CONTINUE HANDLER FOR NOT FOUND SET det_done = TRUE;

          OPEN cursor2;
          bucle2: loop
          FETCH FROM cursor2 INTO trn_id_visita;

           IF det_done THEN
                 CLOSE cursor2;                         
                 LEAVE bucle2;
           END IF;               
           
           INSERT INTO arg_entradas_estados (trn_id, estado_id, fecha, comentario, u_id)
            VALUES (trn_id_visita, 6, now(), '', 1);
           
           UPDATE arg_entradas_dias 
             SET hora_fin = CURTIME()
                ,estado_id = 6
                ,usuario_id = 1
          WHERE
             trn_id_rel = trn_id_visita 
             AND fecha = CURDATE()
             AND estado_id = 4;
          
      
      END loop bucle2;
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS generar_calendarios$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `generar_calendarios`(IN `unidad_id_mina` INT)
BEGIN
DECLARE fecha_inicial DATETIME;
DECLARE fecha_final DATETIME;
DECLARE fecha_actual DATETIME;
DECLARE dia SMALLINT;

CREATE TEMPORARY TABLE calendario_dias(
       unidad_mina SMALLINT
      ,fecha DATETIME
      ,cupo SMALLINT
      ,inscritos SMALLINT
    );
    
SET fecha_inicial = CURDATE();
SET fecha_final = DATE_ADD(fecha_inicial, INTERVAL 30 DAY);

WHILE (fecha_inicial <= fecha_final) DO
BEGIN 
   SET  dia = DAYOFWEEK(fecha_inicial);
    
    IF (dia = 2) THEN
        BEGIN
            INSERT INTO calendario_dias(unidad_mina, fecha, cupo, inscritos)
                                VALUES (unidad_id_mina, fecha_inicial, 20, 0);

        END;
    END IF;
    
    SET fecha_inicial = DATE_ADD(fecha_inicial, INTERVAL 1 DAY);
    
END;
END WHILE;

SELECT * FROM calendario_dias;

END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS test$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `test`(IN act_id INT)
SELECT * FROM `arg_actividad`$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS visor_calendario$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `visor_calendario`(IN `mes` INT)
SELECT cc.nombre, cc.fecha, cc.cal_id, ui.total
FROM
	arg_calendario_cursos cc
    LEFT JOIN usuarios_inscritos ui
    	ON cc.cal_id = ui.cal_id
WHERE
 	cc.mes = mes
Order by cc.fecha$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS visor_califica$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `visor_califica`(IN `u_id_emp` INT, IN `u_id` INT)
SELECT (CASE WHEN uc.tipo_induccion = 0 THEN 'Inducción de Seguridad Básica' ELSE 'Inducción de Seguridad Completa' END) AS induccion, uc.curso_id
,cu.nombre, uc.fecha, REPLACE(uc.expira, '-', '') AS expira, uc.calificacion, uc.folio_sti
FROM arg_usuarios_cursos uc
LEFT JOIN arg_cursos cu
	ON cu.curso_id = uc.curso_id
WHERE
	uc.u_id = u_id$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS visor_cursos$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `visor_cursos`(IN `tipo` INT)
    NO SQL
SELECT curso_id, (CASE WHEN (curso_id = 1) THEN 
                      CASE WHEN (tipo_induccion = 0) THEN 'Inducción de Seguridad Básica LC' ELSE 'Inducción de Seguridad LC Completa' END 
                  WHEN (curso_id = 2) THEN 
                      CASE WHEN (tipo_induccion = 0) THEN 'Inducción de Seguridad Básica SA' ELSE 'Inducción de Seguridad SA Completa' END 
                  WHEN (curso_id = 3) THEN 
                      CASE WHEN (tipo_induccion = 0) THEN 'Inducción de Seguridad Básica EC' ELSE 'Inducción de Seguridad EC Completa' END
                 END) AS induccion,nombre, CONCAT(arg_cursos.vigencia,' Meses') AS vigencia FROM arg_cursos WHERE tipo_induccion = tipo$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS visor_empresas$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `visor_empresas`(IN `tipo` SMALLINT)
SELECT
     o.org_id
    ,o.nombre AS empresa
    ,o.rfc
    ,CONCAT(o.calle, ' ', o.num_exterior) AS calle
    ,o.colonia
    ,o.localidad
    ,o.codigo_postal
    ,ci.ciudad
    ,us.nombre AS usuario_administrador
    ,o.sisdo_pro
FROM
	arg_organizaciones o
    LEFT JOIN arg_ciudades AS ci
    	ON ci.ciudad_id = o.ciudad_id
    LEFT JOIN arg_usuarios us
        ON us.u_id = o.usuario_administrador
WHERE
	o.org_id = (CASE WHEN tipo = 0 THEN o.org_id ELSE tipo END)
    
ORDER BY
	o.nombre$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS visor_herramientas$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `visor_herramientas`(IN `tipo` INT)
    NO SQL
SELECT 
	org.nombre as empresa
    ,he.nombre
    ,he.marca
    ,he.modelo
    ,he.serie
    ,he.activo
    ,he.herr_id
FROM
	arg_herramientas AS he
    LEFT JOIN arg_organizaciones AS org
    	ON he.org_id = org.org_id
WHERE
	he.org_id = (CASE WHEN tipo = 0 THEN he.org_id ELSE tipo END)
ORDER BY
	org.nombre, he.nombre$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS visor_inscritos$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `visor_inscritos`(IN `cal_id` INT, IN `u_id` INT)
SELECT
	 trn_id
    ,o.nombre AS empresa
    ,u.nombre
    ,(CASE WHEN ui.tipo_induccion = 0 THEN 'Inducción de seguridad básica' ELSE 'Inducción de seguridad completa' END) AS tipo_induccion
    ,ca.fecha AS fecha_curso
    ,ui.fecha AS fecha_solicitud
    ,e.estado
    ,(CASE WHEN ui.estado_id = 1 THEN 2 WHEN ui.estado_id = 2 THEN 6 WHEN ui.estado_id = 6 THEN 999 WHEN ui.estado_id = 7 THEN 999 END) AS estado_id_1
    ,(CASE WHEN ui.estado_id = 1 THEN 3 END) AS estado_id_2
    ,(CASE WHEN ui.estado_id = 1 THEN 'Aceptar' WHEN ui.estado_id = 2 THEN 'Finalizar' WHEN ui.estado_id = 6 THEN 'Calificar' WHEN ui.estado_id = 7 THEN 'Calificaciones' END) AS estado_n_1
    ,(CASE WHEN ui.estado_id = 1 THEN 'Rechazar' END) AS estado_n_2
FROM
	arg_calendario_usuarios ui
    LEFT JOIN arg_usuarios u
    	ON ui.u_id = u.u_id
    LEFT JOIN arg_calendario_cursos ca
    	ON ca.cal_id = ui.cal_id
    LEFT JOIN arg_organizaciones o
    	ON o.org_id = u.org_id
    LEFT JOIN arg_estados e
    	ON e.estado_id = ui.estado_id
WHERE
	ui.cal_id = cal_id
    AND ui.u_id = (CASE WHEN u_id = 0 THEN ui.u_id ELSE u_id END)$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS visor_personas$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `visor_personas`(IN `tipo` SMALLINT)
SELECT
     org.nombre AS empresa
    ,arg_usuarios.u_id
    ,arg_usuarios.nombre
    ,arg_usuarios.email
    ,seg.imss as seguro
    ,ine.imss as ine
    ,lic.imss as licencia
    ,arg_usuarios.administrador
    ,cursos_vigentes_prov(arg_usuarios.u_id) AS cursos_vigentes 
FROM
	arg_usuarios
    LEFT JOIN usuarios_doc seg
    	ON arg_usuarios.u_id = seg.u_id
        AND seg.tipo_id = 1
    LEFT JOIN  usuarios_doc ine
    	ON arg_usuarios.u_id = ine.u_id
        AND ine.tipo_id = 2
    LEFT JOIN  usuarios_doc lic
    	ON arg_usuarios.u_id = lic.u_id
        AND lic.tipo_id = 3
    LEFT JOIN arg_organizaciones org
    	ON org.org_id = arg_usuarios.org_id
WHERE
	arg_usuarios.division = 'proveedor'
    AND arg_usuarios.org_id = (CASE WHEN tipo = 0 THEN arg_usuarios.org_id ELSE tipo END)
ORDER BY
	arg_usuarios.nombre$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS visor_usuarios_inscritos$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `visor_usuarios_inscritos`(IN `cal_id` BIGINT)
SELECT
	 o.nombre AS empresa
    ,u.nombre
    ,(CASE WHEN ui.tipo_induccion = 0 THEN 'Inducción de seguridad básica' ELSE 'Inducción de seguridad completa' END) AS tipo_induccion
    ,ca.fecha AS fecha_curso
    ,ui.fecha AS fecha_solicitud
    
FROM
	arg_calendario_usuarios ui
    LEFT JOIN arg_usuarios u
    	ON ui.u_id = u.u_id
    LEFT JOIN arg_calendario_cursos ca
    	ON ca.cal_id = ui.cal_id
    LEFT JOIN arg_organizaciones o
    	ON o.org_id = u.org_id
WHERE
	ui.cal_id = cal_id$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS visor_vehiculos$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `visor_vehiculos`(IN `tipo` SMALLINT)
SELECT 
	 org.nombre as empresa
    ,vh.veh_id
    ,vh.placas
    ,vh.marca
    ,vh.modelo
    ,vh.color
    ,vh.poliza
    ,vh.path
    ,vh.fecha_expira
    ,vh.activo
FROM
	arg_vehiculos AS vh
    LEFT JOIN arg_organizaciones AS org
    	ON vh.org_id = org.org_id
WHERE
	vh.org_id = (CASE WHEN tipo = 0 THEN vh.org_id ELSE tipo END)
ORDER BY
	org.nombre, vh.placas$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS visor_visitas$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `visor_visitas`(IN `usuario` INT, IN `fecha_ini` DATE, IN `fecha_fin` DATE, IN `empleado` INT, IN `unidad` INT, IN `estado` INT)
BEGIN

DECLARE org_administrador SMALLINT;

if (empleado = 100) THEN 
    BEGIN
        SET org_administrador = (SELECT org_id FROM arg_usuarios uf WHERE uf.u_id = usuario);
    

    SELECT
         arg_organizaciones.nombre AS empresa
        ,sol.u_id AS u_id
        ,sol.nombre AS nombre
        ,ati.nombre AS atiende
        ,arg_empr_unidades.nombre AS mina
        ,CONCAT(vh.marca, ' ', vh.modelo) AS vehiculo
        ,vh.placas AS placas
        ,arg_entradas.fecha_inicio
        ,arg_entradas.fecha_final
        ,di.fecha AS hoy
        ,arg_entradas.folio AS folio
        ,arg_entradas.trn_id AS trn_id
        ,arg_entradas.comentario AS comentario
        ,en.estado AS estado

        ,(CASE WHEN usuario_id_atie = usuario 
               THEN (CASE di.estado_id 
                          WHEN 1 THEN 2 
                          WHEN 2 THEN 4 
                          WHEN 4 THEN 6 
                          WHEN 8 THEN 6 
                     END)
               WHEN empleado = 99 
               THEN (CASE di.estado_id 
                          WHEN 2 THEN 4 
                          WHEN 4 THEN 6 
                          WHEN 8 THEN 6 
                     END)
               ELSE '' END) AS estado_1

    ,(CASE WHEN usuario_id_atie = usuario 
           THEN (CASE di.estado_id 
                     WHEN 1 THEN 'Autorizar' 
                     WHEN 2 THEN 'Iniciado' 
                     WHEN 4 THEN 'Finalizar' 
                     WHEN 8 THEN 'Finalizar' 
                 END)
           WHEN empleado = 99
           THEN (CASE di.estado_id 
                     WHEN 2 THEN 'Iniciado' 
                     WHEN 4 THEN 'Finalizar' 
                     WHEN 8 THEN 'Finalizar' 
                 END)	
      ELSE '' END) AS estado_1_n

    ,(CASE WHEN usuario_id_atie = usuario THEN (CASE ene.estado_id WHEN 1 THEN 3 WHEN 2 THEN 5 WHEN 4 THEN 8 END) ELSE '' END) AS estado_2
    ,(CASE WHEN usuario_id_atie = usuario THEN (CASE ene.estado_id WHEN 1 THEN 'Rechazar' WHEN 2 THEN 'No Iniciado' WHEN 4 THEN 'Prolongado' END) ELSE '' END) AS estado_2_n

    ,(CASE WHEN di.estado_id IN (4,6) THEN di.hora_inicio ELSE '' END) AS iniciado
    ,(CASE WHEN di.estado_id IN (4,6) THEN di.hora_fin  ELSE '' END) AS finalizado

    ,REPLACE(TIMEDIFF(di.hora_inicio, di.hora_fin), '-', '') AS duracion_visita
    FROM
        arg_entradas 
        LEFT JOIN arg_entradas_dias AS di
            ON di.trn_id_rel = arg_entradas.trn_id
        LEFT JOIN arg_usuarios sol
            ON sol.u_id = arg_entradas.usuario_id
        LEFT JOIN arg_usuarios ati
            ON ati.u_id = arg_entradas.usuario_id_atie

        LEFT JOIN arg_organizaciones
            ON arg_organizaciones.org_id = sol.org_id

        LEFT JOIN arg_empr_unidades
            ON arg_empr_unidades.unidad_id = arg_entradas.unidad_id

        LEFT JOIN arg_vehiculos AS vh
            ON vh.veh_id = arg_entradas.veh_id

        LEFT JOIN visitas_estados ene
            ON ene.trn_id = arg_entradas.trn_id

        LEFT JOIN arg_estados AS en
            ON en.estado_id = di.estado_id
        LEFT JOIN arg_usuarios_perfiles up 
            ON up.u_id = usuario
        LEFT JOIN visitas_duracion du
            ON du.trn_id = ene.trn_id
    WHERE 
        arg_entradas.usuario_id = (CASE WHEN empleado IN(1,99,100) THEN arg_entradas.usuario_id ELSE usuario END)

        AND arg_entradas.usuario_id_atie = (CASE WHEN empleado IN (0,99,100) THEN arg_entradas.usuario_id_atie ELSE usuario  END)
        AND (di.fecha BETWEEN fecha_ini AND fecha_fin OR ene.estado_id = 8)

        AND sol.org_id = (CASE WHEN (empleado = 100 AND sol.division = 'proveedor') THEN org_administrador ELSE sol.org_id END )

        AND arg_entradas.unidad_id = (CASE WHEN unidad = 0 THEN arg_entradas.unidad_id ELSE unidad END)
        AND en.estado_id = (CASE WHEN estado = 0 THEN en.estado_id ELSE estado END)
    ORDER BY 
        di.fecha;
        
        
    END;
    ELSE
    BEGIN
         
         SELECT
         arg_organizaciones.nombre AS empresa
        ,sol.u_id AS u_id
        ,sol.nombre AS nombre
        ,ati.nombre AS atiende
        ,arg_empr_unidades.nombre AS mina
        ,CONCAT(vh.marca, ' ', vh.modelo) AS vehiculo
        ,vh.placas AS placas
        ,arg_entradas.fecha_inicio
        ,arg_entradas.fecha_final
        ,di.fecha AS hoy
        ,arg_entradas.folio AS folio
        ,arg_entradas.trn_id AS trn_id
        ,arg_entradas.comentario AS comentario
        ,en.estado AS estado

        ,(CASE WHEN usuario_id_atie = usuario 
               THEN (CASE di.estado_id 
                          WHEN 1 THEN 2 
                          WHEN 2 THEN 4 
                          WHEN 4 THEN 6 
                          WHEN 8 THEN 6 
                     END)
               WHEN empleado = 99 
               THEN (CASE di.estado_id 
                          WHEN 2 THEN 4 
                          WHEN 4 THEN 6 
                          WHEN 8 THEN 6 
                     END)
               ELSE '' END) AS estado_1

    ,(CASE WHEN usuario_id_atie = usuario 
           THEN (CASE di.estado_id 
                     WHEN 1 THEN 'Autorizar' 
                     WHEN 2 THEN 'Iniciado' 
                     WHEN 4 THEN 'Finalizar' 
                     WHEN 8 THEN 'Finalizar' 
                 END)
           WHEN empleado = 99
           THEN (CASE di.estado_id 
                     WHEN 2 THEN 'Iniciado' 
                     WHEN 4 THEN 'Finalizar' 
                     WHEN 8 THEN 'Finalizar' 
                 END)	
      ELSE '' END) AS estado_1_n

    ,(CASE WHEN usuario_id_atie = usuario THEN (CASE ene.estado_id WHEN 1 THEN 3 WHEN 2 THEN 5 WHEN 4 THEN 8 END) ELSE '' END) AS estado_2
    ,(CASE WHEN usuario_id_atie = usuario THEN (CASE ene.estado_id WHEN 1 THEN 'Rechazar' WHEN 2 THEN 'No Iniciado' WHEN 4 THEN 'Prolongado' END) ELSE '' END) AS estado_2_n

     ,(CASE WHEN di.estado_id IN (4,6) THEN di.hora_inicio ELSE '' END) AS iniciado
    ,(CASE WHEN di.estado_id IN (4,6) THEN di.hora_fin  ELSE '' END) AS finalizado

    ,REPLACE(TIMEDIFF(di.hora_inicio, di.hora_fin), '-', '') AS duracion_visita
    FROM
        arg_entradas 
        LEFT JOIN arg_entradas_dias AS di
            ON di.trn_id_rel = arg_entradas.trn_id
        LEFT JOIN arg_usuarios sol
            ON sol.u_id = arg_entradas.usuario_id
        LEFT JOIN arg_usuarios ati
            ON ati.u_id = arg_entradas.usuario_id_atie

        LEFT JOIN arg_organizaciones
            ON arg_organizaciones.org_id = sol.org_id

        LEFT JOIN arg_empr_unidades
            ON arg_empr_unidades.unidad_id = arg_entradas.unidad_id

        LEFT JOIN arg_vehiculos AS vh
            ON vh.veh_id = arg_entradas.veh_id

        LEFT JOIN visitas_estados ene
            ON ene.trn_id = arg_entradas.trn_id

        LEFT JOIN arg_estados AS en
            ON en.estado_id = di.estado_id
        LEFT JOIN arg_usuarios_perfiles up 
            ON up.u_id = usuario
        LEFT JOIN visitas_duracion du
            ON du.trn_id = ene.trn_id
    WHERE 
        arg_entradas.usuario_id = (CASE WHEN empleado IN(1,99) THEN arg_entradas.usuario_id ELSE usuario END)

        AND arg_entradas.usuario_id_atie = (CASE WHEN empleado IN (0,99) THEN arg_entradas.usuario_id_atie ELSE usuario  END)
        AND (di.fecha BETWEEN fecha_ini AND fecha_fin OR ene.estado_id = 8)


        AND arg_entradas.unidad_id = (CASE WHEN unidad = 0 THEN arg_entradas.unidad_id ELSE unidad END)
        AND en.estado_id = (CASE WHEN estado = 0 THEN en.estado_id ELSE estado END)
    ORDER BY 
        di.fecha;
    
    END;
END IF;
    
END$$
DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS visor_visitas_v2$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `visor_visitas_v2`(IN `usuario` INT, IN `fecha_ini` DATE, IN `fecha_fin` DATE, IN `empleado` SMALLINT, IN `unidad` INT, IN `estado` INT)
SELECT
     arg_organizaciones.nombre AS empresa
    ,arg_usuarios.u_id AS u_id
    ,arg_usuarios.nombre AS nombre
    ,ati.nombre AS atiende
    ,arg_empr_unidades.nombre AS mina
    ,CONCAT(vh.marca, ' ', vh.modelo) AS vehiculo
    ,vh.placas AS placas
	,arg_entradas.fecha_inicio
    ,arg_entradas.fecha_final
    ,di.fecha AS hoy
    ,arg_entradas.folio AS folio
    ,arg_entradas.trn_id AS trn_id
    ,arg_entradas.comentario AS comentario
    ,en.estado AS estado
    
    ,(CASE WHEN usuario_id_atie = usuario 
           THEN (CASE ene.estado_id 
                      WHEN 1 THEN 2 
                      WHEN 2 THEN 4 
                      WHEN 4 THEN 6 
                      WHEN 8 THEN 6 
                 END)
           WHEN empleado = 99 
      	   THEN (CASE ene.estado_id 
                      WHEN 2 THEN 4 
                      WHEN 4 THEN 6 
                      WHEN 8 THEN 6 
                 END)
           ELSE '' END) AS estado_1

,(CASE WHEN usuario_id_atie = usuario 
       THEN (CASE ene.estado_id 
                 WHEN 1 THEN 'Autorizar' 
                 WHEN 2 THEN 'Iniciado' 
                 WHEN 4 THEN 'Finalizar' 
                 WHEN 8 THEN 'Finalizar' 
             END)
 	   WHEN empleado = 99
  	   THEN (CASE ene.estado_id 
                 WHEN 2 THEN 'Iniciado' 
                 WHEN 4 THEN 'Finalizar' 
                 WHEN 8 THEN 'Finalizar' 
             END)	
  ELSE '' END) AS estado_1_n

,(CASE WHEN usuario_id_atie = usuario THEN (CASE ene.estado_id WHEN 1 THEN 3 WHEN 2 THEN 5 WHEN 4 THEN 8 END) ELSE '' END) AS estado_2
    ,(CASE WHEN usuario_id_atie = usuario THEN (CASE ene.estado_id WHEN 1 THEN 'Rechazar' WHEN 2 THEN 'No Iniciado' WHEN 4 THEN 'Prolongado' END) ELSE '' END) AS estado_2_n

,du.iniciado AS iniciado
,du.finalizado AS finalizado

,TIMEDIFF(du.finalizado, du.iniciado) AS duracion_visita
FROM
	arg_entradas 
    LEFT JOIN arg_entradas_dias AS di
    	ON di.trn_id_rel = arg_entradas.trn_id
    LEFT JOIN arg_usuarios 
    	ON arg_usuarios.u_id = arg_entradas.usuario_id
    LEFT JOIN arg_usuarios ati
        ON ati.u_id = arg_entradas.usuario_id_atie
    LEFT JOIN arg_organizaciones
        ON arg_organizaciones.org_id = arg_usuarios.org_id
    LEFT JOIN arg_empr_unidades
    	ON arg_empr_unidades.unidad_id = arg_entradas.unidad_id
    LEFT JOIN arg_vehiculos AS vh
    	ON vh.veh_id = arg_entradas.veh_id
    LEFT JOIN visitas_estados ene
    	ON ene.trn_id = arg_entradas.trn_id
    LEFT JOIN arg_estados AS en
    	ON en.estado_id = ene.estado_id
    LEFT JOIN arg_usuarios_perfiles up 
        ON up.u_id = usuario
    LEFT JOIN visitas_duracion du
        ON du.trn_id = ene.trn_id
WHERE 
	arg_entradas.usuario_id = (CASE WHEN empleado IN(1,99) THEN arg_entradas.usuario_id ELSE usuario END)
    AND arg_entradas.usuario_id_atie = (CASE WHEN up.perfil_id = 2 THEN usuario ELSE  arg_entradas.usuario_id_atie END)
    AND (di.fecha BETWEEN fecha_ini AND fecha_fin OR ene.estado_id = 8)
    AND arg_entradas.unidad_id = (CASE WHEN unidad = 0 THEN arg_entradas.unidad_id ELSE unidad END)
    AND en.estado_id = (CASE WHEN estado = 0 THEN en.estado_id ELSE estado END)
ORDER BY 
    di.fecha$$
DELIMITER ;
