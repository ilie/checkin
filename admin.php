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
    require('conexion.php');
    
    //Conmprobamos que no se puede accder a esta pagina sin usuario ni contras
?>

<!DOCTYPE html>
<html>
<head>
   <link rel="apple-touch-icon" href="img/IconCkeckIn.png">
<link rel="icon" href="img/IconCkeckIn.png" sizes="16x16" type="image/png">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>CheckIn-Admin | VLEC</title>
    <link rel="stylesheet" media="screen" href="css/estilos_generales.css"/>
    <script src="js/jquery-3.2.1.js"> </script>
	<link rel="stylesheet" href="library/bootstrap/css/bootstrap.min.css" />
	<script src="library/bootstrap/js/bootstrap.min.js"></script>
    <script src="js/confirmar.js"></script>
    <script src="js/jquery.3.2.1"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
	
</head>
<body>
	<header>
         <?php require('cabecera.php'); ?> 
    </header>
 	
        <section>
        <?php require('mensajes.php'); ?>
        	<h1>Employees List</h1> 
            <article > 
                <table class="tabla_empleados table table-striped table-bordered table-hover">
                      <thead>
                        <tr>
                          <th  id="id_empleado"  class="th_empleados">ID</th>
                          <th  id="nombre_empleado" class="th_empleados">Name</th>
                          <th  id="email_empleado" class="th_empleados">Email</th>
                          <th  id="pin_empleado" class="th_empleados">Pin</th>
                          <th  id="opciones_empleado" class="th_empleados">Options</th>
                        </tr>
                      </thead>
                      <tbody>
                      <?php
					  
					  $consulta="SELECT * from trabajador ORDER BY nombre";
					  $resultados=mysqli_query($conexion, $consulta) or die ("No se ha podido realizar la consulta!");
					  $row_cnt = mysqli_num_rows($resultados);
					 while($fila=mysqli_fetch_array($resultados)) {
						 
							echo "<tr>";
							  echo "<td class='td_empleados'>".$fila['id_trabajador']."</td>";
							  echo "<td class='td_empleados'>".$fila['nombre']."</td>";
							  echo "<td class='td_empleados'>".$fila['correo_electronico']."</td>";
							  echo "<td class='td_empleados'>".$fila['pin']."</td>";
							  echo "<td class='td_empleados'> 
									 <button class='btn btn-default' name='up' id='up' value='Modify' type='submit'>
									 	<a class='btn-default' href='actualizar_trabajador.php?Id=".$fila['id_trabajador'].
										 "&nom=".$fila['nombre']."&nif=".$fila['nif']."&correo_electronico=".$fila['correo_electronico'].
										 "&pin=".$fila['pin']."&afiliacion=".$fila['num_afiliacion']."&horas=".$fila['horas_jornada']."'> <i class='fa fa-pencil'></i> Modify </a>
									 </button>
									 
									 <button class='btn btn-warning' name='del' id='del' value='Delete' type='submit'>
									 	<a class='btn-warning' href='borrar_trabajador.php?Id=".$fila['id_trabajador']."'> Delete  <i class='fa fa-trash-o'></i></a>
									 </button>

							 </td>";
							echo "</tr>";
						}
						?>
                      </tbody>
                      <tfoot>
                        <tr>
                          <td colspan="5"><?php printf("Result set has found %d entries.\n", $row_cnt);  mysqli_free_result($resultados); ?></td>
                        </tr>
                      </tfoot>
				</table>
                <button class="button"><a href="nuevo_empleado.php">Add new employee  <i class="fa fa-user-plus"></i></a></button> 
            </article>
            
        </section>
        <footer>
            <?php require('footer.php'); mysqli_close($conexion); ?> 
        </footer>
</body>
</html>