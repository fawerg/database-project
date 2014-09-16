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
	
	if($row['password'] == $pass && !(strlen($pass) == 0)){
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
		$sql= "SELECT id, disponibilita, data_scadenza, iban, nome FROM final_db.bilancio NATURAL JOIN final_db.categoria_bilancio WHERE mail = $1";
		$result= pg_prepare($db , "q", $sql);
		$value = array($username);
		$result = pg_execute($db, "q", $value);
		$s = "";
		$row = pg_fetch_assoc($result);
		while($row){
			$id = $row['id'];
			$s.="<pre>Identificativo:".$row['id']."
Disponibilità: ".$row['disponibilita']."
Data Scadenza: ".$row['data_scadenza']."
Conto Associato: ".$row['iban']."
Categorie:";
			while($id == $row['id']){
				$s .= " ".$row['nome'];
				$row = pg_fetch_assoc($result);
			}
			$s .= "</pre>";
		}
		pg_free_result($result);
		
	}
	else{
		$sql1= "SELECT id,disponibilita, data_scadenza, iban, nome FROM final_db.bilancio NATURAL JOIN final_db.categoria_bilancio WHERE mail = $1 AND id=$2";
		$result1= pg_prepare($db , "l", $sql1);
		$value1 = array($username, $id);
		$result1= pg_execute($db, "l", $value1);
		$s="";
		$row = pg_fetch_assoc($result1);
		while($row){
			$id = $row['id'];
			$s.="<pre>Identificativo:".$row['id']."
Disponibilità: ".$row['disponibilita']."
Data Scadenza: ".$row['data_scadenza']."
Conto Associato: ".$row['iban']."
Categorie:";
			while($id == $row['id']){
				$s .= " ".$row['nome'];
				$row = pg_fetch_assoc($result1);
			}
			$s .= "</pre>";
		}
		pg_free_result($result1);
	}
	
	
	pg_close($db);
	return $s;
	
}

function print_transazioni($username){
	$db = connection_pgsql();
	
	$sql= "SELECT data_transazione, iban, entita_economica, descrizione, nome, tipo, tipologia FROM final_db.transazione NATURAL JOIN final_db.categoria WHERE mail = $1";
	$result= pg_prepare($db , "q", $sql);
	$value = array($username);
	$result= pg_execute($db, "q", $value);
	$s="";
	while($row = pg_fetch_assoc($result)){
		$s.="<pre>Data: ".$row['data_transazione']." 
Iban: ".$row['iban']."
Ammontare: ".$row['tipo']."".$row['entita_economica']."
Descrizione: ".$row['descrizione']."
Categoria: ".$row['nome'];
		if($row['tipologia']=='n'){
			$s.="<br>Tipologia: Normale</pre>";	
		}
		else{
			$s.="<br>Tipologia: Programmata</pre>";	
		}
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

function random_n($n){
	$string="";
	$i=$n;
	while($i>0){
		$string.=rand(0, 9);
		$i--;
	}
	return $string;
}

function insert_conto($ammontare, $tetto, $deprif, $mail){
	$db = connection_pgsql();
	$bool=true;
	$string = 'IT';
	while($bool){
		$bool=false;
		$sql = "SELECT iban FROM final_db.conto";
		$result = pg_prepare($db, 'p', $sql);
		$value = array();
		$result = pg_execute($db, 'p', $value);
		$string.=random_n(30);
		while($row=pg_fetch_assoc($result)){
			if($row['iban']==$string){
			 	$bool=true;
			}
		}	
	}
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

function insert_transazione($descrizione, $ammontare, $iban, $mail, $categoria,$data1, $tipo){
	$db = connection_pgsql();
	if($tipo==NULL && $data1== NULL){
		$sql = "INSERT INTO final_db.transazione (descrizione, entita_economica, iban, mail, nome) VALUES ($1, $2, $3, $4, $5)";
		$result = pg_prepare($db, 'q', $sql);
		$value = array($descrizione, $ammontare, $iban, $mail, $categoria);
		$result = pg_execute($db, 'q', $value);
	}
	else{
		$sql = "INSERT INTO final_db.transazione (data_transazione, descrizione, entita_economica, iban, mail, nome, type) VALUES ($1, $2, $3, $4, $5, $6, $7)";
		$result = pg_prepare($db, 'q', $sql);
		$date = date_create();
		$data= date("Y-m-d H:i:s.u");
		echo $data;
		$value = array($data, $descrizione, $ammontare, $iban, $mail, $categoria, $tipo);
		$result = pg_execute($db, 'q', $value);
		
		
		$sql = "INSERT INTO final_db.transazione_programmata (data_transazione, data_operativa, iban) VALUES ($1, $2, $3)";
		$result = pg_prepare($db, 'p', $sql);
		$value = array($data, $data1, $iban);
		$result = pg_execute($db, 'p', $value);
	}
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
	$i = 4;
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

function insert_bilancio($disp, $val, $data, $iban, $mail, $categorie){
	$db=connection_pgsql();
	$t="";
	$bool=true;
	while($bool){
		$bool=false;
		$sql = "SELECT id FROM final_db.bilancio";
		$result = pg_prepare($db, 'l', $sql);
		$value = array();
		$result = pg_execute($db, 'l', $value);
		$t.=random_n(8);
		while($row=pg_fetch_assoc($result)){
			if($row['id']==$t){
			 	$bool=true;
			}
		}	
	}
	$sql= "INSERT INTO final_db.bilancio (id, disponibilita, valore_iniziale, data_scadenza, iban, mail) VALUES ($1, $2, $3, $4, $5, $6)";
	$result=pg_prepare($db, "q", $sql);
	$value=array($t, $disp, $val, $data, $iban, $mail );
	$result= pg_execute($db, "q", $value);
	pg_free_result($result);
	
	for($i=0; $i<count($categorie); $i++){
		$sql = "INSERT INTO final_db.categoria_bilancio VALUES($1, $2, $3)";
		$result = pg_prepare($db, "p".$i, $sql);
		$value = array($t, $mail, $categorie[$i]);
		$result = pg_execute($db, "p".$i, $value);
		pg_free_result($result);
	}
	
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

function remove_transazioni($mail){
	$db=connection_pgsql();
	$sql="DELETE FROM final_db.transazione WHERE mail=$1";
	$result= pg_prepare($db, "q", $sql);
	$value=array($mail);
	$result=pg_execute($db, "q", $value);
	
	pg_free_result($result);
	pg_close($db);
}	

function statistiche_conti($mail){
	$db= connection_pgsql();
	$sql= "CREATE OR REPLACE VIEW final_db.statistiche_conti AS
			SELECT *
			FROM final_db.conto NATURAL JOIN final_db.transazione
			WHERE mail='".$mail."'";
	$resutlt= pg_prepare($db, "q", $sql);
	$value=array();
	$result=pg_execute($db, "q", $value);
	
	$sql=	"SELECT *
		FROM final_db.statistiche_conti NATURAL JOIN final_db.categoria";
	$result=pg_prepare($db, "p", $sql);
	$result=pg_execute($db, "p", $value);
	
	$nspese=0;
	$nentrate=0;
	$spesa=0;
	$entrata=0;
	
	while($row=pg_fetch_assoc($result)){
		if($row['tipo']=='+'){
			$nentrate++;
			$entrata+=$row['entita_economica'];
		}
		else{
			$nspese++;
			$spesa+=$row['entita_economica'];
		}
	}
	$media =0.00+$spesa/$nspese;
	$string="<tr><td class='td-rapporti'>Entità di spesa media : ".$media." €</td></tr>";
	$media= 0.00+$entrata/$nentrate;
	$string.="<tr><td class= 'td-rapporti'>Entità di entrata media : ".$media." €</td></tr>";
	
	
	pg_close($db);
	$db=connection_pgsql();
	$sql="SELECT nome, SUM(entita_economica)
		FROM final_db.statistiche_conti NATURAL JOIN final_db.categoria
		WHERE tipo='-'
		GROUP BY (nome)
		ORDER BY (sum) DESC";
	$result=pg_prepare($db, "q", $sql);
	$value=array();
	$result=pg_execute($db, "q", $value);
	$i=2;
	$string.="<tr><td class='td-rapporti'>Categorie di spesa più importanti : </td></tr>";
	while(($row=pg_fetch_assoc($result)) && $i >=0){
		$string.="<tr><td class='td-rapporti'>".$row['nome']." (".$row['sum']." €)</td></tr>";
		$i--;
	}
	
	
	$sql="SELECT nome, SUM(entita_economica)
		FROM final_db.statistiche_conti NATURAL JOIN final_db.categoria
		WHERE tipo='+'
		GROUP BY (nome)
		ORDER BY (sum) DESC";
	$result=pg_prepare($db, "p", $sql);
	$result=pg_execute($db, "p", $value);
	$i=2;
	$string.="<tr><td class='td-rapporti'>Categorie di entrata più importanti : </td></tr>";
	while(($row=pg_fetch_assoc($result)) && $i >=0){
		$string.="<tr><td class='td-rapporti'>".$row['nome']." (".$row['sum']." €)</td></tr>";
		$i--;
	}
	pg_free_result($result);
	pg_close($db);
	return $string;
}

function saldo_contabile($mail, $iban, $data1, $data2){
	$db = connection_pgsql();
	
	$sql= "CREATE OR REPLACE  VIEW final_db.saldo AS
			SELECT *
			FROM final_db.conto NATURAL JOIN final_db.transazione 
			WHERE mail = '".$mail."' AND iban = '".$iban."' AND data_transazione::date>='".$data1."' AND data_transazione::date<= '".$data2."' AND type='n'
			ORDER BY data_transazione ASC";
	$result = pg_prepare($db , "q", $sql);
	$value = array();
	
	$result = pg_execute($db, "q", $value);		
	$sql= "SELECT *
			FROM final_db.saldo NATURAL JOIN final_db.categoria";
	$result = pg_prepare($db , "p", $sql);
	$value = array();
	$data=array();
	$result = pg_execute($db, "p", $value);
				
	$string = "";
	$parziale = 0;
	$estratto=0;
	while($row = pg_fetch_assoc($result)){
		$estratto = $row['ammontare'];
		$row['tipo'] == "+" ? $value[date("d-m-y H:i:s",strtotime($row['data_transazione']))]=$parziale+$row['entita_economica'] :$value[date("d-m-y H:i:s",strtotime($row['data_transazione']))]=$parziale-$row['entita_economica'];
		$string .= "<tr>
						<td class='td-rapporti'>".date("d-m-Y", strtotime($row['data_transazione']))."</td>
						<td class='td-rapporti'>".$row['descrizione']."</td>
						<td class='td-rapporti'>".$row['nome']."</td>
						<td class='td-rapporti' align='right'>".$row['tipo']."</td>
						<td class='td-rapporti' align='right'>".$row['entita_economica']."</td>
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
	$_SESSION['array']=$value;
	pg_free_result($result);
	pg_close($db);
				
	return $string;			
}
function saldo_bilancio2($mail, $id, $id2, $d1, $d2){
	$db = connection_pgsql();
	
	$sql= "CREATE OR REPLACE  VIEW final_db.saldo_bilancio AS
			SELECT *
			FROM final_db.bilancio NATURAL JOIN final_db.categoria_bilancio NATURAL JOIN final_db.transazione
			WHERE mail = '".$mail."' AND id = '".$id."' AND data_transazione::date >= '".$d1."' AND data_transazione::date <= '".$d2."'
			ORDER BY data_transazione ASC";
	$result = pg_prepare($db , "q", $sql);
	$value = array();
	$result = pg_execute($db, "q", $value);
	pg_free_result($result);
	$parziale=0;
	$sql= "SELECT data_transazione,nome,disponibilita, data_inizio, data_scadenza,  descrizione ,tipo,  entita_economica FROM final_db.saldo_bilancio NATURAL JOIN final_db.categoria";
	$result = pg_prepare($db , "p", $sql);
	$value = array();
	$result = pg_execute($db, "p", $value);
	$string="<tr>
													<td class='td-rapporti'>Data transazione(gg/mm/yy)</td><td class='td-rapporti'>Descrizione</td><td class='td-rapporti'>Categoria</td><td class='td-rapporti'></td><td class='td-rapporti'>Entità economica (€)</td>
												</tr><table width='100%'>";
	$v=array();
	while($row=pg_fetch_assoc($result)){
		$string.=	"<tr>
					<td class='td-rapporti'>".date("d-m-Y", strtotime($row['data_transazione']))."</td>
					<td class='td-rapporti'>".$row['nome']."</td>
					<td class='td-rapporti'>".$row['descrizione']."</td>
					<td align='right' class='td-rapporti' align='right'>".$row['tipo']."</td>
					<td  class='td-rapporti' text-align='right'>".$row['entita_economica']."</td>
				</tr>";
				$row['tipo']=="+"? $parziale-=$row['entita_economica'] : $parziale+=$row['entita_economica'];
				$row['tipo'] == "+" ? $v[$row['data_transazione']]=$parziale-$row['entita_economica'] :$v[$row['data_transazione']]=$parziale+$row['entita_economica'];
	}
	$_SESSION['array']=$v;
	pg_close($db);
	
	$string.="<tr><td>".print_bilanci($mail, $id)."</td></tr>";
	$db=connection_pgsql();
	$sql= "CREATE OR REPLACE  VIEW final_db.saldo_bilancio AS
			SELECT *
			FROM final_db.bilancio NATURAL JOIN final_db.categoria_bilancio NATURAL JOIN final_db.transazione
			WHERE mail = '".$mail."' AND id = '".$id2."' AND data_transazione::date >= '".$d1."' AND data_transazione::date <= '".$d2."'
			ORDER BY data_transazione ASC";
	$result1 = pg_prepare($db , "q", $sql);
	$value1 = array();
	$result1 = pg_execute($db, "q", $value);
	
	$parziale=0;
	$sql= "SELECT data_transazione,nome,disponibilita, data_inizio, data_scadenza,  descrizione ,tipo,  entita_economica FROM final_db.saldo_bilancio NATURAL JOIN final_db.categoria";
	$result1 = pg_prepare($db , "p", $sql);
	$result1 = pg_execute($db, "p", $value);
	$string.="<tr>
													<td class='td-rapporti'>Data transazione(gg/mm/yy)</td><td class='td-rapporti'>Descrizione</td><td class='td-rapporti'>Categoria</td><td class='td-rapporti'></td><td class='td-rapporti'>Entità economica (€)</td>
												</tr>";
	$v1=array();
	while($row=pg_fetch_assoc($result1)){
		$string.=	"<tr>
					<td class='td-rapporti'>".date("d-m-Y", strtotime($row['data_transazione']))."</td>
					<td class='td-rapporti'>".$row['nome']."</td>
					<td class='td-rapporti'>".$row['descrizione']."</td>
					<td align='right' class='td-rapporti' align='right'>".$row['tipo']."</td>
					<td  class='td-rapporti' text-align='right'>".$row['entita_economica']."</td>
				</tr>";
				$row['tipo']=="+"? $parziale-=$row['entita_economica'] : $parziale+=$row['entita_economica'];
				$row['tipo'] == "+" ? $v1[$row['data_transazione']]=$parziale-$row['entita_economica'] :$v1[$row['data_transazione']]=$parziale+$row['entita_economica'];
	}
	$_SESSION['array1']=$v1;
	pg_free_result($result);
	pg_free_result($result1);
	pg_close($db);
	$string.="<tr><td>".print_bilanci($mail, $id2)."</td></tr>";
	return $string;
}

function saldo_bilancio($mail, $id, $d1, $d2){
	$db = connection_pgsql();
	unset($_SESSION['array']);
	$sql= "CREATE OR REPLACE  VIEW final_db.saldo_bilancio AS
			SELECT *
			FROM final_db.bilancio NATURAL JOIN final_db.categoria_bilancio NATURAL JOIN final_db.transazione
			WHERE mail = '".$mail."' AND id = '".$id."' AND data_transazione::date >= '".$d1."' AND data_transazione::date <= '".$d2."'
			ORDER BY data_transazione ASC";
	$result = pg_prepare($db , "q", $sql);
	$value = array();
	$result = pg_execute($db, "q", $value);
	
	$parziale=0;
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
					<td align='right' class='td-rapporti' align='right'>".$row['tipo']."</td>
					<td  class='td-rapporti' text-align='right'>".$row['entita_economica']."</td>
				</tr>";
				$row['tipo']=="+"? $parziale-=$row['entita_economica'] : $parziale+=$row['entita_economica'];
				$row['tipo'] == "+" ? $value[$row['data_transazione']]=$parziale-$row['entita_economica'] :$value[$row['data_transazione']]=$parziale+$row['entita_economica'];
	}
	$_SESSION['array']=$value;
	$_SESSSION['disp']=0-$row['disponibilia'];
	pg_free_result($result);
	pg_close($db);
	$string.=print_bilanci($mail, $id);
	
	return $string;
}

function percentuale_spesa($mail, $d1, $d2){
	$db = connection_pgsql();
	$sql="CREATE OR REPLACE VIEW final_db.spese_globali AS
		SELECT *
		FROM final_db. categoria NATURAL JOIN final_db.transazione
		WHERE mail='".$mail."' AND data_transazione::date>='".$d1."' AND data_transazione::date<= '".$d2."'";
	$result=pg_prepare($db, "q", $sql);
	$value=array();
	$result= pg_execute($db, "q", $value);
	
	$sql= "SELECT nome, iban, SUM(entita_economica)
		FROM final_db.spese_globali
		WHERE tipo='-'
		GROUP BY nome, iban";
	$result=pg_prepare($db, "p", $sql);
	$value=array();
	$result=pg_execute($db, "p", $value);
	
	$array_categorie = array();
	$array_quantitativi = array();
	$i = 0;
	$sum = 0;
	$string="";
	while($row = pg_fetch_assoc($result)){
		$j = 0;
		$sum += $row['sum'];
		while($j < $i){
			if($row['nome'] == $array_categorie[$j]){
				$array_quantitativi[$j] += $row['sum'];
				break;
			}
			$j++;
		}
		if($j == $i){
			$array_categorie[$i] = $row['nome'];
			$array_quantitativi[$i] = $row['sum'];
			$i++;
		}
		$string.="
				<tr>
					<td class='td-rapporti'>".$row['iban']."</td>
					<td class='td-rapporti'>".$row['nome']."</td>
					<td></td>
					<td align='right'>-</td>
					<td class='td-rapporti'>".$row['sum']."</td>
				</tr>
			";
	}
	$string .= "<tr>
					<td colspan='5'><hr></td>
				</tr>
				<tr>
					<td class='td-rapporti' colspan='4' align='left'>SPESA TOTALE</td>
					<td class='td-rapporti'>".$sum."</td>
				</tr>";
	$j = 0;
	$v1 = array();
	while($j < $i){
		$v1[$array_categorie[$j]]=(integer)($array_quantitativi[$j]*100)/$sum;
		$string .= "<tr>
						<td colspan='4' align='left'>".$array_categorie[$j]."</td>
						<td class='td-rapporti'>".$array_quantitativi[$j]."(".(integer)($array_quantitativi[$j]*100)/$sum."%)
					</tr>";
		$j++;
	}
	$_SESSION['array']=$v1;
	pg_free_result($result);
	pg_close($db);
	return $string;
	
}

function user_logout() {
//disconnette l'utente eliminando il contenuto della sessione
    
    unset ($_SESSION['isLogged']);

}

function connection_pgsql() {
//apre una connessione con il DBMS postgreSQL le cui coordinate sono definite in conf.php
    
    $connection = "host=".myhost." dbname=".mydb." user=".myuser." password=".mypsw;
	
    return pg_connect ($connection);
	
    
}
?>