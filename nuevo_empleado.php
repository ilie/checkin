<?php 
session_start();
if (!isset($_SESSION['nombre'])) {
header("Location: index.php");
}

    /*

	Autor: Ilie Florea 
 	email: virginialyonsit@gmail.com 
 	Version: 1.0
 
 
    -------------------------------------------------------------------------------------
	INICIAMOS CONEXION CON LA BASE DE DATOS
    -------------------------------------------------------------------------------------
    */
    //Abrimos la conexion desde el archivo conexion.php con la siguiente fucccion.
    include('conexion.php');
    
    //Conmprobamos que no se puede accder a esta pagina sin usuario ni contras
    
?>
<!DOCTYPE html>
<html>
<head>

<title>Add new employee | CheckIn VLEC</title>
<link rel="apple-touch-icon" href="img/IconCkeckIn.png">
<link rel="icon" href="img/IconCkeckIn.png" sizes="16x16" type="image/png">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" media="screen" href="css/actualizar.css"/>
    <link rel="stylesheet" media="screen" href="css/estilos_generales.css"/>
        <script src="js/jquery-3.2.1.js"> </script>
	<link rel="stylesheet" href="library/bootstrap/css/bootstrap.min.css" />
	<script src="library/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/confirmar.js"></script>
    <script src="js/jquery.3.2.1"></script>
</head>
<body>
	<header>
         <?php require('cabecera.php'); ?> 
    </header>
    <section>
			<?php 
				if(isset($_POST["new"])){
					$nombre=$_POST["nom"];
					$nif=$_POST["nif"];
					$correo_electronico=$_POST["correo_electronico"];
					$pin=$_POST["pin"];
					$afiliacion=$_POST["afiliacion"];
					$horas=$_POST["horas"];
					
					$sql="INSERT INTO trabajador (nombre, nif, correo_electronico, pin, num_afiliacion, horas_jornada) VALUES ('".$nombre."','".$nif."','".$correo_electronico."','".$pin."','".$afiliacion."','".$horas."')";
						
						if (mysqli_query($conexion, $sql)) {
							header("Location: admin.php?ok2=1");
							echo("<script>location.href ='admin.php?ok2=1';</script>");
						} else {
							header("Location: admin.php?ok2=0");
							echo("<script>location.href ='admin.php?ok2=1';</script>");
							
						}
						
						mysqli_close($conexion);
					
					
					
					}
					
			?>
            
            
            <H1 align="center">Add new employee</H1>
                <form class="formulario_actualizar"  action="<?php echo $_SERVER['PHP_SELF']; ?>" method='POST'>
                    <table align="center">
                        <tr>
                            <td colspan="2" id="modify_details">New employee details</td>
                        </tr>
                        <!-----------------Aqui empieza el formulario que se ve en pantalla---------------------------------->
                        <tr>
                            <td>Name: *</td>
                            <td><label for="nom"></label>
                            	<input required type="text" name="nom" id="nom" autofocus >
                            </td>
                        </tr>
                        <tr>
                            <td>NIF: *</td>
                            <td><label for="nif"></label>
                            	<input  required="required" type="text" name="nif" id="nif" >
                            </td>
                        </tr>
                        <tr>
                            <td>E-mail: *</td>
                            <td><label for="c_e"></label>
                            	<input  required="required" type="email" name="correo_electronico" id="correo_electronico">
                            </td>
                        </tr>
                        <tr>
                            <td>Pin/Pass: *</td>
                            <td><label for="pin"></label>
                            	<input  required="required" type="text" name="pin" id="pin">
                            </td>
                        </tr>
                        <tr>
                            <td>S.S. Number: *</td>
                            <td><label for="afiliacion"></label>
                            	<input required  type="text" name="afiliacion" id="afiliacion">
                            </td>
                        </tr>
                        <tr>
                            <td>CT. Hours: *</td>
                            <td><label for="horas"></label>
                            	<input  required="required" type="text" name="horas" id="horas">
                            </td>
                        </tr>
                        
                        <tr><td></td>
                            <td>
                            	<input id="guardar" name="new" value="Save" type="submit">
                            </td>
                        </tr>
                           

                    </table>
                </form>
    </section>
    <footer>
            <?php require('footer.php'); ?> 
    </footer>
</body>
</html>