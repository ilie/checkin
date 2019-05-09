<?php
session_start();
if (!isset($_SESSION['nombre'])) {
    header("Location: index.php");
}
/*
  Autor Ilie Florea email: virginialyonsit@gmail.com 
  Version 1.0 
  -------------------------------------------------------------------------------------
  CONNECT TO BBDD
  -------------------------------------------------------------------------------------
 */
//Using this function.
require('conexion.php');

require('library/fpdf/fpdf.php');

$trabajador=$_POST['trabajador'];
$fecha_inicio=$_POST['fecha_inicio'];
$fecha_final=$_POST['fecha_final'];

//Sacamos los datos del trabajador para ponerlos en el PDF
	$sql1="SELECT * FROM trabajador WHERE id_trabajador='".$trabajador."'";
	$result1=mysqli_query($conexion, $sql1) or die("No se ha podido realizar la consulta en la tabla de trabajador!");
	$row1=mysqli_fetch_array($result1);
	$nombre=$row1[1];
	$nif=$row1[2];
	$correo=$row1[3];
	$pin=$row1[4];
	$afiliacion=$row1[5];
	$horas_jornada=$row1[6];



// Sacamos las jornadas de este usuario entre las fechas especificadas en las variables	
	$sql = "SELECT * FROM jornada where id_empleado='". $trabajador ."' AND  fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_final."'  ORDER BY fecha";
	$result = mysqli_query($conexion, $sql) or die("No se ha podido realizar la consulta de jornada between!");
	$segundos_totales=0;
	
	$fila_excel=2;

	$mini_nombre=substr($nombre,0,12);

require('library/excel/Classes/PHPExcel.php');
$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()
	->setCreator('Checkin')
	->setTitle('Listado resumen mensual del registro de jornada mes: '.date("m-Y"))
	->setDescription('Listado resumen mensual del registro de jornada mes: '.date("m-Y"))
	->setKeywords($nombre.' '.date("m-Y"))
	->setCategory('Control de horarios');

$objPHPExcel->setActiveSheetIndex(0);	
$objPHPExcel->getActiveSheet()->setTitle('Horas '.$mini_nombre.' '.date("m-Y"));	
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Dia',PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Entrada',PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Salida',PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Pausas',PHPExcel_Cell_DataType::TYPE_STRING);
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Horas ordinarias',PHPExcel_Cell_DataType::TYPE_STRING);	
	
	while ($row = mysqli_fetch_array($result)) {
			// Comparamos el tiempo de entrada con el de salida y sacamos la diferencia!!
			$segundos_entradas_salidas=strtotime($row['hora_salida'])-strtotime($row['hora_entrada']);
			
			
			$segundos_pausa=0;
			//Sacamos los descansos que pertenecen a esta jornada del bucle
		  	$consulta = "SELECT * FROM `descanso` WHERE `id_jornada`=" . $row[0];
          	$resultados = mysqli_query($conexion, $consulta) ;
			while ($fila = mysqli_fetch_array($resultados)) {
				$segundos_pausa_entradas_salidas=strtotime($fila['hora_salida']) - strtotime($fila['hora_entrada']);
				$segundos_pausa=$segundos_pausa_entradas_salidas+$segundos_pausa;
				}
				
			//Imprimimos en el while la fila de celdas
			$objPHPExcel->getActiveSheet()->setCellValue('A'.$fila_excel, $row['fecha']);
			$objPHPExcel->getActiveSheet()->setCellValue('B'.$fila_excel, $row['hora_entrada']);
			$objPHPExcel->getActiveSheet()->setCellValue('C'.$fila_excel, $row['hora_salida']);
			
			//Restamos los segundos de pausas a los segundos de entrada salida.
			$segundos_trabajo_efectivo=$segundos_entradas_salidas-$segundos_pausa;
			$segundos_totales=$segundos_trabajo_efectivo+$segundos_totales;
			 //-------Celda con los tiemos de las pausas----------------. 
			 //Convertimos el tiempo en horas
			 $hora_pausa = floor($segundos_pausa / 3600);
			 $minutos_pausa = floor(($segundos_pausa / 60) % 60);
			 $segundos2_pausa = $segundos_pausa % 60;
			 $tiempo_pausa =$hora_pausa.":".$minutos_pausa.":".$segundos2_pausa;
			 
			 //Imprimimos en el PDF el tiempo en horas.
			 $objPHPExcel->getActiveSheet()->setCellValue('D'.$fila_excel, $tiempo_pausa);
			 
			 //---------Celda con los tiempos de trabajo efectivo ---------
			//Convertimos el tiempo en horas
			 $hora_trabajo = floor($segundos_trabajo_efectivo / 3600);
			 $minutos_trabajo = floor(($segundos_trabajo_efectivo / 60) % 60);
			 $segundos2_trabajo = $segundos_trabajo_efectivo % 60;
			 $tiempo_trabajo = $hora_trabajo.":".$minutos_trabajo.":".$segundos2_trabajo;
			
			//Celda con los tiempos de trabajo efectivo.
			$objPHPExcel->getActiveSheet()->setCellValue('E'.$fila_excel, $tiempo_trabajo);
			
			$fila_excel++;
			 
			
	}
	
	//---------Celda con los tiempos de trabajo efectivo ---------
			//Convertimos el tiempo en horas
			 $hora_trabajo_total = floor($segundos_totales / 3600);
			 $minutos_trabajo_total = floor(($segundos_totales / 60) % 60);
			 $segundos2_trabajo_total = $segundos_totales % 60;
			 $tiempo_trabajo_total = $hora_trabajo_total.":".$minutos_trabajo_total.":".$segundos2_trabajo_total;	
			
	//Imprimimos el total de horas del trabajador	
	$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Total Horas: ');
	$objPHPExcel->getActiveSheet()->setCellValue('F2', $tiempo_trabajo_total);	 
	
	
$mes=date("m-Y");
$nombre_archivo=$nombre." ".$mes;

//Tipo de documento xls
//header('Content-Type: application/vnd.ms-excel'); //Para xls
//header('Content-Disposition: attachment;filename="Excel.xls"'); //Para xls
//$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

//Tipo de documento xlsx
header("Content-Type: application/vnd.openxmlformat-officedocument.spreadsheetml.sheet"); //Para xlsx
header("Content-Disposition: attachment;filename=".$nombre_archivo.".xlsx"); //Para xls

header('Cache-Control: max-age=0');


$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
?>
