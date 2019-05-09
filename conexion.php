<?php
	//Datos de conexion con la bbdd
	$db_host="tu_host";             // Host
	$db_nombre="tu_BBDD";           // Base de datos
	$db_usuario="tu_usuario";       // Usuario
	$db_pwd="tu_contraseña";		// Contraseña
	
	//Establecemos conexion con la base de datos
	$conexion=mysqli_connect($db_host,$db_usuario,$db_pwd) ;
	
	if(mysqli_connect_errno()){
		
		echo "Fallo al conectar con la base de datos";
		
		exit();
		
		}
	
	mysqli_select_db($conexion, $db_nombre) or die ("No se encuentra la base de datos");
	//Cambiamos el charset para que los caracters españoles sean reconocisdos
	mysqli_set_charset($conexion, "utf8");
	
?>