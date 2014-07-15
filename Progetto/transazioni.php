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
		<title>Transazioni</title>
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
								<div class='div-table'>
									Transazioni:
								</div>
								<div class='div-scroll'>
									".print_transazioni($_SESSION['isLogged'])."
								</div>
							</td>
						</tr>";
						if(!isset($_POST['prog'])){
							print"
							<tr>
								<td colspan='7' class='td-containt'>
									<form class='padding-el' method='post' action='ct.php'>									
										<input type='submit' name='transazione' value='Crea Nuova Transazione' />
									</form>
									<form class='padding-el' method='post' action='ccat.php'>
										<input type='submit' name='categoria' value='Crea Nuova Categoria' />
									</form>
									<form class='padding-el' method='post' action='transazioni.php'>
										<input type='submit' name='prog' value='Crea transazione programmata'>
									</form>
								</td>
							</tr>";
						}
						if(isset($_POST['prog'])){
							print"
							<tr>
								<td colspan='7' class='td-containt'>
									<form name='frmMain' method='post'> 
										<table>
											<tr>
												<td colspan='7' >
												Date: <input type='text' name='txtDate' value='mm/dd/yyyy' size='15' maxlength='10'>  
												<input type='button' name='cmdCal' value='Calendario' onClick=\"javascript:window.open('calendar.php?form=frmMain & field=txtDate','',' top=50 , left=400 , width=175, height=140, menubar=no, toolbar=no ,scrollbars=no ,resizable=yes,status=no'); return false;\"> 
												</td>
												
											</tr>
											<tr>
												<td colspas='7' >
													<input type='submit' name='crea' value='Invia'>
												</td>
											</tr>
										</table>
									</form> 
								</td>
							</tr>
							";

						}
			}
			else{
					header('Location: alter-test.php');
				}
			?>
			
		</table>
	</body>
</html>
