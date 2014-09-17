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
									Conti di deposito: 
								</div>".print_conti_dep($_SESSION['isLogged'])."					
								<br>
								<div class='div-table'>
									Conti di credito: 
								</div>".print_conti_cred($_SESSION['isLogged'])."
							</td>
						</tr>";
						if(!isset($_POST['delete'])&&!isset($_POST['del_ib'])){
							print"
							<tr>
								<td colspan='7' class='td-containt'>
									<form class='padding-el' method='post' action='cc.php'>									
										<input type='submit' value='Crea Nuovo Conto' />
									</form>
									<form class='padding-el' method='post' action='conti.php'>
										<input type='submit' value='Elimina conto' name='delete'>
									</form>
								</td>
							</tr>
							<tr>
								<td colspan='7' class='td-containt'>
								<div style='color:red;'>
									**Non è possibile eliminare un conto di deposito se è collegato a un conto di credito, in questi casi bisogna prima procedere alla chiusura del conto di credito sopracitato.
								</div>
								</td>
							</tr>";
						}
						if(isset($_POST['delete'])&&!isset($_POST['del_ib'])){
							print"
							<tr>
								<td colspan ='7' class='td-containt'>
									<form class='padding-el' method='post' action='conti.php'>
										Iban: <select name='ib_del'>".lista_iban($_SESSION['isLogged'], false)."</select>
										<input type='submit' name='del_ib' value='Invia'/>
									</form>
								</td>
							</tr>
							";
						}
						if(isset($_POST['del_ib'])){
							remove_conto($_SESSION['isLogged'], $_POST['ib_del']);
							header('Location: conti.php');
						}
				}
				else{
					header('Location: alter-test.php');
				}
			?>
		</table>
	</body>
</html>
