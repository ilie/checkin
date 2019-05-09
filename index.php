<!DOCTYPE html>
<head>
<link rel="apple-touch-icon" href="img/IconCkeckIn.png">
<link rel="icon" href="img/IconCkeckIn.png" sizes="16x16" type="image/png">

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, minimum-scale=0.6, maximum-scale=1.0" />
<title>Login | CheckIn VLEC</title>
<link rel="stylesheet" media="screen" href="css/estilos_index.css"/>
</head>
<body>
	<header>
    <img src="img/LogoCkeckIn.jpg" alt="LogoCheckIn">
    <hr>
    </header>
	<section>
 		<H1>Please Login</H1>
			<form class="formulario_login" method='POST' action='login.php'>
				<table align="center">
            		<tr>
                		<td colspan="4" id="login_information">Login Information</td>
                	</tr>
					<tr>
						<td>Username: *</td>
						<td><input  required="required" name="username" type="email" placeholder="Example: yourname@vlec.es" autofocus ></td>
					</tr>
					<tr>
						<td>Password: *</td>
						<td><input  required="required" name="pwd" type="password"></td>
					</tr>
					<tr>
						<td></td>
						<td>
							<input  required="required"  value="Login" type="submit">
						</td>
					</tr>
		   		</table>
			</form>
    </section>
        
</body>
</html>