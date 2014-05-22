<html>
	<head>
		<title> Login </title>
	</head>
	
	<body>
		<?php
			include_once ('bdlab-lib-fun.php');
			$db = connection_pgsql();
	
			$sql = "SELECT pwd, nome FROM progetto_db.utente WHERE mail = $1";
			$result = pg_prepare($db, "q", $sql);
			$value = array('m.taddei92@gmail.com');
			$result = pg_execute($db, "q", $value);
			$row = pg_fetch_assoc($result);
			pg_free_result($result);
			pg_close($db);
			print_r ($row);
			
		?>
	</body>
</html>