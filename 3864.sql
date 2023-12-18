-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 11-12-2023 a las 23:53:57
-- Versión del servidor: 10.1.48-MariaDB-0+deb9u2
-- Versión de PHP: 8.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `3864`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE `gastos` (
  `id_gasto` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `fecha` varchar(20) DEFAULT NULL,
  `id_presupuesto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`id_gasto`, `id_usuario`, `monto`, `fecha`, `id_presupuesto`) VALUES
(1, 1, '2000.00', '10-12-2023 16:43', NULL),
(7, 1, '1000.00', '10-12-2023 18:44', NULL),
(8, 1, '1777.00', '10-12-2023 18:52', NULL),
(20, 1, '1000.00', '11-12-2023 19:38', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuestos`
--

CREATE TABLE `presupuestos` (
  `id_presupuesto` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `monto_maximo` decimal(10,2) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `gasto_actual` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `presupuestos`
--

INSERT INTO `presupuestos` (`id_presupuesto`, `id_usuario`, `monto_maximo`, `fecha_inicio`, `fecha_fin`, `gasto_actual`) VALUES
(1, 1, '10000.00', '2023-12-10', '2023-12-15', '1000.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas`
--

CREATE TABLE `tareas` (
  `id_tarea` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` varchar(250) DEFAULT NULL,
  `fecha_limite` varchar(20) DEFAULT NULL,
  `prioridad` varchar(2) NOT NULL,
  `completada` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tareas`
--

INSERT INTO `tareas` (`id_tarea`, `id_usuario`, `titulo`, `descripcion`, `fecha_limite`, `prioridad`, `completada`) VALUES
(33, 1, 'laquuesefue', 'xddddoaooaoaoa', '2023-12-29 14:30', 'no', 'no'),
(35, 1, 'otramasff', 'alksfjsdlsad :(', '2023-12-29 14:30', 'si', 'no'),
(41, 1, 'unamasalsjf', 'ksdahlgslkhgskladfsgdalkfsgalkfhgsdalkfgsdahfgshsdafsafsdafsdafsdafsdafdsafdsafdsafdsf', '2023-12-10 10:00', 'si', 'no'),
(43, 1, 'comprar', 'jasfhsajkfahfj 2323 jladsbfdskjapf sdakp fsdajpo fhsda0j fhs0da fsad0hf sda0fsdahasd0fbsda fsd0a fsa sd0bf dsa0fasdf0usdah fsda0buf sdab0f ads0uf sad0fb0sdafbsd0a f  bsdaf sda0 fs0abdsad0 fsdab0 sdabf0sdabf bsda0 f0sadbds0ad s0f sa0bf dsa0f sda0bf sd', '2023-12-19 10:00', 'si', 'no'),
(65, 1, 'completadasadasfaf', 'lskdsaldsmssddsd', '2023-12-10 10:00', 'si', '2023-12-09 20:07'),
(71, 1, 'vencidaaa', 'khqgdkhasFAF', '2023-12-09 20:00', 'si', 'no'),
(73, 1, 'paracomasfmsdaf', 'sioa.mcvc,bmvc//aosdodaosasd', '2023-12-11 10:00', 'no', '2023-12-10 00:49'),
(74, 1, 'verañaslñkdsff', 'akjsf sda f sadjf dsaf asd kf jsadj f sjadfja dsjf jdas jfsda veamosss', '2023-12-22 10:00', 'si', 'no'),
(83, 1, 'jjjjjjjjjjjjjjjjjjjjjjjjjjjjjjpñaññññ', 'presupuestosoaoaaoaa en realidad', '2023-12-17 20:13', 'no', 'no'),
(97, 2, 'otrotitulokksad', 'adkn sdavdsvvmn sa df sadf ', '2023-12-19 19:00', 'no', '2023-12-10 14:21'),
(104, 2, 'ppruebareal', 'jkasjfsdkjfksdaf', '2023-12-19 09:21', 'si', 'no'),
(105, 2, 'comprarlslla', 'descripcion... .sdañsa ñas. sdfl31qew>(&)X8dwq9y6)/¿0scl+A\nSC{Ñq\n\nm', '2023-12-16 12:17', 'no', '2023-12-10 14:49'),
(108, 2, 'notanimportantee', 'lkaslkflasfkfslam sdjqw\'0g(&G()?+lp¡?0i)Y|1\'|13?¡DPP*¿´d+s', '2023-12-19 10:00', 'no', 'no'),
(109, 2, 'ayerjaja', 'hakslkasñcasd  asd as fjaf asd fjda', '2023-12-09 10:00', 'si', 'no');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `usuario` varchar(25) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `usuario`, `clave`, `email`) VALUES
(1, 'perrito', '$2y$10$HGlqhnr5YYfho6mXEO1D0evXDybOAQXm10F5Jt/r8ujR/xo2hLKBq', 'perrito@gmail.com'),
(2, 'otro', '$2y$10$PH7WweQKlB2n5RWytiKzAOwT8bVEtxnbNBTX.loqIgfh8LomC9G1G', 'otro@gmail.com');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`id_gasto`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  ADD PRIMARY KEY (`id_presupuesto`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD PRIMARY KEY (`id_tarea`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id_gasto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  MODIFY `id_presupuesto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tareas`
--
ALTER TABLE `tareas`
  MODIFY `id_tarea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD CONSTRAINT `gastos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `gastos_ibfk_2` FOREIGN KEY (`id_presupuesto`) REFERENCES `presupuestos` (`id_presupuesto`);

--
-- Filtros para la tabla `presupuestos`
--
ALTER TABLE `presupuestos`
  ADD CONSTRAINT `presupuestos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `tareas`
--
ALTER TABLE `tareas`
  ADD CONSTRAINT `tareas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
