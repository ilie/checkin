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
  CONNECT TO BBDD
  -------------------------------------------------------------------------------------
 */
//Using this function.
require('conexion.php');

//Avoiding unregistered users to access tih page by pasting the URL inte the search bar

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

//Sacamos los datos de la empresa para ponerlos en el PDF
	$sql2="SELECT * FROM empresa";
	$result2=mysqli_query($conexion, $sql2) or die("No se ha podido realizar la consulta en la tabla de empresa!");
	$row2=mysqli_fetch_array($result2);
	$empresa=$row2[1];
	$cif=$row2[2];
	$direccion=$row2[3];
	$pais=$row2[4];
	$codigo_postal=$row2[5];
	$telefono=$row2[6];
	$correo_e=$row2[7];
	$pass=$row2[8];
	
/*
  -------------------------------------------------------------------------------------
  EMPEZAMOS A CONSTRUIR EL PDF
  -------------------------------------------------------------------------------------
 */	
	$pdf = new FPDF();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',16); 
	$pdf->Cell(0,10,'Listado resumen mensual del registro de jornada',0,1,'C'); //Titulo  a partir de aqui voy a bajar el tamaño de la letra a 14
	$pdf->SetFont('Arial','B',12);
/*
----------------CABECERA--------------------
*/
	//En la primera fila creamos los titulos de las cajas
	$pdf->Cell(25,7,'Empresa: ','L T ','L');
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(70,7,$empresa,'T R','L');
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(30,7,'Trabajador: ','L T ','L');
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(65,7,$nombre,'T R',1,'L');
	
	//Segunda Fila de datos /////////////////
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(25,6,'C.I.F / N.I.F: ','L','L');
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(70,6,$cif,'R','L');
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(30,6,'NIF: ','L','L');
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(65,6,$nif,'R',1,'L');
	
	//Tercera fila  Fila de datos /////////////////
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(25,6,'Dirección: ','L','L');
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(70,6,$direccion,'R','L');
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(30,6,'Nº Afiliación: ','L','L');
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(65,6,$afiliacion,'R',1,'L');
	
	//Cuarta fila  Fila de datos /////////////////
	/*
----------------CABECERA--------------------
*/
	$pdf->Cell(25,6,'C.C.C. : ','L B','L');
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(70,6,'','R B','L');
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(30,6,'Año mes: ','L B','L');
	$pdf->SetFont('Arial','',11);
	$pdf->Cell(65,6,date("Y-m"),'R B',1,'L');
	
/*
---------------- FIN CABECERA --------------------
*/	
	//Salto de linea con una altura de 5
	$pdf->Cell(0,5,'',0,1,'C');	

/*
----------------Tabla de horas--------------------
*/
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(35,6,'Dia',1,'C'); $pdf->Cell(40,6,'Entrada',1,'C'); $pdf->Cell(40,6,'Salida',1,'C'); $pdf->Cell(35,6,'Pausas',1,'C'); $pdf->Cell(40,6,'Horas ordinarias',1,1,'C');
	
// Sacamos las jornadas de este usuario entre las fechas especificadas en las variables	
	$sql = "SELECT * FROM jornada where id_empleado='". $trabajador ."' AND  fecha BETWEEN '".$fecha_inicio."' AND '".$fecha_final."'  ORDER BY fecha";
	$result = mysqli_query($conexion, $sql) or die("No se ha podido realizar la consulta de jornada between!");
	
	$segundos_totales=0;
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
			$pdf->SetFont('Arial','',11);
			$pdf->Cell(35,6,$row['fecha'],1,'C'); 
		  	$pdf->Cell(40,6,$row['hora_entrada'],1,'C'); 
		  	$pdf->Cell(40,6,$row['hora_salida'],1,'C'); 
			
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
			 $pdf->Cell(35,6,$tiempo_pausa,1,'C');
			
			//---------Celda con los tiempos de trabajo efectivo ---------
			//Convertimos el tiempo en horas
			 $hora_trabajo = floor($segundos_trabajo_efectivo / 3600);
			 $minutos_trabajo = floor(($segundos_trabajo_efectivo / 60) % 60);
			 $segundos2_trabajo = $segundos_trabajo_efectivo % 60;
			 $tiempo_trabajo = $hora_trabajo.":".$minutos_trabajo.":".$segundos2_trabajo;
			
			//Celda con los tiempos de trabajo efectivo.
		  	$pdf->Cell(40,6,$tiempo_trabajo,1,1,'L'); /*Aqui hago un salto de linea para que las celdas se pongan por debajo*/  
		
		}
			

	//Salto de linea con una altura de 5 
	$pdf->Cell(0,5,'',0,1,'C');	


		
	//---------Celda con los tiempos de trabajo efectivo ---------
			//Convertimos el tiempo en horas
			 $hora_trabajo_total = floor($segundos_totales / 3600);
			 $minutos_trabajo_total = floor(($segundos_totales / 60) % 60);
			 $segundos2_trabajo_total = $segundos_totales % 60;
			 $tiempo_trabajo_total = $hora_trabajo_total.":".$minutos_trabajo_total.":".$segundos2_trabajo_total;	
		$pdf->SetFont('Arial','B',12);	
	//Imprimimos el total de horas del trabajador		 
	$pdf->SetX(125);	$pdf->Cell(35,6,'Total Horas: ',1,'C'); 
		  	$pdf->Cell(40,6,$tiempo_trabajo_total,1,1,'L'); /*Aqui hago un salto de linea para que las celdas se pongan por debajo*/ 
	$pdf->SetFont('Arial','',11);
	
	// FOOTER -----------------------------
			$pdf->SetY(249);
			$pdf->Cell(40,10, 'Firma de la empresa: ',0,0,'L'); $pdf->SetX(127); $pdf->Cell(40,10, 'Firma del Trabajador: ',0,1,'L');
			$pdf->Cell(40,6, 'En Huesca a: '.date("d-m-Y"),0,1,'L'); 
			$pdf->SetFont('Arial','',6);
			$pdf->Cell(0,2,'Registro realizado en cumplimiento de la letra h) del artículo 1 del R.D.-Ley 16/2013, de 20 de diciembre por el que se modifica el artículo 12.5 del E.T., por el que se establece que “La jornada de',0,1,'L');
			$pdf->Cell(0,2,'los trabajadores a tiempo parcial se registrará día a día y se totalizará mensualmente, entregando copia al trabajador, junto con el recibo de salarios, del resumen de todas las horas realizadas en',0,1,'L');
			$pdf->Cell(0,2,'cada mes, tanto de las ordinarias como de las complementarias en sus distintas modalidades.',0,1,'L');
			$pdf->Cell(0,4,'El empresario deberá conservar los resúmenes mensuales de los registros de jornada durante un período mínimo de cuatro años. El incumplimiento empresarial de estas obligaciones de registro',0,1,'L');
			$pdf->Cell(0,1,'tendrá por consecuencia jurídica la de que el contrato se presuma celebrado a jornada completa, salvo prueba en contrario que acredite el carácter parcial de los servicios.',0,1,'L');
	// FOOTER -----------------------------
	
$pdf->Output('',$nombre.'  '.date("m-Y"));
?>
