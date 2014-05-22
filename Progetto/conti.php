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
		
						<td>
								
						</td>
					</tr>";
				}
			?>
	</body>
</html>
