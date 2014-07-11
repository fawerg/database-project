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
		<title>Profilo</title>
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
									Dati utente: 
								</div>".print_user_data($_SESSION['isLogged'])."
							
							</td>
						</tr>
						";
						if(!isset($_POST['mod_name']) && !isset($_POST['mod_surname']) && !isset($_POST['mod_address']) && !isset($_POST['mod_mail']) && !isset($_POST['mod_n'])&& !isset($_POST['mod_pwd']) && !isset($_POST['mod_p'])){
							print 
							"<tr>
								<td colspan='7' class='td-containt'>
									<form class=\"padding-el\" method=\"post\" action=\"profilo.php\">
										<table>
											<tr>
												<td><input type='submit' name='mod_name' value='Modifica Nome'/></td>
												<td><input type='submit' name ='mod_surname' value='Modifica Cognome'/></td>
												<td><input type='submit' name ='mod_address' value='Modifica Indirizzo'/></td>
												<td><input type='submit' name='mod_mail' value='Modifica Mail'/ ></td>
												<td><input type='submit' name='mod_pwd' value='Modifica Password'/></td>
											</tr>
										</table>
									</form>
								</td>
							</tr>";
						}
						else if(!isset($_POST['mod_p'])){
							print
								"<tr>
								<td colspan='7' class='td-containt'>
									<form form class=\"padding-el\" method=\"post\" action=\"profilo.php\">
										<table>
											<tr>";
								if(isset($_POST['mod_name'])){
									print "Nuovo Nome: <input type='text' name='mod_n'><input type='submit' value='Continua'>";
									$n="nome";
								}
								if(isset($_POST['mod_surname'])){
									print "Nuovo Cognome: <input type='text' name='mod_c'><input type='submit' value='Continua'>";
									$n="cognome";
								}
								if(isset($_POST['mod_address'])){
									print "Nuovo Indirizzo: <input type='text' name='mod_a'><input type='submit' value='Continua'>";
									$n="indirizzo";
								}
								if(isset($_POST['mod_mail'])){
									print "Nuova Mail: <input type='text' name='mod_m'><input type='submit' value='Continua'>";
									$n="mail";
								}
								if(isset($_POST['mod_pwd'])){
									print "<pre>
Nuova Password:    <input type='password' name='mod_p'>
Conferma Password: <input type='password' name='cmod_p'>
<input type='submit' value='Continua'></pre>";
									$n="password";
								}
								print"
												
											</tr>
										</table>
									</form>
								</td>
							</tr>";
							
						}
						if(isset($_POST['mod_n'])){
							change_name($_SESSION['isLogged'], $_POST['mod_n'] );
							print"
							<meta http-equiv=\"refresh\" content=\"0\">";
						}
						if(isset($_POST['mod_c'])){
							change_surname($_SESSION['isLogged'], $_POST['mod_c'] );
							print"
							<meta http-equiv=\"refresh\" content=\"0\">";
						}
						if(isset($_POST['mod_a'])){
							change_address($_SESSION['isLogged'], $_POST['mod_a'] );
							print"
							<meta http-equiv=\"refresh\" content=\"0\">";
						}
						if(isset($_POST['mod_m'])){
							change_mail($_SESSION['isLogged'], $_POST['mod_m'] );
							print"
							<meta http-equiv=\"refresh\" content=\"0\">";
						}
						if(isset($_POST['mod_p']) && $_POST['cmod_p']!= $_POST['mod_p']){
								print"<tr>
								<td colspan='7' class='td-containt'>
									<form form class=\"padding-el\" method=\"post\" action=\"profilo.php\">
										<table>
											<tr>
<pre>
Nuova Password:    <input type='password' name='mod_p'>
Conferma Password: <input type='password' name='cmod_p'> <p style='color:red;'>Errore, password non corrispondenti!</p>
<input type='submit' value='Continua'></pre>
											</tr>
										</table>
									</form>
								</td>
							</tr>";	
						}
						
						if(isset($_POST['mod_p']) && $_POST['cmod_p']== $_POST['mod_p']){
							change_password($_SESSION['isLogged'], $_POST['mod_p'] );
							print"
							<meta http-equiv=\"refresh\" content=\"0\">";
						}
						
				}
				else{
					header('Location: alter-test.php');
				}
			?>
		</table>
	</body>
</html>