<?php
/*
 *Autor: Ilie Florea 
 *email: virginialyonsit@gmail.com 
 *Version: 1.0
 *-------------------------------------------------------------------------------------
 *CONNECT TO BBDD
 *-------------------------------------------------------------------------------------
 */
//Using this function.
require('conexion.php');

//Avoiding unregistered users to access tih page by pasting the URL inte the search bar
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: index.php");
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Checkins</title>
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

        <!--Icons-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <script >
			$(function () {
				$.datepicker.setDefaults($.datepicker.regional["es"]);
				$("#datepicker1 , #datepicker2, #datepicker3 , #datepicker4 , #datepicker5 , #datepicker6 , #datepicker7").datepicker({
					dateFormat: "yy-mm-dd",
				});
			});
        </script>
        
</head>
<body>
	<header>
        <?php require("cabecera.php"); ?>
     </header>
     <section>
     <?php require('mensajes2.php'); ?>
     <h1>Checkins</h1>
     <article>
     <?php 
					$sentencia="SELECT id_trabajador, nombre FROM trabajador ORDER BY nombre";
					$reg=mysqli_query($conexion, $sentencia) or die("No se ha podido realizar la consulta trabajadores!")
				
	  ?>
      <div style="display:block; margin-bottom: 30px; width: 100%">
           
         <!--------------------------------------------------------MODAL ADD ENTRY----------------------------------------------------->
            <div class="container" align="center" style="float:right; width: 150px; padding-right: 50px; padding-bottom: 30px;">
                  <!-- Trigger the modal with a button -->
                  <button type="button" class="btn btn-warning btn-lg" data-toggle="modal" data-target="#myModal"><i class="fa fa-calendar-plus-o"></i>  Add entry</button>
                
                  <!-- Modal -->
                  <div class="modal fade" id="myModal" role="dialog">
                    <div class="modal-dialog" style="width:80%; height:300px;">
                    
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h3 class="modal-title">Add manual Checkin</h3>
                        </div>
                        <div class="modal-body">
                        <p><strong>Note:</strong> All fields are required to create a manual checkin !</p>
                          <!---------------------------------------------------FORM--------------------------------------------------->
                              <form class="form-inline" method="POST" action="nuevo_checkin.php">
                                  <div class="form-group">
                                    <label for="form-group">Emplyee: </label>
                                      <select required name="trabajador" style="font-size:14px" class="input-sm" id="sel1">
                                          <option value="">Please select </option>
                                           <?php while ($trab = mysqli_fetch_array($reg)) {
                                                echo   "<option value='".$trab[0]."'>".$trab[1]."</option>";
                                                }
                                          ?>
                                    </select>
                                 </div>
                                <div class="form-group">
                                     <label for="Date">Date:</label>
                                    <input type="text" required id="datepicker3" name="fecha" placeholder="Click here date YY/mm/dd"/>
                                </div>
                                <div class="form-group">
                                  <label for="pwd">In</label>
                                  <input type="time" required class="form-control" id="time_in" name="time_in">
                                </div>
                                <div class="form-group">
                                  <label for="pwd">Out</label>
                                  <input type="time" required class="form-control" id="time_out" name="time_out">
                                </div>
                                <button type="submit"  name="save" value="save" class="btn btn-warning">Save <i class="fa fa-check"></i></button>
                              </form>
    
                          <!----------------------------------------------FIN--FORM--------------------------------------------------->
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                  
            </div>
           <!-------------------------------------------------------------------FIN MODAL-----------------------------------------------------> 
           <?php 
					$sentencia="SELECT id_trabajador, nombre FROM trabajador ORDER BY nombre";
					$reg=mysqli_query($conexion, $sentencia) or die("No se ha podido realizar la consulta trabajadores!")
				
	  		?>
           
           <!--------------------------------------------------------MODAL GENERATE PDF---------------------------------------------------->
            <div class="container" align="center" style="float:right; width: 150px; margin-right: 50px; padding-bottom: 30px;">
                  <!-- Trigger the modal with a button -->
                  <button type="button" class="btn btn-danger btn-lg" data-toggle="modal" data-target="#myModa2"><i class="fa fa-file-pdf-o"></i>  Generate PDF</button>
                
                  <!-- Modal -->
                  <div class="modal fade" id="myModa2" role="dialog">
                    <div class="modal-dialog" style="width:80%; height:300px;">
                    
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h3 class="modal-title">Generate PDF</h3>
                        </div>
                        <div class="modal-body">
                        <p><strong>Note:</strong> All fields are required to create a PDF File !</p>
                          <!---------------------------------------------------FORM--------------------------------------------------->
                              <form class="form-inline" method="POST" action="pdf.php" target="_blank">
                                  <div class="form-group">
                                    <label for="form-group">Emplyee: </label>
                                      <select required name="trabajador" style="font-size:14px" class="input-sm">
                                          <option value="">Please select </option>
                                           <?php while ($trab = mysqli_fetch_array($reg)) {
                                                echo   "<option value='".$trab[0]."'>".$trab[1]."</option>";
                                                }
                                          ?>
                                    </select>
                                 </div>
                                <div class="form-group">
                                     <label for="Date">From:</label>
                                    <input type="text" required id="datepicker4" name="fecha_inicio" placeholder="Click here date YY/mm/dd"/>
                                </div>
                                <div class="form-group">
                                     <label for="Date">To:</label>
                                    <input type="text" required id="datepicker5" name="fecha_final" placeholder="Click here date YY/mm/dd"/>
                                </div>
                                <button type="submit"  name="save" value="save" class="btn btn-danger" >Get <i class="fa fa-file-pdf-o"></i> </button>
                              </form>
    
                          <!----------------------------------------------FIN--FORM--------------------------------------------------->
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                  
            </div>
           <!-------------------------------------------------------------------FIN MODAL-----------------------------------------------------> 
           
           <?php 
					$sentencia="SELECT id_trabajador, nombre FROM trabajador ORDER BY nombre";
					$reg=mysqli_query($conexion, $sentencia) or die("No se ha podido realizar la consulta trabajadores!")
				
	 		 ?>
           
            <!--------------------------------------------------------MODAL GENERATE Excel---------------------------------------------------->
            <div class="container" align="center" style="float:right; width: 150px; margin-right: 50px; padding-bottom: 30px;">
                  <!-- Trigger the modal with a button -->
                  <button type="button" class="btn btn-success btn-lg" data-toggle="modal" data-target="#myModa3"><i class="fa fa-file-excel-o"></i>  Genetate Excel</button>
                
                  <!-- Modal -->
                  <div class="modal fade" id="myModa3" role="dialog">
                    <div class="modal-dialog" style="width:80%; height:300px;">
                    
                      <!-- Modal content-->
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h3 class="modal-title">Generate Excel</h3>
                        </div>
                        <div class="modal-body">
                        <p><strong>Note:</strong> All fields are required to create an excel file !</p>
                          <!---------------------------------------------------FORM--------------------------------------------------->
                              <form class="form-inline" method="POST" action="excel.php">
                                  <div class="form-group">
                                    <label for="form-group">Emplyee: </label>
                                      <select required name="trabajador" style="font-size:14px" class="input-sm">
                                          <option value="">Please select </option>
                                           <?php while ($trab = mysqli_fetch_array($reg)) {
                                                echo   "<option value='".$trab[0]."'>".$trab[1]."</option>";
                                                }
                                          ?>
                                    </select>
                                 </div>
                                <div class="form-group">
                                     <label for="Date">From:</label>
                                    <input type="text" required id="datepicker6" name="fecha_inicio" placeholder="Click here date YY/mm/dd"/>
                                </div>
                                <div class="form-group">
                                     <label for="Date">To:</label>
                                    <input type="text" required id="datepicker7" name="fecha_final" placeholder="Click here date YY/mm/dd"/>
                                </div>
                                <button type="submit"  name="save" value="save" class="btn btn-success">Get <i class="fa fa-file-excel-o"></i></button>
                              </form>
    
                          <!-------------------------------------------------FIN--FORM--------------------------------------------------->
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                      
                    </div>
                  </div>
                  
            </div>
           <!-----------------------------------------------------------------FIN MODAL----------------------------------------------------->          

                         
		</div>
        
     </article>
     <article>	
             	<?php 
					$sentencia="SELECT id_trabajador, nombre FROM trabajador ORDER BY nombre";
					$reg=mysqli_query($conexion, $sentencia) or die("No se ha podido realizar la consulta trabajadores!")
				
			  ?>
              <form method='GET' style='text-align:center; float:right; margin-bottom:25px; margin-top:25px; margin-right: -550px;'<?php 
					$sentencia="SELECT id_trabajador, nombre FROM trabajador ORDER BY nombre";
					$reg=mysqli_query($conexion, $sentencia) or die("No se ha podido realizar la consulta trabajadores!")
				
	  ?> class="form-inline" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" >
      
      
      
                    	<div class="form-group">
   						 <label for="Start Date">From:</label>
    					 <input type="text" id="datepicker1" name="fecha_inicio" placeholder="Start date YY/mm/dd"/>
                        </div>
                        <div class="form-group">
   						 <label for="Finish Date">to:</label>
    					 <input type="text" id="datepicker2" name="fecha_final" placeholder="Final date YY/mm/dd"/>
                        </div>
                        
                        <label for="form-group">Select one Emplyee: </label>
                 	 <select name="trabajador" style="font-size:18px" class="input-sm" id="sel1">
                    	<option value="">Please select </option>
                    		<?php while ($trab = mysqli_fetch_array($reg)) {
								echo   "<option value='".$trab[0]."'>".$trab[1]."</option>";
								}
                    		?>
                    
                  	</select>
                        
                    	<button type="submit" name="Go" value="Go" class="btn btn-default">Go</button>
                 </form>     
     </article>
     	<article>
     		<table class="table table-striped table-bordered table-hover table-condensed table-responsive">
            	<thead>
                    <th>Name</th>
                    <th>Date</th>
                    <th colspan="2">Action</th>
                    <th>Time</th>
                    <th>Options</th>
                </thead>
                <tbody>
                	<?php 
					$tamagno_paginas=15;
					
					if (isset($_GET['pagina'])) {
						
							if($_GET['pagina']==1){
								$pagina=1;
								
									//echo("<script>location.href ='checkins.php';</script>");
									
									
								
								}else{
									$pagina=$_GET['pagina'];
									
									}
						}else {
						
						$pagina=1;
						}
						
						$empezar_desde=($pagina-1)*$tamagno_paginas;
						

					
						if (isset($_GET["Go"])){
							$fecha_inicio=$_GET["fecha_inicio"];
							$fecha_final=$_GET["fecha_final"];
							$id_trabajador=$_GET["trabajador"];
							
							if (!empty($fecha_inicio) AND !empty($fecha_final) AND empty($id_trabajador) ) {
								
										$sql_total="SELECT id_trabajador, nombre, jornada.id_jornada, 
													jornada.fecha, jornada.hora_entrada, jornada.hora_salida FROM `trabajador` INNER JOIN 
													jornada on trabajador.id_trabajador=jornada.id_empleado  AND  jornada.fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_final."' " ;
													$resultado = mysqli_query($conexion, $sql_total) or die("No se ha podido realizar la consulta entre fechas!");
											
										$sql_limite="SELECT id_trabajador, nombre, jornada.id_jornada, 
											jornada.fecha, jornada.hora_entrada, jornada.hora_salida FROM `trabajador` INNER JOIN 
											jornada on trabajador.id_trabajador=jornada.id_empleado  AND  jornada.fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_final."' ORDER BY jornada.fecha DESC LIMIT $empezar_desde,$tamagno_paginas" ;
											$resultado_limite = mysqli_query($conexion, $sql_limite) or die("No se ha podido realizar la consulta entre fechas!");
											
								
								}elseif (empty($fecha_inicio) AND empty($fecha_final) AND !empty($id_trabajador) ){
										
										$sql_total="SELECT id_trabajador, nombre, jornada.id_jornada, 
													jornada.fecha, jornada.hora_entrada, jornada.hora_salida FROM `trabajador` INNER JOIN 
													jornada on trabajador.id_trabajador=jornada.id_empleado WHERE  trabajador.id_trabajador='".$id_trabajador."'" ;
													$resultado = mysqli_query($conexion, $sql_total) or die("No se ha podido realizar la consulta entre fechas!");
											
										$sql_limite="SELECT id_trabajador, nombre, jornada.id_jornada, 
											jornada.fecha, jornada.hora_entrada, jornada.hora_salida FROM `trabajador` INNER JOIN 
											jornada on trabajador.id_trabajador=jornada.id_empleado WHERE  trabajador.id_trabajador='".$id_trabajador."' ORDER BY jornada.fecha DESC LIMIT $empezar_desde,$tamagno_paginas" ;
											$resultado_limite = mysqli_query($conexion, $sql_limite) or die("No se ha podido realizar la consulta entre fechas!");
											
								
								
								
								}elseif ((!empty($id_trabajador) AND !empty($fecha_inicio)) AND !empty($fecha_final)) {
									
									$sql_total="SELECT id_trabajador, nombre, jornada.id_jornada, 
													jornada.fecha, jornada.hora_entrada, jornada.hora_salida FROM `trabajador` INNER JOIN 
													jornada on trabajador.id_trabajador=jornada.id_empleado WHERE trabajador.id_trabajador='".$id_trabajador."' AND jornada.fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_final."'" ;
													$resultado = mysqli_query($conexion, $sql_total) or die("No se ha podido realizar la consulta entre fechas2!");
											
										$sql_limite="SELECT id_trabajador, nombre, jornada.id_jornada, 
											jornada.fecha, jornada.hora_entrada, jornada.hora_salida FROM `trabajador` INNER JOIN 
											jornada on trabajador.id_trabajador=jornada.id_empleado WHERE trabajador.id_trabajador='".$id_trabajador."' AND jornada.fecha BETWEEN'".$fecha_inicio."' AND '".$fecha_final."' ORDER BY jornada.fecha DESC LIMIT  $empezar_desde,$tamagno_paginas" ;
											$resultado_limite = mysqli_query($conexion, $sql_limite) or die("No se ha podido realizar la consulta entre fechas!");
											$fila_nombre = mysqli_fetch_array($resultado_limite);
									
										
										}else {
											
											$sql_total="SELECT id_trabajador, nombre, jornada.id_jornada, jornada.fecha, jornada.hora_entrada, 
											jornada.hora_salida FROM `trabajador` INNER JOIN jornada on trabajador.id_trabajador=jornada.id_empleado  ";
											$resultado = mysqli_query($conexion, $sql_total) or die("No se ha podido realizar la consulta de jornada 4 !");
										
											$sql_limite="SELECT id_trabajador, nombre, jornada.id_jornada, jornada.fecha, jornada.hora_entrada, 
											jornada.hora_salida FROM `trabajador` INNER JOIN jornada on trabajador.id_trabajador=jornada.id_empleado ORDER BY jornada.fecha DESC LIMIT  $empezar_desde,$tamagno_paginas";
											$resultado_limite = mysqli_query($conexion, $sql_limite) or die("No se ha podido realizar la consulta de jornada 4 !");
											
											}
							
										
							
							}else{
								
								$sql_total="SELECT id_trabajador, nombre, jornada.id_jornada, jornada.fecha, jornada.hora_entrada, 
											jornada.hora_salida FROM `trabajador` INNER JOIN jornada on trabajador.id_trabajador=jornada.id_empleado  ";
											$resultado = mysqli_query($conexion, $sql_total) or die("No se ha podido realizar la consulta de jornada 4 1!");
										
								$sql_limite="SELECT id_trabajador, nombre, jornada.id_jornada, jornada.fecha, jornada.hora_entrada, 
											jornada.hora_salida FROM `trabajador` INNER JOIN jornada on trabajador.id_trabajador=jornada.id_empleado ORDER BY jornada.fecha DESC LIMIT  $empezar_desde,$tamagno_paginas ";
											$resultado_limite = mysqli_query($conexion, $sql_limite) or die("No se ha podido realizar la consulta de jornada 4 2!");	
											
											
								
								}

						$segundos_trabajo=0;
						$segundos_pausa=0;
						$row_cnt = mysqli_num_rows($resultado);
						 while ($row = mysqli_fetch_array($resultado_limite)) {
							echo   "<tr class='success'>";
                    		echo   "<td>".$row[1]."</td>";
							echo   "<td>".$row[3]."</td>";
							echo   "<td> CheckIn at: ".$row[4]."</td>";
							$id_jornada = $row[2];
							///////////SEGUNDOS Trabajo ///////////////////////////////////////////////////////////
							// Extraigo los tiempos y los comparo
							if(empty($row[5])) {$seconds=strtotime('00:00:00')-strtotime($row[4]); } else {
							$seconds=strtotime($row[5])-strtotime($row[4]);}
							$time=$seconds;
							$hours = floor($time / 3600);
							$minutes = floor(($time / 60) % 60);
							$seconds2 = $time % 60;
							$diff_horas=$hours.":".$minutes.":".$seconds2;
							if ($diff_horas < 0) {$clase="danger";} else {$clase="";}
							echo "<td class='".$clase."'>CheckOut at: ".$row[5]."</td>";
							echo "<td class='".$clase."'>".$diff_horas."</td>";
								echo "<td>
										<button class='btn btn-default' name='up' id='up' value='Modify' type='submit'>
									 	<a class='btn-default' href='actualizar_jornada.php?id_t=".$row['id_trabajador'].
										 "&nom=".$row['nombre']."&id_j=".$row['id_jornada']."&fecha_j=".$row['fecha'].
										 "&hora_entrada=".$row['hora_entrada']."&hora_salida=".$row['hora_salida']."'> <i class='fa fa-pencil'></i> Modify </a>
									 </button>
									 
									 <button class='btn btn-warning' name='del' id='del' value='Delete' type='submit'>
									 	<a class='btn-warning' href='borrar_jornada.php?id_jornada=".$row[2]."'> Delete  <i class='fa fa-trash-o'></i></a>
									 </button>
							      </td>";
							echo	"</tr>";
							$segundos_trabajo+=$seconds;
							$sql2="SELECT id_trabajador, nombre, descanso.id_descanso, descanso.fecha, 
							descanso.hora_entrada, descanso.hora_salida FROM `trabajador` INNER JOIN descanso on 
							trabajador.id_trabajador=descanso.id_empleado WHERE descanso.id_jornada='".$id_jornada."'";
							$result= mysqli_query($conexion, $sql2) or die("No se ha podido realizar la consulta de descanso!");
							
							$sql2_limite="SELECT id_trabajador, nombre, descanso.id_descanso, descanso.fecha, 
							descanso.hora_entrada, descanso.hora_salida FROM `trabajador` INNER JOIN descanso on 
							trabajador.id_trabajador=descanso.id_empleado WHERE descanso.id_jornada='".$id_jornada."'";
							$result_limite= mysqli_query($conexion, $sql2_limite) or die("No se ha podido realizar la consulta de descanso!");

							
                            $row_cnt2 = mysqli_num_rows($result);
                           // $row_cnt = $row_cnt + $row_cnt2;
								
								while ($fila = mysqli_fetch_array($result_limite)) {
									
									///////////SEGUNDOS Pausa ///////////////////////////////////////////////////////////	
									// Extraigo los tiempos y los comparo
									if (empty($fila[5])) {$segundos=strtotime('00:00:00')-strtotime($fila[4]);} else {
									$segundos=strtotime($fila[5])-strtotime($fila[4]);}
									$tiempo=$segundos;
									$horas = floor($tiempo / 3600);
									$minutos = floor(($tiempo / 60) % 60);
									$segundos2 = $tiempo % 60;
									$diff_horas_p=$horas.":".$minutos.":".$segundos2;
									$segundos_pausa+=$segundos;
									if ($diff_horas_p < 0) {$clase2="danger";} else {$clase2="";}
									echo   "<tr>";
										echo   "<td>".$fila[1]."</td>";
										echo   "<td>".$fila[3]."</td>";
										echo   "<td> Break at: ".$fila[4]." </td>";
										echo   "<td class='".$clase2."'>Back at: ".$fila[5]." </td>";
										echo   "<td class='".$clase2."'>".$diff_horas_p." </td>";
										echo "<td>
												<button class='btn btn-default' name='up' id='up' value='Modify' type='submit'>
													<a class='btn-default' href='actualizar_pausa.php?id_t=".$fila[0]."&nombre=".$fila[1]."&id_descanso=".$fila[2]."&fecha_d=".$fila[3]."&hora_entrada=".$fila[4]."&hora_salida=".$fila[5]."&id_jornada=".$id_jornada."'> <i class='fa fa-pencil'></i> Modify </a>
												</button>
											 
												<button class='btn btn-warning' name='del' id='del' value='Delete' type='submit'>
												   <a class='btn-warning' href='borrar_pausa.php?id_descanso=".$fila[2]."'> Delete  <i class='fa fa-trash-o'></i></a>
												</button>
											</td>";
									echo "</tr>";
								}
						 
						 
						 }
					?>	
                
                </tbody>
                <tfoot>
                        <tr>
                            <td height="50"><i class="fa fa-toggle-on"></i> Checkins: 
							<?php 
								if (isset($result)){
									printf($row_cnt);
                        			mysqli_free_result($resultado);
                       				mysqli_free_result($result); 
								}
								
								?></td>
                            <td colspan='2' ><i class="fa fa-clock-o"></i> Total Hours:
                            <?php 
								$segundos_trabajo;
							 	$horas_t = floor($segundos_trabajo / 3600);
									$minutos_t = floor(($segundos_trabajo / 60) % 60);
									$segundos2_t = $segundos_trabajo % 60;
									$diff_t=$horas_t.":".$minutos_t.":".$segundos2_t;
									echo $diff_t ;
							 
							 ?>
                            
                            </td>
                            <td>
                            	<i class="fa fa-smile-o"></i> Working Hours:
                                <?php 
								$e_w_s=$segundos_trabajo-$segundos_pausa;
								$tiempo_trabajo_h=$e_w_s;
									$horas_t_t = floor($tiempo_trabajo_h / 3600);
									$minutos_t_t = floor(($tiempo_trabajo_h / 60) % 60);
									$segundos2_t_t = $tiempo_trabajo_h % 60;
									$diff_h_t=$horas_t_t.":".$minutos_t_t.":".$segundos2_t_t;
									echo $diff_h_t ;	
							 ?>	
                            </td>
                            <td colspan='2'>
                            	<i class="fa fa-smile-o"></i> Break Hours:
                                <?php 
									$tiempo_pausa_h=$segundos_pausa;
									$horas_t_p = floor($tiempo_pausa_h / 3600);
									$minutos_t_p = floor(($tiempo_pausa_h / 60) % 60);
									$segundos2_t_p = $tiempo_pausa_h % 60;
									$diff_h_p=$horas_t_p.":".$minutos_t_p.":".$segundos2_t_p;
									echo $diff_h_p;
							 		mysqli_close($conexion);
							    ?>	
                            </td>
                      </tr>
                  </tfoot>
                     
            </table>
            <table class="table">
            	<tr>
                      	<td style="font-size:14px">
                        
                      		<?php  $total_paginas=ceil($row_cnt/$tamagno_paginas);
							echo "Page ".$pagina." of ".$total_paginas; 
					echo "</td>";
					echo "<td colspan='5'  style='text-align: right'>";
								for ($i=1; $i<=$total_paginas; $i++) {
									
									if (isset($_GET["Go"])){
										$fecha_inicio=$_GET["fecha_inicio"];
										$fecha_final=$_GET["fecha_final"];
										$id_trabajador=$_GET["trabajador"];
										//Mostrando por pantalla los links de paginación antiguos
										//echo "   <a href='?fecha_inicio=".$fecha_inicio."&fecha_final=".$fecha_final."&trabajador=".$id_trabajador."&pagina=".$i."&Go=GO'>" .$i. "</a> ";
									}else{
										//Mostrando por pantalla los links de paginación antiguos
										//echo "   <a href='?pagina=". $i."'>" .$i. "</a> ";
										
					
										}
									
									} // End for
							
							// Si hay pagina la guardamos en la variable pageno
							if (isset($_GET['pagina'])) {
    							$pageno = $_GET['pagina'];
							} else {
   								 $pageno = 1;
							}
							
							//Total paginas
							$total_pages = $total_paginas;
							
							
							?>
							
                             	
                        </td>
                       
                      </tr>
            </table>
            
            <?php if (isset($_GET["Go"])){ 
            
            // ===================================================================
            // Si buscamos entre fechas y/o por trabajador
            // ===================================================================
            
            	$fecha_inicio=$_GET["fecha_inicio"];
				$fecha_final=$_GET["fecha_final"];
				$id_trabajador=$_GET["trabajador"];
            
            ?>
            <!------------------------------- First ---------------------->
             <ul class="pagination pull-right " style="margin-top: -50px;">
    				<li>
    					<a href="?pagina=1&fecha_inicio=<?php 
    															echo $fecha_inicio.
    																 "&fecha_final=" . $fecha_final .
    																 "&trabajador=" . $id_trabajador .
    																 "&Go=Go"; 
    													?>">
    						First
    					</a>
    				</li>
    				
            <!------------------------------- Preview ---------------------->    				
    				<li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
      			 	 <a href="<?php 
      			 	 	if($pageno <= 1){ 
      			 	 		echo '#'; 
      			 	 		} else { 
       				 					echo "?pagina=".($pageno - 1).
       				 						 "&fecha_inicio=".$fecha_inicio.
       				 						 "&fecha_final=".$fecha_final.
       				 						 "&trabajador=".$id_trabajador.
       				 						 "&Go=Go" ; 
      			 	 		
      			 	 	}
      			 	 
      			 	 ?>">Prev</a>
      			 	 
      			 	 
      
    				</li>
    				
    				
    				
             <!------------------------------- Next ---------------------->   				
    				
    				
   			   		 <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
       				 <a href="<?php 
       				 				if($pageno >= $total_pages){ 
       				 					echo '#'; 
       				 					} else { 
       				 					echo "?pagina=".($pageno + 1).
       				 						 "&fecha_inicio=".$fecha_inicio.
       				 						 "&fecha_final=".$fecha_final.
       				 						 "&trabajador=".$id_trabajador.
       				 						 "&Go=Go" ; 
       				 					
       				 					} 
       				 				
       				 		  ?>">Next</a>
   					 </li>
            <!------------------------------- Last ---------------------->
            
            
                		
    		<li>
    			<a href="?pagina=<?php 
    						          echo  $total_pages.
    						          	    "&fecha_inicio=".$fecha_inicio.
    						          	    "&fecha_final=".$fecha_final.
    						          	    "&trabajador=".$id_trabajador.
    						          	    "&Go=Go"; 
    						      ?>">Last
    			</a>
    		</li>
		  </ul>
            
            
            
            
            <?php 
            
            } else { 
            
            	// =========================================================================
           		 // Si NO buscamos entre fechas y/o por trabajador la paginación sera normal
            	// =========================================================================            
            
            
	            ?>
	            
	            <ul class="pagination pull-right " style="margin-top: -50px;">
	    			<li><a href="?pagina=1">First</a></li>
 		   			<li class="<?php if($pageno <= 1){ echo 'disabled'; } ?>">
 	     			  <a href="<?php if($pageno <= 1){ echo '#'; } else { echo "?pagina=".($pageno - 1); } ?>">Prev</a>
  		  			</li>
 	  			    <li class="<?php if($pageno >= $total_pages){ echo 'disabled'; } ?>">
  	     			 <a href="<?php if($pageno >= $total_pages){ echo '#'; } else { echo "?pagina=".($pageno + 1); } ?>">Next</a>
  	 				 </li>
 		   			<li><a href="?pagina=<?php echo $total_pages; ?>">Last</a></li>
			  </ul>
            
            
            
            
            <?php }  ?>
            
            
            
            
     	</article>
     </section>
     <footer>
            <?php require('footer.php');?> 
    </footer>
</body>
</html>