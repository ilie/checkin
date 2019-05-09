<?php 
session_start();
        if (!isset($_SESSION['nombre'])) {
                header("Location: index.php");
            }

    /*
    -------------------------------------------------------------------------------------
	INICIAMOS CONEXION CON LA BASE DE DATOS
    -------------------------------------------------------------------------------------
    */
    //Abrimos la conexion desde el archivo conexion.php con la siguiente fucccion.
    require('conexion.php');
    
    //Conmprobamos que no se puede accder a esta pagina sin usuario ni contras
    
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>My Details</title>
    <link rel="stylesheet" media="screen" href="css/actualizar.css"/>
    <script src="js/confirmar.js"></script>
     <link rel="apple-touch-icon" href="img/IconCkeckIn.png">
<link rel="icon" href="img/IconCkeckIn.png" sizes="16x16" type="image/png">
      
        <link rel="stylesheet" media="screen" href="css/estilos_generales.css"/>

        <!--JqueryUI-->
        <link rel="stylesheet" href="library/jquery-ui/jquery-ui.css"/>
        <script src="library/jquery-ui/jquery-ui.js"></script> 
        <!--Jquery-->
        <script src="js/jquery-3.2.1.js"></script>

        <link rel="stylesheet" href="library/bootstrap/css/bootstrap.min.css" />
        <script src="library/bootstrap/js/bootstrap.min.js"></script>
        
        
        <script type="text/javascript" src="library/ui3/jquery-ui-1.10.4.custom.js"></script>
        <link rel="stylesheet" type="text/css" href="library/ui3/css/sunny/jquery-ui-1.10.4.custom.css"/>
        
        
        <script type="text/javascript" src="./../../plugins/ui3/jquery-ui-1.10.4.custom.js"></script>
        <link rel="stylesheet" type="text/css" href="./../../plugins/ui3/css/sunny/jquery-ui-1.10.4.custom.css"/>
        <!--Bootstrap Date Picker
        <script src="library/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="library/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <link rel="stylesheet" media="screen" href="library/bootstrap-datepicker/css/bootstrap-datepicker.css">
        <script src="js/confirmar.js"></script> -->
        <!--Icons-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    
</head>

<body>
	<header>
         <?php require('cabecera.php'); ?> 
    </header>
    <section>
    	<article>
        	<?php 
			
			if (isset($_POST["save"])){
				
									$id=$_POST["id"];
					$nom=$_POST["nom"];
					$cif=$_POST["cif"];
					$address=$_POST["address"];
					$pais=$_POST["country"];
					$cp=$_POST["cp"];
					$tel=$_POST["tel"];
					$email=$_POST["email"];
					$pin=$_POST["pin"];
					//Empezamos a crear las instruciones SQL necesarios para actulalizar la información del formulario.
					
					$sql = "UPDATE empresa SET nombre='".$nom."', cif='".$cif."', direccion='".$address."', pais='".$pais."', pass='".$pin."', codigo_postal='".$cp."', telefono='".$tel."', correo_electronico_empresa='".$email."', pass='".$pin."' WHERE id_empresa=".$id;
			
						if (mysqli_query($conexion, $sql)) {
							
							echo "<article>";
               			    echo "	<div class='alert success'>";
 							echo "	  <span class='closebtn'>&times;</span>";
  							echo "		<strong>OK !</strong> Record updated successfully.";
                			echo "      <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
							echo "	</div>";
            				echo "</article>";
						} else {
							
							//echo mysqli_error($conexion);
							echo "<article>";
               			    echo "	<div class='alert warning'>";
 							echo "	  <span class='closebtn'>&times;</span>";
  							echo "		<strong>Warning!</strong> Error updating record please contact your administrator.";
                			echo "      <script src='js/mensaje_borrar_trabajador_ok.js'></script>";
							echo "	</div>";
            				echo "</article>";
							
						}
				
				
				
					
					
				}else{
					
					
					$consulta="SELECT * from empresa";
					$resultados=mysqli_query($conexion, $consulta) or die ("No se ha podido realizar la consulta!");
					$fila=mysqli_fetch_array($resultados);
					  
					$id=$fila['0'];
					$nom=$fila['1'];
					$cif=$fila['2'];
					$address=$fila['3'];
					$pais=$fila['4'];
					$cp=$fila['5'];
					$tel=$fila['6'];
					$email=$fila['7'];
					$pin=$fila['8'];
					
					
					

						
						mysqli_close($conexion);
					
				}
			
			?>
            <H1 >Modify Company details</H1>
                <form class="formulario_actualizar" method='POST' action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <table align="center">
                        <tr>
                            <td colspan="2" id="modify_details">Modify company details</td>
                        </tr>
                        <!-----------------Aqui empieza el formulario que se ve en pantalla---------------------------------->
                        <tr>
                            <td colspan="2"> <label for="id"></label>
                            <input type="hidden" name="id" id="id" value='<?php echo $id ;?>'autofocus ></td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >Name: </td>
                            <td><label for="nom"></label>
                            	<input required type="text" name="nom" id="nom_empresa" value='<?php echo $nom ;?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >CIF: </td>
                            <td><label for="cif"></label>
                            	<input required type="text" name="cif" id="cif" value='<?php echo $cif ;?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >Address: </td>
                            <td><label for="address"></label>
                            	<input required type="text" name="address" id="address" value='<?php echo $address ;?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >Country: </td>
                            <td><label for="country"></label>
                            	<input required type="text" name="country" id="country" value='<?php echo $pais ;?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >Postal code: </td>
                            <td><label for="cp"></label>
                            	<input required type="text" name="cp" id="cp" value='<?php echo $cp ;?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >Telephone: </td>
                            <td><label for="tel"></label>
                            	<input required type="tel" name="tel" id="tel" value='<?php echo $tel ;?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >Email: </td>
                            <td><label for="email"></label>
                            	<input required type="email" name="email" id="email" value='<?php echo $email ;?>' >
                            </td>
                        </tr>
                        <tr>
                            <td id="primera_columna" >Pin / Pass: </td>
                            <td><label for="pin"></label>
                            	<input required type="text" name="pin" id="pin" value='<?php echo $pin ;?>' >
                            </td>
                        </tr>
                        
                        <tr><td></td>
                            <td>
                            	<input id="save" name="save" value="Save" type="submit">
                            </td>
                        </tr>
                           

                    </table>
                </form>
            
            
            
    	</article>
    </section>
    <footer>
            <?php require('footer.php'); ?> 
        </footer>
</body>
</html>