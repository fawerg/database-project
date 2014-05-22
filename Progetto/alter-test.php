<?php
    ini_set('display_errors','Off');
    define ('LOGINEXPIRE', 300);
    ini_set('session.cookie_lifetime',LOGINEXPIRE);
    session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<link href="alter-style.css" rel="stylesheet" type="text/css" />
		<title>Pagina Test</title>
	</head>
	<body>
		<div class="div-header">BANCHE FOTTI SOLDI - TUTTE INTORNO A VOI</div>
		<table class='table-ext'>
			<?php
				include_once('bdlab-lib-fun.php');
                if ($_SESSION['isLogged'] && !$_GET["mod"]){			
					print "<tr>
						<td class='td-menu-home'>
							<a href='alter-test.php'>H</a>
						</td>
						<td class='td-menu'>
							<a href='test.html'>Gestione Profilo</a>
						</td>
						<td class='td-menu'>
							<a href=\"conti.php\">Gestione Conti</a>
						</td>
						<td class='td-menu'>
							<a href=\"test.html\">Gestione Transazioni</a>
						</td>
						<td class='td-menu'>
							<a href=\"test.html\">Gestione Bilanci</a>
						</td>
						<td class='td-menu'>
							<a href=\"logout.php\">Logout</a>
						</td>
					</tr>
					<tr>
						<td colspan='6' class='td-containt'>
							<div class='div-table'>Dati Personali</div>
							".print_user_data($_SESSION['isLogged'])."
							<div class='div-table'>Conti Aperti</div>
							<pre>
 Iban: IT17X0605502100000001234567		Denaro: 10000.00 €
 Iban: IT17X0605502125700001234567		Denaro: 1000.00 €
							</pre>
							<div class='div-table'>Transazioni Effettuate</div>
							<pre>
 12/11/2013			-753.45 €
 17/11/2013			-198.99 €
 1/12/2013			+1753.76 €
 12/12/2013			-53.45 €
							</pre>
							<div class='div-table'>Bilanci Attivi</div>
						</td>
					</tr>";
				}
				else{
					print "<tr>
						<td colspan='5' class='td-containt'>
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
								<input type=\"submit\" value=\"Login\" />
							</form>
						</td>
					</tr>";
				}
			?>
		</table>
	</body>
</html>
