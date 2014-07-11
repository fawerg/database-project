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
		<title>Gestione Conti</title>
	</head>
	<body>
		<div class="div-header">HOME BANKING - LA BANCA A CASA TUA</div>
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
								<div class='div-table'>
									Conti di deposito: 
								</div>".print_conti_dep($_SESSION['isLogged'])."
								<div class='div-table'>
									Conti di credito: 
								</div>".print_conti_cred($_SESSION['isLogged'])."
							
							</td>
						</tr>";
					if(!isset($_POST['continua']) && !isset($_POST['crea'])){
						print "<tr>
							<td colspan='7' class='td-containt'>
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
						if(isset($_POST['tipo'])){
							if($_POST['tipo']=='Deposito'){
								print "<tr>
									<td colspan='7' class='td-containt'>
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
													<td><select name='deprif'>".lista_iban($_SESSION['isLogged'], true)."</select></td>
												</tr>
											</table>
											<input type='submit' name='crea' value='Crea conto'>
										<form>
									</td>
								</tr>";
							}
						}
						if(isset($_POST['crea'])){
							(isset($_POST['tetto']) && isset($_POST['deprif'])) ? insert_conto($_POST['ammontare'], $_POST['tetto'], $_POST['deprif'], $_SESSION['isLogged']) : insert_conto($_POST['ammontare'], NULL, NULL, $_SESSION['isLogged']);
							header('Location: conti.php');
						}
					}
				}
				else{
					header('Location: alter-test.php');
				}
			?>
		</table>
	</body>
</html>
