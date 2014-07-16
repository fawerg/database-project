<?php
    ini_set('display_errors','On');
    define ('LOGINEXPIRE', 300);
    ini_set('session.cookie_lifetime',LOGINEXPIRE);
    session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<link href="alter-style.css" rel="stylesheet" type="text/css" />
		<title>Home</title>
	</head>
	<body>
		<div class="div-header">HOME BANKING - LA BANCA A CASA TUA</div>
		<table class='table-ext'>
			<?php
				include_once('bdlab-lib-fun.php');
                	if ($_SESSION['isLogged']){			
					print "<tr>
						<td class='td-menu-home'>
							<a href='alter-test.php'>H</a>
						</td>
						<td class='td-menu'>
							<a href='profilo.php'>Gestione Profilo</a>
						</td>
						<td class='td-menu'>
							<a href=\"conti.php\">Gestione Conti</a>
						</td>
						<td class='td-menu'>
							<a href=\"transazioni.php\">Gestione Transazioni</a>
						</td>
						<td class='td-menu'>
							<a href=\"bilancio.php\">Gestione Bilanci</a>
						</td>
						<td class='td-menu'>
								<a href='rapporti.php'>Rapporti</a>
						</td>
						<td class='td-menu'>
							<a href=\"logout.php\">Logout</a>
						</td>
						
					</tr>
					<tr>
						<td colspan='7' class='td-containt'>
							<div class='div-table'>Dati Personali</div>
							".print_user_data($_SESSION['isLogged'])."
							<div class='div-table'>Conti Aperti</div>
							<pre>".print_conti($_SESSION['isLogged'])."
							</pre>
							<div class='div-table'>Transazioni Effettuate</div>
							<pre>".print_transazioni($_SESSION['isLogged'])."</pre>
							<div class='div-table'>Bilanci Attivi</div>
							<pre>".print_bilanci($_SESSION['isLogged'], NULL)."</pre>
						</td>
					</tr>";
				}
				else{
					print "<tr>
						<td colspan='7' class='td-containt'>
							<div class='div-table'>Autenticazione</div>
							<form class=\"padding-el\" method=\"post\" action=\"check.php\">
								<table>
									<tr>
										<td>Username:</td>
										<td><input type=\"text\" name=\"user\" /></td>
									</tr>
									<tr>
										<td>Password:</td>
										<td><input type=\"password\" name=\"pass\" /></td>
									</tr>
								</table>
								<input type=\"submit\" value=\"Login\"/>    <a href=\"main.php\">Registrati</a>
							</form>
						</td>
					</tr>";
				}
			?>
		</table>
	</body>
</html>
