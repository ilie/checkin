<?php 
/*
Autor: Ilie Florea 
email: virginialyonsit@gmail.com 
Version: 1.0

*/


//Llamo a conexion desde el archivo conexion.php con la siguiente fucccion.
include("conexion.php");

//Procedemos a la comprobacion de los usuarios/////////////////////////////////////////////

//Recojo los datos del formulario
	//con la funcion de mysqli_real_escape_string consigo evitar la injeccion SQL ///
	$username=mysqli_real_escape_string($conexion, $_POST['username']);
	$pwd=mysqli_real_escape_string($conexion, $_POST['pwd']);
	
// Hacemos una consulta en la tabla de los trabajador
$consulta1="SELECT * FROM trabajador WHERE correo_electronico='".$username."' AND pin='".$pwd."'";
$resultado1= mysqli_query($conexion, $consulta1) or die ("Error consultando la tabla trabajador");

	if($fila=mysqli_fetch_row($resultado1)){
	
		//En este bloque si existe el usuario lo mandamos a la página de fichar.
		session_start();
		$_SESSION['id']=$fila[0];
		$_SESSION['nombre']=$fila[1];
		header("Location: user.php");
		mysqli_close($conexion);
		
		}else{
	
				// Hacemos una consulta en la tabla de los empresa para ver si el usuario es jefe o trabajador o ninguno de los dos.
				$consulta2="SELECT * FROM empresa WHERE correo_electronico_empresa='".$username."' AND pass='".$pwd."'";
				$resultado2= mysqli_query($conexion, $consulta2) or die ("Error consultando la tabla empresa");
			
				
				
				if($fila2=mysqli_fetch_row($resultado2)){
				
					//En este bloque si existe el usuario jefe lo mandamos a la página de admin.	
					session_start();
					$_SESSION['nombre']=$fila2[1];
					header("Location: checkins.php");
					mysqli_close($conexion);
				
				}else{
					//SI el código ha llegado hasta aqui significa que el usuario no existe o no se ha escrito correctamente
					mysqli_close($conexion);
					header("Location: index.php");
					}
					
	}
	