<?PHP
include_once("C:/wamp64/www/gentelella-master/production/produit.php");
$produitC=new PRODUITC();
if (isset($_POST["code_s"])){
  echo "slm";
	$produitC->SUPP_PRDT($_POST["code_s"]);
	header('Location: tables.php');
}

?>
