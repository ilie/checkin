
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `descanso`
--

CREATE TABLE `descanso` (
  `id_descanso` int(20) NOT NULL,
  `id_jornada` int(20) NOT NULL,
  `id_empleado` int(20) NOT NULL,
  `fecha` date NOT NULL,
  `hora_entrada` time NOT NULL,
  `hora_salida` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `id_empresa` int(2) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `cif` varchar(20) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `pais` varchar(10) DEFAULT NULL,
  `codigo_postal` int(10) DEFAULT NULL,
  `telefono` int(20) DEFAULT NULL,
  `correo_electronico_empresa` varchar(255) NOT NULL,
  `pass` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jornada`
--

CREATE TABLE `jornada` (
  `id_jornada` int(20) NOT NULL,
  `id_empleado` int(20) NOT NULL,
  `fecha` date NOT NULL,
  `hora_entrada` time NOT NULL,
  `hora_salida` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trabajador`
--

CREATE TABLE `trabajador` (
  `id_trabajador` int(20) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `nif` varchar(10) NOT NULL,
  `correo_electronico` varchar(255) NOT NULL,
  `pin` varchar(10) NOT NULL,
  `num_afiliacion` varchar(20) NOT NULL,
  `horas_jornada` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `descanso`
--
ALTER TABLE `descanso`
  ADD PRIMARY KEY (`id_descanso`),
  ADD KEY `id_jornada` (`id_jornada`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`id_empresa`);

--
-- Indices de la tabla `jornada`
--
ALTER TABLE `jornada`
  ADD PRIMARY KEY (`id_jornada`),
  ADD KEY `id_empleado` (`id_empleado`);

--
-- Indices de la tabla `trabajador`
--
ALTER TABLE `trabajador`
  ADD PRIMARY KEY (`id_trabajador`,`correo_electronico`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `descanso`
--
ALTER TABLE `descanso`
  MODIFY `id_descanso` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `id_empresa` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT de la tabla `jornada`
--
ALTER TABLE `jornada`
  MODIFY `id_jornada` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- AUTO_INCREMENT de la tabla `trabajador`
--
ALTER TABLE `trabajador`
  MODIFY `id_trabajador` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `descanso`
--
ALTER TABLE `descanso`
  ADD CONSTRAINT `descanso_ibfk_1` FOREIGN KEY (`id_jornada`) REFERENCES `jornada` (`id_jornada`) ON DELETE CASCADE,
  ADD CONSTRAINT `descanso_ibfk_2` FOREIGN KEY (`id_empleado`) REFERENCES `trabajador` (`id_trabajador`);

--
-- Filtros para la tabla `jornada`
--
ALTER TABLE `jornada`
  ADD CONSTRAINT `jornada_ibfk_1` FOREIGN KEY (`id_empleado`) REFERENCES `trabajador` (`id_trabajador`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
