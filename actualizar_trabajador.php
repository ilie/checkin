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
    
	//error_reporting(E_ALL); ini_set('display_errors', 'On');
?>
<!DOCTYPE html>
<html>
    
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Modify Details | CheckIn VLEC</title>
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
				/*
				---------------------------------------------------------------------------------------------------
				Recojemos las varibles que nos llegan a esta página tanto por el método post como por el método GET
				---------------------------------------------------------------------------------------------------
				*/	
				
				if (!isset($_POST["guardar"])){
					
					$id=$_GET['Id'];
					$nom=$_GET['nom'];
					$nif=$_GET['nif'];
					$correo_electronico=$_GET['correo_electronico'];
					$pin=$_GET['pin'];
					$afiliacion=$_GET['afiliacion'];
					$horas=$_GET['horas'];
					
				}else{
					
					$id=$_POST["id"];
					$nom=$_POST["nom"];
					$nif=$_POST["nif"];
					$correo_electronico=$_POST["correo_electronico"];
					$pin=$_POST["pin"];
					$afiliacion=$_POST["afiliacion"];
					$horas=$_POST["horas"];
					
					//Empezamos a crear las instruciones SQL necesarios para actulalizar la información del formulario.
					
					$sql = "UPDATE trabajador SET nombre='".$nom."', nif='".$nif."', correo_electronico='".$correo_electronico."', pin='".$pin."' , num_afiliacion='".$afiliacion."' , horas_jornada='".$horas."'  WHERE id_trabajador=".$id;
			
						if (mysqli_query($conexion, $sql)) {
							echo("<script>location.href ='admin.php?ok=1';</script>");
							header('Location: admin.php?ok=1');
						} else {
							echo("<script>location.href ='admin.php?ok=0';</script>");
							header('Location: admin.php?ok=0');
							
						}
						
						
				}
			
						
            
            ?>
            <H1 align="center">Modify  <?php echo $nom; ?>'s  details</H1>
                <form class="formulario_actualizar" method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <table align="center">
                        <tr>
                            <td colspan="2" id="modify_details">Modify  <?php echo $nom; ?>'s  details</td>
                        </tr>
                        <!-----------------Aqui empieza el formulario que se ve en pantalla---------------------------------->
                        <tr>
                            <td colspan="2"> <label for="id"></label>
                            <input type="hidden" name="id" id="id" value='<?php echo $id;?>'autofocus ></td>
                        </tr>
                        <tr>
                            <td id="primera_columna">Name: </td>
                            <td><label for="nom"></label>
                            	<input type="text" name="nom" id="nom" value='<?php echo $nom; ?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >NIF: </td>
                            <td><label for="nif"></label>
                            	<input type="text" name="nif" id="nif" value='<?php echo $nif; ?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >E-mail: </td>
                            <td><label for="c_e"></label>
                            	<input type="email" name="correo_electronico" id="correo_electronico" value='<?php echo $correo_electronico; ?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >Pin/Pass: </td>
                            <td><label for="pin"></label>
                            	<input type="text" name="pin" id="pin" value='<?php echo $pin; ?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >S.S. Number: </td>
                            <td><label for="afiliacion"></label>
                            	<input type="text" name="afiliacion" id="afiliacion" value='<?php echo $afiliacion; ?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >CT. Hours: </td>
                            <td><label for="horas"></label>
                            	<input type="text" name="horas" id="horas" value='<?php echo $horas; ?>' >
                            </td>
                        </tr>
                        
                        <tr><td></td>
                            <td>
                            	<input id="guardar" name="guardar" value="Save" type="submit">
                            </td>
                        </tr>
                           

                    </table>
                </form>
    </section>
    <footer>
            <?php require('footer.php');?> 
    </footer>
</body>
</html>