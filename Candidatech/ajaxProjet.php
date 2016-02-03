<?php include ("inc/connection.php"); ?>
<?php
	echo "<select name='recherche' required>";
        echo '<option value = ""> </option>';
	if(isset($_POST["idClient"])){
		$res = $GLOBALS['connexion']->query("SELECT * FROM PROJET 
			WHERE CLI_NO=".$_POST["idClient"]." ORDER BY PRO_NOM");
		while($row = mysqli_fetch_assoc($res)){
			echo "<option value='".$row["PRO_NO"]."'>".$row["PRO_NOM"]."</option>";
		}
	}
	echo "</select>";
?>
