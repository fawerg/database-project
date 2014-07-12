<?php
include_once('../../conf.php');
  
function user_check($name, $pass) {
//verifica la correttezza di login e password inserite e imposta il contenuto della sessione

	$db = connection_pgsql();
	
	$sql = "SELECT password FROM final_db.utente WHERE mail = $1";
	$result = pg_prepare($db, "q", $sql);
	$value = array($name);
	$result = pg_execute($db, "q", $value);
	$row = pg_fetch_assoc($result);
	
	pg_free_result($result);
	pg_close($db);
	
	update_scheduler();
	
	if($row['password'] == $pass){
		$_SESSION['isLogged'] = $name;
		return true;
	}
	else
		return false;
}

function print_user_data($username){
	
	$db = connection_pgsql();
	
	$sql = "SELECT nome, cognome, indirizzo, mail FROM final_db.utente WHERE mail = $1";
	$result = pg_prepare($db, "q", $sql);
	$value = array($username);
	$result = pg_execute($db, "q", $value);
	$row = pg_fetch_assoc($result);
	$string = "<pre>
Nome: ".$row['nome']."
Cognome: ".$row['cognome']."
Indirizzo: ".$row['indirizzo']."
E-mail: ".$row['mail']."
			</pre>";
	pg_free_result($result);
	pg_close($db);
	
	return $string;

}

function print_conti($username){
	$db = connection_pgsql();
	
	$sql= "SELECT iban, tipologia FROM final_db.conto WHERE mail = $1";
	$result= pg_prepare($db , "q", $sql);
	$value = array($username);
	$result= pg_execute($db, "q", $value);
	$s="";
	$i=1;
	while($row = pg_fetch_assoc($result)){
		$s.="<pre>Conto".$i.": 
			Iban: ".$row['iban']."
			Tipologia: ".$row['tipologia']."</pre>";
		$i++;
	}
	pg_free_result($result);
	pg_close($db);
	return $s;
}

function print_bilanci($username, $id){
	$db = connection_pgsql();
	
	if($id==NULL){
		$sql= "SELECT id,disponibilita, data_scadenza, iban, nome FROM final_db.bilancio NATURAL JOIN final_db.categoria_bilancio WHERE mail = $1";
		$result= pg_prepare($db , "q", $sql);
		$value = array($username);
		$result= pg_execute($db, "q", $value);
		$s="";
		while($row = pg_fetch_assoc($result)){
			$s.="<pre>Identificativo:".$row['id']."
Disponibilità: ".$row['disponibilita']."
Data Scadenza: ".$row['data_scadenza']."
Conto Associato: ".$row['iban']."
Categoria: ".$row['nome']."</pre>";
		}
		pg_free_result($result);
		
	}
	else{
		$sql1= "SELECT id,disponibilita, data_scadenza, iban, nome FROM final_db.bilancio NATURAL JOIN final_db.categoria_bilancio WHERE mail = $1 AND id=$2";
		$result1= pg_prepare($db , "l", $sql1);
		$value1 = array($username, $id);
		$result1= pg_execute($db, "l", $value1);
		$s="";
		while($row = pg_fetch_assoc($result1)){
			$s.="<pre>Identificativo:".$row['id']."
Disponibilità: ".$row['disponibilita']."
Data Scadenza: ".$row['data_scadenza']."
Conto Associato: ".$row['iban']."
Categoria: ".$row['nome']."</pre>";
		}
		pg_free_result($result1);
	}
	
	
	pg_close($db);
	return $s;
	
}

function print_transazioni($username){
	$db = connection_pgsql();
	
	$sql= "SELECT * FROM final_db.transazione WHERE mail = $1";
	$result= pg_prepare($db , "q", $sql);
	$value = array($username);
	$result= pg_execute($db, "q", $value);
	$s="";
	while($row = pg_fetch_assoc($result)){
		$s.="<pre>Data: ".$row['data_transazione']." 
Iban: ".$row['iban']."
Ammontare: ".$row['entita_economica']."
Descrizione: ".$row['descrizione']."
Categoria: ".$row['nome']."</pre>";
	}
	pg_free_result($result);
	pg_close($db);
	return $s;
}

function print_conti_dep($username){
	$db = connection_pgsql();
	
	$sql= "SELECT iban, ammontare FROM final_db.conto WHERE mail = $1 AND tipologia='Deposito'";
	$result= pg_prepare($db , "q", $sql);
	$value = array($username);
	$result= pg_execute($db, "q", $value);
	$s="";
	$i=1;
	while($row = pg_fetch_assoc($result)){
		$s.="<pre>Conto".$i.": 
			Iban: ".$row['iban']."
			Disponibilità: ".$row['ammontare']."
		</pre>";
		$i++;
	}
	pg_free_result($result);
	pg_close($db);
	return $s;	
}

function print_conti_cred($username){
	$db = connection_pgsql();
	
	$sql= "SELECT iban, ammontare , tetto_max, deposito_riferimento  FROM final_db.conto_credito NATURAL JOIN final_db.conto WHERE mail = $1";
	$result= pg_prepare($db , "q", $sql);
	$value = array($username);
	$result= pg_execute($db, "q", $value);
	$s="";
	$i=1;
	while($row = pg_fetch_assoc($result)){
		$s.="<pre>Conto".$i.": 
			Iban: ".$row['iban']."
			Disponibilità: ".$row['ammontare']."
			Tetto: ".$row['tetto_max']."
			Iban conto deposito associato: ".$row['deposito_riferimento']."
		</pre>";
		$i++;
	}
	pg_free_result($result);
	pg_close($db);
	return $s;
}

function insert_conto($ammontare, $tetto, $deprif, $mail){
	$db = connection_pgsql();
	
	$string = 'IT';
	for($i=0; $i<30; $i++)
		$string .= rand(0, 9);
	if($tetto != NULL && $deprif != NULL){
		$sql = "INSERT INTO final_db.conto_credito VALUES ($1, $2, $3)";
		$result = pg_prepare($db, 'q', $sql);
		$value = array($string, $tetto, $deprif);
		$result = pg_execute($db, 'q', $value);		
	} else {
		$sql = "INSERT INTO final_db.conto VALUES ($1, $2, $3, $4)";
		$result = pg_prepare($db, 'q', $sql);
		$value = array($string, $ammontare, 'Deposito', $mail);
		$result = pg_execute($db, 'q', $value);
	}
	pg_free_result($result);
	pg_close($db);
}

function remove_conto($mail, $iban){
	$db=connection_pgsql();
	$sql="DELETE FROM final_db.conto WHERE mail=$1 AND iban=$2";
	$result=pg_prepare($db, "q", $sql);
	$value=array($mail, $iban);
	$result=pg_execute($db, "q", $value);
	
	pg_free_result($result);
	pg_close($db);
}

function insert_transazione($descrizione, $ammontare, $iban, $mail, $categoria){
	$db = connection_pgsql();

	
	$sql = "INSERT INTO final_db.transazione (descrizione, entita_economica, iban, mail, nome) VALUES ($1, $2, $3, $4, $5)";
	$result = pg_prepare($db, 'q', $sql);
	$value = array($descrizione, $ammontare, $iban, $mail, $categoria);
	$result = pg_execute($db, 'q', $value);
		
	pg_free_result($result);
	pg_close($db);
}

function insert_categoria($nome, $tipo, $mail, $padre){
	$db = connection_pgsql();

	
	$sql = "INSERT INTO final_db.categoria (nome, tipo, mail, nome_padre, mail_padre) VALUES ($1, $2, $3, $4, $5)";
	$result = pg_prepare($db, 'q', $sql);
	if($tipo == "Spesa")
		$value = array($nome, '-', $mail, $padre, $mail);
	else
		$value = array($nome, '+', $mail, $padre, $mail);
	$result = pg_execute($db, 'q', $value);
		
	pg_free_result($result);
	pg_close($db);
}

function lista_id($mail){
	$db = connection_pgsql();
	$sql= "SELECT id FROM final_db.bilancio WHERE mail=$1";
 	$result=pg_prepare($db, "q", $sql);
 	$value=array($mail);
 	$result=pg_execute($db, "q", $value);
 	$string="";
 	while($row=pg_fetch_assoc($result)){
 		$string.="<option>".$row['id']."</option>";
 	}	
 	pg_free_result($result);
 	pg_close($db);
 	return $string;
}


function lista_iban($mail, $dep_true){
	$db = connection_pgsql();
	if($dep_true){
		$sql= "SELECT iban FROM final_db.conto WHERE (mail = $1 AND tipologia = $2)";
		$result= pg_prepare($db , "q", $sql);
		$value = array($mail, 'Deposito');
	}
	else{
		$sql= "SELECT iban FROM final_db.conto WHERE mail = $1";
		$result= pg_prepare($db , "q", $sql);
		$value = array($mail);
	}
	$result = pg_execute($db, "q", $value);
	$string = '';
	while($row = pg_fetch_assoc($result)){
		$string .= '<option>'.$row['iban'].'</option>';
	}
	pg_free_result($result);
	pg_close($db);
	return $string;
}

function lista_categorie($mail, $tipo){
	$db = connection_pgsql();
	
	$sql= "SELECT nome FROM final_db.categoria WHERE (mail = $1 AND tipo = $2)";
	$result= pg_prepare($db , "q", $sql);
	$value = array($mail, $tipo);
	$result = pg_execute($db, "q", $value);
	
	$string = '';
	while($row = pg_fetch_assoc($result)){
		$string .= '<option>'.$row['nome'].'</option>';
	}
	
	pg_free_result($result);
	pg_close($db);
	return $string;
}

function lista_categorie_checkbox($mail, $tipo){
	$db = connection_pgsql();
	
	$sql= "SELECT nome FROM final_db.categoria WHERE (mail = $1 AND tipo = $2)";
	$result= pg_prepare($db , "q", $sql);
	$value = array($mail, $tipo);
	$result = pg_execute($db, "q", $value);
	$string = '';
	while($row = pg_fetch_assoc($result)){
		$string .= '<input type="checkbox" name="'.$row['nome'].'" value="'.$row['nome'].'">'.$row['nome'].'</option>';
	}
	
	pg_free_result($result);
	pg_close($db);
	return $string;
}

function change_password($mail, $ps ){
	$db=connection_pgsql();
	$sql="UPDATE final_db.utente SET password=$2 WHERE mail=$1";
	$result= pg_prepare($db, "q", $sql);
	$value=array($mail, $ps);
	$result=pg_execute($db, "q", $value);
	pg_free_result($result);
	pg_close($db);
}

function change_name($username, $name){
	$db=connection_pgsql();
	$sql="UPDATE final_db.utente SET nome=$2 WHERE mail=$1";
	$result= pg_prepare($db, "q", $sql);
	$value=array($username, $name);
	$result=pg_execute($db, "q", $value);
	pg_free_result($result);
	pg_close($db);
	
}

function change_surname($username, $surname){
	$db=connection_pgsql();
	$sql="UPDATE final_db.utente SET cognome=$2 WHERE mail=$1";
	$result= pg_prepare($db, "q", $sql);
	$value=array($username, $surname);
	$result=pg_execute($db, "q", $value);
	pg_free_result($result);
	pg_close($db);
	
}

function change_address($username, $address){
	$db=connection_pgsql();
	$sql="UPDATE final_db.utente SET indirizzo=$2 WHERE mail=$1";
	$result= pg_prepare($db, "q", $sql);
	$value=array($username, $address);
	$result=pg_execute($db, "q", $value);
	pg_free_result($result);
	pg_close($db);
	
}

function change_mail($username, $mail){
	$db=connection_pgsql();
	$sql="UPDATE final_db.utente SET mail=$2 WHERE mail=$1";
	$result= pg_prepare($db, "q", $sql);
	$value=array($username, $mail);
	$result=pg_execute($db, "q", $value);
	$_SESSION['isLogged']=$mail;
	pg_free_result($result);
	pg_close($db);
	
}

function insert_bilancio($disp, $val, $data, $iban, $mail, $categoria){
	$db=connection_pgsql();
	$t='';
	for($i=0 ; $i<8; $i++){
		$t.=rand(0,9);
	}

	$sql= "INSERT INTO final_db.bilancio (id, disponibilita, valore_iniziale, data_scadenza, iban, mail) VALUES ($1, $2, $3, $4, $5, $6)";
	$result=pg_prepare($db, "q", $sql);
	$value=array($t, $disp, $val, $data, $iban, $mail );
	$result= pg_execute($db, "q", $value);
	pg_free_result($result);
	
	$sql1="INSERT INTO final_db.categoria_bilancio VALUES($1, $2, $3)";
	$result1=pg_prepare($db, "p", $sql1);
	$value1=array($t,$mail, $categoria );
	$result1=pg_execute($db, "p", $value1);
	pg_free_result($result1);
	pg_close($db);
}

function remove_bilancio($mail, $id){
	$db=connection_pgsql();
	$sql="DELETE FROM final_db.bilancio WHERE mail=$1 AND id=$2";
	$result=pg_prepare($db, "q", $sql);
	$value=array($mail, $id);
	$result=pg_execute($db, "q", $value);
	
	$sql="DELETE FROM final_db.categoria_bilancio WHERE mail=$1 AND id=$2";
	$result=pg_prepare($db, "p", $sql);
	$value=array($mail, $id);
	$result=pg_execute($db, "p", $value);
	
	pg_free_result($result);
	pg_close($db);
}

function saldo_contabile($mail, $iban, $data1, $data2){
	$db = connection_pgsql();
	
	$sql= "CREATE OR REPLACE  VIEW final_db.saldo AS
			SELECT *
			FROM final_db.conto NATURAL JOIN final_db.transazione
			WHERE mail = '".$mail."' AND iban = '".$iban."' AND data_transazione::date >= '".$data1."' AND data_transazione::date <= '".$data2."'
			ORDER BY data_transazione ASC";
	$result = pg_prepare($db , "q", $sql);
	$value = array();
	$result = pg_execute($db, "q", $value);
				
	$sql= "SELECT *
			FROM final_db.saldo NATURAL JOIN final_db.categoria";
	$result = pg_prepare($db , "p", $sql);
	$value = array();
	$result = pg_execute($db, "p", $value);
				
	$string = "";
	$parziale = 0;
	while($row = pg_fetch_assoc($result)){
		$estratto = $row['ammontare'];
		$string .= "<tr>
						<td class='td-rapporti'>".date("d-m-Y", strtotime($row['data_transazione']))."</td>
						<td class='td-rapporti'>".$row['descrizione']."</td>
						<td class='td-rapporti'>".$row['nome']."</td>
						<td class='td-rapporti' text-align='right'>".$row['tipo']."</td>
						<td class='td-rapporti' text-align='right'>".$row['entita_economica']."</td>
					</tr>";
		$row['tipo'] == "+" ? $parziale += $row['entita_economica'] : $parziale -= $row['entita_economica'];
	}
	$string .= "<tr>
					<td colspan='3'> Totale Parziale </td>
					<td class='td-rapporti' text-align='right'>";
	if($parziale >= 0)
	  	$string .= "+" ; 
	else{
	  	$string .= "-";
		$parziale -= 2*$parziale;
	}  	
	$string .="</td>
					<td  class='td-rapporti' text-align='right'>".$parziale."</td>
				</tr>
				<tr>
					<td colspan='3'> Estratto Conto Attuale </td>
					<td class='td-rapporti' text-align='right'> + </td>
					<td class='td-rapporti' text-align='right'>".$estratto."</td>
				</tr>";
				
	pg_free_result($result);
	pg_close($db);
				
	return $string;			
}

function saldo_bilancio($mail, $id, $d1, $d2){
	$db = connection_pgsql();

	$sql= "CREATE OR REPLACE  VIEW final_db.saldo_bilancio AS
			SELECT *
			FROM final_db.bilancio NATURAL JOIN final_db.categoria_bilancio NATURAL JOIN final_db.transazione
			WHERE mail = '".$mail."' AND id = '".$id."' AND data_transazione::date >= '".$d1."' AND data_transazione::date <= '".$d2."'
			ORDER BY data_transazione ASC";
	$result = pg_prepare($db , "q", $sql);
	$value = array();
	$result = pg_execute($db, "q", $value);
	

	$sql= "SELECT data_transazione,nome,disponibilita, data_inizio, data_scadenza,  descrizione ,tipo,  entita_economica FROM final_db.saldo_bilancio NATURAL JOIN final_db.categoria";
	$result = pg_prepare($db , "p", $sql);
	$value = array();
	$result = pg_execute($db, "p", $value);
	$string="";
	
	while($row=pg_fetch_assoc($result)){
		$string.=	"<tr>
					<td class='td-rapporti'>".date("d-m-Y", strtotime($row['data_transazione']))."</td>
					<td class='td-rapporti'>".$row['nome']."</td>
					<td class='td-rapporti'>".$row['descrizione']."</td>
					<td text-align='right' class='td-rapporti' text-align='right'>".$row['tipo']."</td>
					<td  class='td-rapporti' text-align='right'>".$row['entita_economica']."</td>
				</tr>";
				$disp=$row['disponibilita'];
				$di=$row['data_inizio'];
				$df=$row['data_scadenza'];
	}
	pg_free_result($result);
	pg_close($db);
	$string.=print_bilanci($mail, $id);
	
	return $string;
}

function percentuale_spesa($mail, $d1, $d2){
	$db = connection_pgsql();
	$sql="";
}

function user_logout() {
//disconnette l'utente eliminando il contenuto della sessione
    
    unset ($_SESSION['isLogged']);

}

function update_scheduler(){
	$db = connection_pgsql();

	$sql = "DELETE FROM final_db.scheduler WHERE id = '0'";
	print $sql."\n";
	$result = pg_prepare($db, "delete", $sql);
	$data = array();
	$result = pg_execute($db, "delete", $data);
	
	$sql = "UPDATE final_db.scheduler SET id = '0' WHERE id = '1'";
	print $sql."\n";
	$result = pg_prepare($db, "update", $sql);
	$data = array();
	$result = pg_execute($db, "update", $data);
	
	$date = getdate();
	$sql = "INSERT INTO final_db.scheduler VALUES ('1', $1, $2, $3)";
	print $sql."\n";
	$result = pg_prepare($db, "insert", $sql);
	$data = array($date['mday'], $date['mon'], $date['year']);
	$result = pg_execute($db, "insert", $data);
	pg_free_result($result);
	pg_close($db);
}

function connection_pgsql() {
//apre una connessione con il DBMS postgreSQL le cui coordinate sono definite in conf.php
    
    $connection = "host=".myhost." dbname=".mydb." user=".myuser." password=".mypsw;
	
    return pg_connect ($connection);
    
}
?>

