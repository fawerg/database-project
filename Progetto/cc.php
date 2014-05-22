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
					print "	
						<tr>
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
						</tr>";
					if(!isset($_POST['continua'])){
						print "<tr>
							<td colspan='6' class='td-containt'>
								<div class='div-table'>Dettagli Conto</div>
								<form class=\"padding-el\" method=\"post\" action=\"cc.php\">
									<table>
										<tr>
											<td>Tipologia:</td>
											<td><select name='tipo'><option>Deposito</option><option>Credito</option></select></td>
										</tr>
									</table>	
									<input type='submit' name='continua' value='Coninua'>
								</form>
							</td>
						</tr>";
					}
					else{
						if($_POST['tipo']=='Deposito'){
							print "<tr>
								<td colspan='6' class='td-containt'>
									<div class='div-table'>Dettagli Conto</div>
									<form class='padding-el' method='POST' action='cc.php'>
										<table>
											<tr>
												<td>Ammontare: </td>
												<td><input type='text' name='ammontare'/></td>
											</tr>
										</table>
										<input type='submit' name='crea' value='Crea conto'>
									<form>
								</td>
							</tr>";
						}
						else{
							print "<tr>
								<td colspan='6' class='td-containt'>
									<div class='div-table'>Dettagli Conto</div>
									<form class='padding-el' method='POST' action='cc.php'>
										<table>
											<tr>
												<td>Ammontare:</td>
												<td><input type='text' name='ammontare'/></td>
											</tr>
											<tr>
												<td>Tetto:</td>
												<td><input type='text' name='tetto'/></td>
											</tr>
											<tr>
												<td>Deposito Riferimento:</td>
												<td><input type='text' name='diprif'/></td>
											</tr>
										</table>
										<input type='submit' name='crea' value='Crea conto'>
									<form>
								</td>
							</tr>";
						}
					}
				}
			?>
		</table>
	</body>
</html>
