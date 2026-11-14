
<?PHP
include_once("C:/wamp64/www/gentelella-master/production/CONFIG.php");
class PRODUIT{
	private $code;
	private $nom;
	private $qte;
	private $date;
	private $prix;
	private $alerte;
	function __construct($code,$qte,$nom,$date,$prix,$alerte){
		$this->code=$code;
		$this->nom=$nom;
		$this->qte=$qte;
		$this->date=$date;
		$this->prix=$prix;
		$this->alerte=$alerte;
	}

	function getCode(){
		return $this->code;
	}
	function getQte(){
		return $this->qte;
	}
	function getDate(){
		return $this->date;
	}
	function getAlerte(){
		return $this->alerte;
	}
	function getNom(){
		return $this->nom;
	}
	function getPrix(){
		return $this->prix;
	}
	function setNom($nom){
		$this->nom=$nom;
	}
	function setCode($code){
		$this->code=$code;
	}
	function setQte($qte){
		$this->qte=$qte;
	}
	function setDate($date){
		$this->date=$date;
	}
	function setAlerte($alerte){
		$this->alerte=$alerte;
	}
	function setPrix($prix){
		$this->prix=$prix;
	}

}

?>

<?PHP
class PRODUITC {
	function __construct(){
	}

function AFF_PRDT ($produit){
		echo "Code: ".$produit->getCode()."<br>";
		echo "Qte: ".$produit->getQte()."<br>";
		echo "Date d'arrivé: ".$produit->getDate()."<br>";
		echo "Alerte: ".$produit->getAlerte()."<br>";
		echo "Nom: ".$produit->getNom()."<br>";
		echo "Prix: ".$produit->getPrix()."<br>";
	}

	function AJT_PRDT($produit){

		try{
			$db = config::getConnexion();
        $req=$db->prepare("INSERT INTO produit (CODE,QUANTITE,DATE_ARV,ALERTE,NOM,PRIX) VALUES (:code,:qte,:daate,:alrt,:nom,:prix)");

        $code=$produit->getCode();
        $qte=$produit->getQte();
        $date=$produit->getDate();
        $alerte=$produit->getAlerte();
				$nom=$produit->getNom();
        $prix=$produit->getPrix();
		$req->bindValue(':code',$code);
		$req->bindValue(':qte',$qte);
		$req->bindValue(':daate',$date);
		$req->bindValue(':alrt',$alerte);
		$req->bindValue(':nom',$nom);
		$req->bindValue(':prix',$prix);

            $req->execute();

        }
        catch (Exception $e){
            echo 'Erreur: '.$e->getMessage();
        }

	}

	function AFF_PRDTS(){
		//$sql="SElECT * From employe e inner join formationphp.employe a on e.cin= a.cin";
		$sql="SELECT * FROM produit";
		$db = config::getConnexion();
		try{
		$liste=$db->query($sql);
		return $liste;
		}
        catch (Exception $e){
            die('Erreur: '.$e->getMessage());
        }
	}
	function SUPP_PRDT($code){

		try{
			$db = config::getConnexion();
	        $req=$db->prepare("DELETE FROM produit WHERE CODE=:code");
			$req->bindValue(':code',$code);
            $req->execute();
           // header('Location: index.php');
        }
        catch (Exception $e){
            die('Erreur: '.$e->getMessage());
        }
	}
	function MODF_PRDT($produit,$code){
		$sql="UPDATE produit SET CODE=:codee,QUANTITE=:qte,DATE_ARV=:date,ALERTE=:alrt,NOM=:nom,PRIX=:prix WHERE CODE=:code";

		$db = config::getConnexion();
		//$db->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);
try{
        $req=$db->prepare($sql);
		$codee=$produit->getCode();
        $nom=$produit->getNom();
        $qte=$produit->getQte();
        $date=$produit->getDate();
        $alerte=$produit->getAlerte();
				$prix=$produit->getPrix();
		$datas = array(':codee'=>$codee, ':code'=>$code, ':qte'=>$qte,':date'=>$date,':alrt'=>$alerte,':nom'=>$nom,':prix'=>$prix);
		$req->bindValue(':codee',$codee);
		$req->bindValue(':code',$code);
		$req->bindValue(':nom',$nom);
		$req->bindValue(':qte',$qte);
		$req->bindValue(':alrt',$alerte);
		$req->bindValue(':date',$date);
		$req->bindValue(':prix',$prix);


            $s=$req->execute();

           // header('Location: index.php');
        }
        catch (Exception $e){
            echo " Erreur ! ".$e->getMessage();
   echo " Les datas : " ;
  print_r($datas);
        }

	}
	function RCPR_PRDT($codee){
		$sql="SELECT * FROM produit WHERE CODE=:cd";
		$db = config::getConnexion();
		try{
			$sth=$db->prepare($sql);
			$sth->bindValue(':cd',$codee);
			$sth->execute();
			while ($ligne = $sth->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
				$data1=$ligne[0];
				$data2=$ligne[1];
				$data3=$ligne[2];
				$data4=$ligne[3];
				$data5=$ligne[4];
				$data6=$ligne[5];
			}
			echo $data1;
			$prodt= new PRODUIT($data1,$data2,$data3,$data4,$data5,$data6);
			return $prodt;
		}
        catch (Exception $e){
            die('Erreur: '.$e->getMessage());
        }
	}

	function RECHERCHE_PRDT($alerte){
		$sql="SELECT * from produit where alrt=$alerte";
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
