<?php
if (isset($_POST['code']))
$codee=$_POST['code_ini'];




if (isset($codee)
{
$produitC=new PRODUITC();
  $result=$produitC->RCPR_PRDT($codee);
foreach($result as $row){
  $code=$row['CODE'];
  $nom=$row['NOM'];
  $qte=$row['QUANTITE'];
  $alerte=$row['ALERTE'];
  $prix=$row['PRIX'];
  $date=$row['DATE_ARV'];
}
}
header('Location: tables.php');
?>
