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
		<title>Pagina Test</title>
	</head>
	<body>
		<div class="div-header">BANCHE FOTTI SOLDI - TUTTE INTORNO A VOI</div>
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
								<div class='div-table'>
									Dati utente: 
								</div>".print_user_data($_SESSION['isLogged'])."
							
							</td>
						</tr>
						";
						if(!isset($_POST['mod_name']) && !isset($_POST['mod_surname']) && !isset($_POST['mod_address']) && !isset($_POST['mod_mail'])){
							print 
							"<tr>
								<td colspan='6' class='td-containt'>
									<form form class=\"padding-el\" method=\"post\" action=\"profilo.php\">
										<table>
											<tr>
												<td><input type='submit' name='mod_name' value='Modifica Nome'/></td>
												<td><input type='submit' name ='mod_surname' value='Modifica Cognome'></td>
												<td><input type='submit' name ='mod_address' value='Modifica Indirizzo'></td>
												<td><input type='submit' name='mod_mail' value='Modifica Mail' ></td>
											</tr>
										</table>
									</form>
								</td>
							</tr>";
						}
						else{
							print
								"<tr>
								<td colspan='6' class='td-containt'>
									<form form class=\"padding-el\" method=\"post\" action=\"profilo.php\">
										<table>
											<tr>";
								if(isset($_POST['mod_name'])){
									print "Nuovo Nome: ";
								}
								if(isset($_POST['mod_surname'])){
									print "Nuovo Cognome: ";
								}
								if(isset($_POST['mod_address'])){
									print "Nuovo Indirizzo: ";
								}
								if(isset($_POST['mod_mail'])){
									print "Nuova Mail: ";
								}
								print"
												<input type='text' name='mod'>
											</tr>
										</table>
									</form>
								</td>
							</tr>";
						}
				}
			?>
		</table>
	</body>
</html>
