<?PHP
include_once("C:/wamp64/www/gentelella-master/production/produit.php");
if (isset($_POST['code']) and isset($_POST['nom']) and isset($_POST['qte']) and isset($_POST['alerte']) and isset($_POST['prix'])and isset($_POST['date']) ){
$produit1=new PRODUIT($_POST['code'],$_POST['qte'],$_POST['nom'],$_POST['date'],$_POST['prix'],$_POST['alerte']);
//Partie2
/*

}
*/
//Partie3
$produit1C=new PRODUITC();
$produit1C->AJT_PRDT($produit1);
header('Location: tables.php');

}else{
	echo "vérifier les champs";
}
//*/

?>
