<?php
include_once("C:/wamp64/www/gentelella-master/production/CONFIG.php");


  class MODELE{
  	private $type;
  	function __construct($type){
  		$this->type=$type;
  	}

  	function getType(){
  		return $this->type;
  	}

  	function setType($type){
  		$this->type=$type;}
}

class MODELEC {
	function __construct(){
	}

function AFF_MODELE ($modele){
		echo "Type: ".$modele->getType()."<br>";
	}

	function AJT_MODELE($produit){

		try{
			$db = config::getConnexion();
        $req=$db->prepare("INSERT INTO modele (TYPE) VALUES (:type)");

        $type=$modele->getType();
		$req->bindValue(':type',$type);

            $req->execute();

        }
        catch (Exception $e){
            echo 'Erreur: '.$e->getMessage();
        }

	}

	function AFF_MODELES(){
		//$sql="SElECT * From employe e inner join formationphp.employe a on e.cin= a.cin";
		$sql="SELECT * FROM modele";
		$db = config::getConnexion();
		try{
		$liste=$db->query($sql);
		return $liste;
		}
        catch (Exception $e){
            die('Erreur: '.$e->getMessage());
        }
	}
	function SUPP_MODELE($code){

		try{
			$db = config::getConnexion();
	        $req=$db->prepare("DELETE FROM modele WHERE TYPE=:type");
			$req->bindValue(':type',$type);
            $req->execute();
           // header('Location: index.php');
        }
        catch (Exception $e){
            die('Erreur: '.$e->getMessage());
        }
	}
	function MODF_MODELE($modele,$type){
		$sql="UPDATE modele SET TYPE=:typee WHERE TYPE=:type";

		$db = config::getConnexion();
		//$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
try{
        $req=$db->prepare($sql);
		$typee=$modele->getType();
		$datas = array(':typee'=>$typee, ':type'=>$type);
		$req->bindValue(':typee',$typee);
		$req->bindValue(':type',$type);


            $s=$req->execute();

           // header('Location: index.php');
        }
        catch (Exception $e){
            echo " Erreur ! ".$e->getMessage();
   echo " Les datas : " ;
  print_r($datas);
        }

	}
	function RCPR_MODEME($type){
		$sql="SELECT * from modele where type=$type";
		$db = config::getConnexion();
		try{
		$liste=$db->query($sql);
		return $liste;
		}
        catch (Exception $e){
            die('Erreur: '.$e->getMessage());
        }
	}

}
?>
