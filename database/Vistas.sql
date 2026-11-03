-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `calificacion_ev`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `calificacion_ev`;
DROP TABLE IF EXISTS `calificacion_ev`;
CREATE TABLE `calificacion_ev` (
`usuario_prov` int(11)
,`calificacionmax` int(3)
,`nombre` varchar(200)
,`intentos` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `documentos_expira`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `documentos_expira`;
DROP TABLE IF EXISTS `documentos_expira`;
CREATE TABLE `documentos_expira` (
`u_id` int(255)
,`tipo_id` int(11)
,`fecha_expira` varchar(100)
,`nombre` varchar(100)
,`folio` varchar(250)
,`dias` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `listado_proveedores`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `listado_proveedores`;
DROP TABLE IF EXISTS `listado_proveedores`;
CREATE TABLE `listado_proveedores` (
`u_id` int(255)
,`nombre` varchar(200)
,`email` varchar(200)
,`org_id` int(180)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `meses`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `meses`;
DROP TABLE IF EXISTS `meses`;
CREATE TABLE `meses` (
`num_mes` int(2)
,`mes` varchar(10)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `motivos_visitas`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `motivos_visitas`;
DROP TABLE IF EXISTS `motivos_visitas`;
CREATE TABLE `motivos_visitas` (
`trn_id` int(11)
,`nombre` varchar(250)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `usuarios_doc`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `usuarios_doc`;
DROP TABLE IF EXISTS `usuarios_doc`;
CREATE TABLE `usuarios_doc` (
`u_id` int(255)
,`tipo_id` int(11)
,`imss` varchar(250)
,`ine` varchar(0)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `usuarios_inscritos`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `usuarios_inscritos`;
DROP TABLE IF EXISTS `usuarios_inscritos`;
CREATE TABLE `usuarios_inscritos` (
`cal_id` bigint(20)
,`total` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `visitas_duracion`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `visitas_duracion`;
DROP TABLE IF EXISTS `visitas_duracion`;
CREATE TABLE `visitas_duracion` (
`trn_id` int(11)
,`iniciado` varchar(80)
,`finalizado` varchar(80)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `visitas_estados`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `visitas_estados`;
DROP TABLE IF EXISTS `visitas_estados`;
CREATE TABLE `visitas_estados` (
`trn_id` int(11)
,`estado_id` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vist`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vist`;
DROP TABLE IF EXISTS `vist`;
CREATE TABLE `vist` (
`u_id` int(255)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_calificacion`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vista_calificacion`;
DROP TABLE IF EXISTS `vista_calificacion`;
CREATE TABLE `vista_calificacion` (
`Usuario_nombre` varchar(200)
,`U_id` int(255)
,`Curso_id` smallint(6)
,`Curso_nombre` varchar(150)
,`Salud` int(11)
,`Seguridad` int(11)
,`MedioAmbiente` int(11)
,`promedio` decimal(16,4)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_calificacion2`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vista_calificacion2`;
DROP TABLE IF EXISTS `vista_calificacion2`;
CREATE TABLE `vista_calificacion2` (
`Usuario_nombre` varchar(200)
,`U_id` int(255)
,`Curso_id` smallint(6)
,`Curso_nombre` varchar(150)
,`Salud` int(11)
,`Seguridad` int(11)
,`MedioAmbiente` int(11)
,`promedio` decimal(16,4)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_calificacion3`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vista_calificacion3`;
DROP TABLE IF EXISTS `vista_calificacion3`;
CREATE TABLE `vista_calificacion3` (
`Usuario_nombre` varchar(200)
,`U_id` int(255)
,`Curso_id` smallint(6)
,`Curso_nombre` varchar(150)
,`Salud` int(11)
,`Seguridad` int(11)
,`MedioAmbiente` int(11)
,`promedio` decimal(16,4)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_calificacion_LC`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vista_calificacion_LC`;
DROP TABLE IF EXISTS `vista_calificacion_LC`;
CREATE TABLE `vista_calificacion_LC` (
`Usuario_nombre` varchar(200)
,`U_id` int(255)
,`Curso_id` smallint(6)
,`Curso_nombre` varchar(150)
,`calificacionLC` int(11)
,`promedio` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vista_calificacion_SA`
-- (Véase abajo para la vista actual)
--
DROP VIEW IF EXISTS `vista_calificacion_SA`;
DROP TABLE IF EXISTS `vista_calificacion_SA`;
CREATE TABLE `vista_calificacion_SA` (
`Usuario_nombre` varchar(200)
,`U_id` int(255)
,`Curso_id` smallint(6)
,`Curso_nombre` varchar(150)
,`Cianuro` int(11)
,`PrimerosAuxilios` int(11)
,`promedio` decimal(15,4)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `calificacion_ev`
--
DROP TABLE IF EXISTS `calificacion_ev`;

DROP VIEW IF EXISTS `calificacion_ev`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `calificacion_ev`  AS SELECT DISTINCT `g`.`usuario_prov` AS `usuario_prov`, `g`.`calificacionmax` AS `calificacionmax`, `j`.`nombre` AS `nombre`, (select count(0) from `arg_evaluacion_calificacion` where `arg_evaluacion_calificacion`.`usuario_prov` = `g`.`usuario_prov`) AS `intentos` FROM (((select `arg_evaluacion_calificacion`.`usuario_prov` AS `usuario_prov`,max(`arg_evaluacion_calificacion`.`calificacion`) AS `calificacionmax` from `arg_evaluacion_calificacion` group by `arg_evaluacion_calificacion`.`usuario_prov`) `g` join `arg_evaluacion_calificacion` on(`g`.`usuario_prov` = `arg_evaluacion_calificacion`.`usuario_prov` and `g`.`calificacionmax` = `arg_evaluacion_calificacion`.`calificacion`)) join `arg_usuarios` `j` on(`g`.`usuario_prov` = `j`.`u_id`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `documentos_expira`
--
DROP TABLE IF EXISTS `documentos_expira`;

DROP VIEW IF EXISTS `documentos_expira`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `documentos_expira`  AS SELECT `arg_usuarios`.`u_id` AS `u_id`, `arg_usuarios_documentos`.`tipo_id` AS `tipo_id`, `arg_usuarios_documentos`.`fecha_expira` AS `fecha_expira`, `arg_tipo_documentos`.`nombre` AS `nombre`, `arg_usuarios_documentos`.`nombre` AS `folio`, timestampdiff(DAY,`arg_usuarios_documentos`.`fecha_expira`,curdate()) AS `dias` FROM ((`arg_usuarios` left join `arg_usuarios_documentos` on(`arg_usuarios_documentos`.`u_id` = `arg_usuarios`.`u_id`)) left join `arg_tipo_documentos` on(`arg_tipo_documentos`.`tipo_id` = `arg_usuarios_documentos`.`tipo_id`)) WHERE `arg_usuarios`.`activo` = 1 AND `arg_usuarios`.`division` = 'proveedor' AND `arg_usuarios_documentos`.`fecha_expira` <> '' ;

-- --------------------------------------------------------

--
-- Estructura para la vista `listado_proveedores`
--
DROP TABLE IF EXISTS `listado_proveedores`;

DROP VIEW IF EXISTS `listado_proveedores`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY INVOKER VIEW `listado_proveedores`  AS SELECT `u`.`u_id` AS `u_id`, `u`.`nombre` AS `nombre`, `u`.`email` AS `email`, `u`.`org_id` AS `org_id` FROM (`arg_usuarios` `u` left join `arg_organizaciones` `o` on(`u`.`org_id` = `o`.`org_id`)) WHERE `u`.`division` = 'proveedor' ;

-- --------------------------------------------------------

--
-- Estructura para la vista `meses`
--
DROP TABLE IF EXISTS `meses`;

DROP VIEW IF EXISTS `meses`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `meses`  AS SELECT 1 AS `num_mes`, 'enero' AS `mes`union all select 2 AS `num_mes`,'febrero' AS `mes` union all select 3 AS `num_mes`,'marzo' AS `mes` union all select 4 AS `num_mes`,'abril' AS `mes` union all select 5 AS `num_mes`,'mayo' AS `mes` union all select 6 AS `num_mes`,'junio' AS `mes` union all select 7 AS `num_mes`,'julio' AS `mes` union all select 8 AS `num_mes`,'agosto' AS `mes` union all select 9 AS `num_mes`,'septiembre' AS `mes` union all select 10 AS `num_mes`,'octubre' AS `mes` union all select 11 AS `num_mes`,'noviembre' AS `mes` union all select 12 AS `num_mes`,'diciembre' AS `mes`  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `motivos_visitas`
--
DROP TABLE IF EXISTS `motivos_visitas`;

DROP VIEW IF EXISTS `motivos_visitas`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `motivos_visitas`  AS SELECT DISTINCT `ac`.`trn_id_rel` AS `trn_id`, `mot`.`nombre` AS `nombre` FROM (`arg_entradas_actividad` `ac` left join `arg_actividad` `mot` on(`mot`.`act_id` = `ac`.`act_id`)) ;

-- --------------------------------------------------------

--
-- Estructura para la vista `usuarios_doc`
--
DROP TABLE IF EXISTS `usuarios_doc`;

DROP VIEW IF EXISTS `usuarios_doc`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `usuarios_doc`  AS SELECT `arg_usuarios`.`u_id` AS `u_id`, `arg_usuarios_documentos`.`tipo_id` AS `tipo_id`, `arg_usuarios_documentos`.`nombre` AS `imss`, '' AS `ine` FROM (`arg_usuarios` left join `arg_usuarios_documentos` on(`arg_usuarios_documentos`.`u_id` = `arg_usuarios`.`u_id`)) WHERE `arg_usuarios`.`activo` = 1 AND `arg_usuarios_documentos`.`tipo_id` = 1 AND `arg_usuarios_documentos`.`activo` = 1 union all select `arg_usuarios`.`u_id` AS `u_id`,`arg_usuarios_documentos`.`tipo_id` AS `tipo_id`,`arg_usuarios_documentos`.`nombre` AS `imss`,'' AS `ine` from (`arg_usuarios` left join `arg_usuarios_documentos` on(`arg_usuarios_documentos`.`u_id` = `arg_usuarios`.`u_id`)) where `arg_usuarios`.`activo` = 1 and `arg_usuarios_documentos`.`tipo_id` = 2 and `arg_usuarios_documentos`.`activo` = 1 union all select `arg_usuarios`.`u_id` AS `u_id`,`arg_usuarios_documentos`.`tipo_id` AS `tipo_id`,`arg_usuarios_documentos`.`nombre` AS `imss`,'' AS `ine` from (`arg_usuarios` left join `arg_usuarios_documentos` on(`arg_usuarios_documentos`.`u_id` = `arg_usuarios`.`u_id`)) where `arg_usuarios`.`activo` = 1 and `arg_usuarios_documentos`.`tipo_id` = 3 and `arg_usuarios_documentos`.`activo` = 1  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `usuarios_inscritos`
--
DROP TABLE IF EXISTS `usuarios_inscritos`;

DROP VIEW IF EXISTS `usuarios_inscritos`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY INVOKER VIEW `usuarios_inscritos`  AS SELECT `arg_calendario_usuarios`.`cal_id` AS `cal_id`, count(`arg_calendario_usuarios`.`cal_id`) AS `total` FROM `arg_calendario_usuarios` GROUP BY `arg_calendario_usuarios`.`cal_id` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `visitas_duracion`
--
DROP TABLE IF EXISTS `visitas_duracion`;

DROP VIEW IF EXISTS `visitas_duracion`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `visitas_duracion`  AS SELECT `en`.`trn_id` AS `trn_id`, (select `ee`.`fecha` from `arg_entradas_estados` `ee` where `ee`.`trn_id` = `en`.`trn_id` and `ee`.`estado_id` = 4 order by `ee`.`estado_id` desc limit 1) AS `iniciado`, (select `ee`.`fecha` from `arg_entradas_estados` `ee` where `ee`.`trn_id` = `en`.`trn_id` and `ee`.`estado_id` = 6 order by `ee`.`estado_id` desc limit 1) AS `finalizado` FROM `visitas_estados` AS `en` WHERE `en`.`estado_id` = 6 ;

-- --------------------------------------------------------

--
-- Estructura para la vista `visitas_estados`
--
DROP TABLE IF EXISTS `visitas_estados`;

DROP VIEW IF EXISTS `visitas_estados`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `visitas_estados`  AS SELECT `en`.`trn_id` AS `trn_id`, (select `ee`.`estado_id` from `arg_entradas_estados` `ee` where `ee`.`trn_id` = `en`.`trn_id` order by `ee`.`fecha` desc limit 1) AS `estado_id` FROM `arg_entradas` AS `en` ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vist`
--
DROP TABLE IF EXISTS `vist`;

DROP VIEW IF EXISTS `vist`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vist`  AS SELECT `arg_usuarios`.`u_id` AS `u_id` FROM `arg_usuarios` WHERE `arg_usuarios`.`activo` = 1 WITH LOCAL CHECK OPTION  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_calificacion`
--
DROP TABLE IF EXISTS `vista_calificacion`;

DROP VIEW IF EXISTS `vista_calificacion`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_calificacion`  AS SELECT `us`.`nombre` AS `Usuario_nombre`, `us`.`u_id` AS `U_id`, `cu`.`curso_id` AS `Curso_id`, `cu`.`nombre` AS `Curso_nombre`, (select ifnull(max(`arg_evaluacion_calificacion`.`calificacion`),0) from `arg_evaluacion_calificacion` where `arg_evaluacion_calificacion`.`examen_id` = 0) AS `Salud`, (select ifnull(max(`arg_evaluacion_calificacion`.`calificacion`),0) from `arg_evaluacion_calificacion` where `arg_evaluacion_calificacion`.`examen_id` = 1) AS `Seguridad`, (select ifnull(max(`arg_evaluacion_calificacion`.`calificacion`),0) from `arg_evaluacion_calificacion` where `arg_evaluacion_calificacion`.`examen_id` = 2) AS `MedioAmbiente`, (select (`Salud` + `Seguridad` + `MedioAmbiente`) / 3) AS `promedio` FROM ((`arg_evaluacion_calificacion` `ec` join `arg_usuarios` `us` on(`ec`.`usuario_prov` = `us`.`u_id`)) join `arg_cursos` `cu` on(`ec`.`curso_id` = `cu`.`curso_id`)) WHERE `ec`.`curso_id` = 3 ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_calificacion2`
--
DROP TABLE IF EXISTS `vista_calificacion2`;

DROP VIEW IF EXISTS `vista_calificacion2`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_calificacion2`  AS SELECT DISTINCT `us`.`nombre` AS `Usuario_nombre`, `us`.`u_id` AS `U_id`, `cu`.`curso_id` AS `Curso_id`, `cu`.`nombre` AS `Curso_nombre`, (select ifnull(max(`arg_evaluacion_calificacion`.`calificacion`),0) from `arg_evaluacion_calificacion` where `arg_evaluacion_calificacion`.`examen_id` = 0) AS `Salud`, (select ifnull(max(`arg_evaluacion_calificacion`.`calificacion`),0) from `arg_evaluacion_calificacion` where `arg_evaluacion_calificacion`.`examen_id` = 1) AS `Seguridad`, (select ifnull(max(`arg_evaluacion_calificacion`.`calificacion`),0) from `arg_evaluacion_calificacion` where `arg_evaluacion_calificacion`.`examen_id` = 2) AS `MedioAmbiente`, (select (`Salud` + `Seguridad` + `MedioAmbiente`) / 3) AS `promedio` FROM ((`arg_evaluacion_calificacion` `ec` join `arg_usuarios` `us` on(`ec`.`usuario_prov` = `us`.`u_id`)) join `arg_cursos` `cu` on(`ec`.`curso_id` = `cu`.`curso_id`)) WHERE `ec`.`curso_id` = 3 ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_calificacion3`
--
DROP TABLE IF EXISTS `vista_calificacion3`;

DROP VIEW IF EXISTS `vista_calificacion3`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_calificacion3`  AS SELECT DISTINCT `us`.`nombre` AS `Usuario_nombre`, `us`.`u_id` AS `U_id`, `cu`.`curso_id` AS `Curso_id`, `cu`.`nombre` AS `Curso_nombre`, (select ifnull(max(`arg_evaluacion_calificacion`.`calificacion`),0) from `arg_evaluacion_calificacion` where `arg_evaluacion_calificacion`.`examen_id` = 0 and `arg_evaluacion_calificacion`.`usuario_prov` = `us`.`u_id`) AS `Salud`, (select ifnull(max(`arg_evaluacion_calificacion`.`calificacion`),0) from `arg_evaluacion_calificacion` where `arg_evaluacion_calificacion`.`examen_id` = 1 and `arg_evaluacion_calificacion`.`usuario_prov` = `us`.`u_id`) AS `Seguridad`, (select ifnull(max(`arg_evaluacion_calificacion`.`calificacion`),0) from `arg_evaluacion_calificacion` where `arg_evaluacion_calificacion`.`examen_id` = 2 and `arg_evaluacion_calificacion`.`usuario_prov` = `us`.`u_id`) AS `MedioAmbiente`, (select (`Salud` + `Seguridad` + `MedioAmbiente`) / 3) AS `promedio` FROM ((`arg_evaluacion_calificacion` `ec` join `arg_usuarios` `us` on(`ec`.`usuario_prov` = `us`.`u_id`)) join `arg_cursos` `cu` on(`ec`.`curso_id` = `cu`.`curso_id`)) WHERE `ec`.`curso_id` = 3 ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_calificacion_LC`
--
DROP TABLE IF EXISTS `vista_calificacion_LC`;

DROP VIEW IF EXISTS `vista_calificacion_LC`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_calificacion_LC`  AS SELECT DISTINCT `us`.`nombre` AS `Usuario_nombre`, `us`.`u_id` AS `U_id`, `cu`.`curso_id` AS `Curso_id`, `cu`.`nombre` AS `Curso_nombre`, (select ifnull(max(`arg_evaluacion_calificacion`.`calificacion`),0) from `arg_evaluacion_calificacion` where `arg_evaluacion_calificacion`.`examen_id` = 6 and `arg_evaluacion_calificacion`.`usuario_prov` = `us`.`u_id`) AS `calificacionLC`, (select `calificacionLC`) AS `promedio` FROM ((`arg_evaluacion_calificacion` `sa` join `arg_usuarios` `us` on(`sa`.`usuario_prov` = `us`.`u_id`)) join `arg_cursos` `cu` on(`sa`.`curso_id` = `cu`.`curso_id`)) WHERE `sa`.`curso_id` = 1 ;

-- --------------------------------------------------------

--
-- Estructura para la vista `vista_calificacion_SA`
--
DROP TABLE IF EXISTS `vista_calificacion_SA`;

DROP VIEW IF EXISTS `vista_calificacion_SA`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vista_calificacion_SA`  AS SELECT DISTINCT `us`.`nombre` AS `Usuario_nombre`, `us`.`u_id` AS `U_id`, `cu`.`curso_id` AS `Curso_id`, `cu`.`nombre` AS `Curso_nombre`, (select ifnull(max(`arg_evaluacion_calificacion`.`calificacion`),0) from `arg_evaluacion_calificacion` where `arg_evaluacion_calificacion`.`examen_id` = 4 and `arg_evaluacion_calificacion`.`usuario_prov` = `us`.`u_id`) AS `Cianuro`, (select ifnull(max(`arg_evaluacion_calificacion`.`calificacion`),0) from `arg_evaluacion_calificacion` where `arg_evaluacion_calificacion`.`examen_id` = 5 and `arg_evaluacion_calificacion`.`usuario_prov` = `us`.`u_id`) AS `PrimerosAuxilios`, (select (`Cianuro` + `PrimerosAuxilios`) / 2) AS `promedio` FROM ((`arg_evaluacion_calificacion` `sa` join `arg_usuarios` `us` on(`sa`.`usuario_prov` = `us`.`u_id`)) join `arg_cursos` `cu` on(`sa`.`curso_id` = `cu`.`curso_id`)) WHERE `sa`.`curso_id` = 2 ;
COMMIT;