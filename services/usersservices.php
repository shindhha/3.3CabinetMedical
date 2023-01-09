<?php


namespace services;

use PDOException;

/**
 *
 */
class UsersServices
{
  public function findIfAdminExists($pdo,$username,$password)
  {
    $sql = "SELECT *
         FROM users
         WHERE login = :username AND `password` = MD5(:password)";

    $request = $pdo->prepare($sql);
    $request->execute(['username' => $username , 'password' => $password]);
    $nbRow = $request->rowcount();
    return $nbRow >= 1;
  }

  public function updatePatient($pdo,$numSecu,$LieuNaissance,$nom,$prenom,$dateNaissance,$adresse,$codePostal,$medecinRef,$numTel,$email,$sexe,$notes)
  {
    $sql = "UPDATE Patients 
            SET LieuNaissance = :LieuNaissance,
            nom = :nom,
            prenom = :prenom,
            dateNaissance = :dateNaissance,
            adresse = :adresse,
            codePostal = :codePostal,
            medecinRef = :medecinRef,
            numTel = :numTel,
            email = :email,
            sexe = :sexe,
            notes = :notes
            WHERE numSecu = :numSecu";

    $stmt = $pdo->prepare($sql);

    $stmt->execute(array('numSecu' => $numSecu,
                         'LieuNaissance' => $LieuNaissance,
                         'nom' => $nom,
                         'prenom' => $prenom,
                         'dateNaissance' => $dateNaissance,
                         'adresse' => $adresse,
                         'codePostal' => $codePostal,
                         'medecinRef' => $medecinRef,
                         'numTel' => $numTel,
                         'email' => $email,
                         'sexe' => $sexe,
                         'notes' => $notes));

  }

  public function getMedecins($pdo)
  {
    $sql = "SELECT * 
            FROM Medecins";

    return $pdo->query($sql);
  }

  public function deletePatient($pdo,$numSecu)
  {
    $sql1 = "DELETE FROM PatientsMedecins WHERE numSecu = :numSecu";
    $sql2 = "DELETE FROM Patients WHERE numSecu = :numSecu";

    $stmt = $pdo->prepare($sql1);
    $stmt->bindParam("numSecu",$numSecu);
    $stmt->execute();

    $stmt = $pdo->prepare($sql2);
    $stmt->bindParam("numSecu",$numSecu);

    $stmt->execute();

  }

  public function insertPatient($pdo,$idMedecin,$numSecu,$LieuNaissance,$nom,$prenom,$dateNaissance,$adresse,$codePostal,$medecinRef,$numTel,$email,$sexe,$notes)
  {
    $sql1 = "INSERT INTO Patients (numSecu,LieuNaissance,nom,prenom,dateNaissance,adresse,codePostal,medecinRef,numTel,email,sexe,notes) VALUES (:numSecu,:LieuNaissance,:nom,:prenom,:dateNaissance,:adresse,:codePostal,:medecinRef,:numTel,:email,:sexe,:notes)";
    

    $sql2 = "INSERT INTO PatientsMedecins (numSecu,numRPPS) VALUES (:numSecu,:numRPPS)";

    $stmt = $pdo->prepare($sql1);
    $stmt->execute(array('numSecu' => $numSecu,
                         'LieuNaissance' => $LieuNaissance,
                         'nom' => $nom,
                         'prenom' => $prenom,
                         'dateNaissance' => $dateNaissance,
                         'adresse' => $adresse,
                         'codePostal' => $codePostal,
                         'medecinRef' => $medecinRef,
                         'numTel' => $numTel,
                         'email' => $email,
                         'sexe' => $sexe,
                         'notes' => $notes));

    $stmt = $pdo->prepare($sql2);
    $stmt->execute(array('numSecu' => $numSecu, 
                         'numRPPS' => $idMedecin));

  }

  public function getOrdonnances($pdo,$idVisite)
  {
    $sql = "SELECT codeCIS
            FROM Ordonnances
            WHERE idOrdonnance = :idOrdonnance";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam("idOrdonnance",$idVisite);
    $stmt->execute();

    return $stmt->fetchAll();
  }

  public function getVisites($pdo,$numSecu)
  {
    $sql = "SELECT motifVisite,dateVisite,note,Visites.idVisite
            FROM Visites
            JOIN ListeVisites
            ON ListeVisites.idVisite = Visites.idVisite
            WHERE numSecu = :numSecu";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam("numSecu",$numSecu);
    $stmt->execute();

    return $stmt->fetchAll();
  }

  public function getListPatients($pdo,$medecinRef,$numSecu = "%")
  {
    $sql = "SELECT Patients.numSecu,LieuNaissance,nom,prenom,dateNaissance,adresse,codePostal,medecinRef,numTel,email,sexe,notes
            FROM PatientsMedecins
            JOIN Patients
            ON PatientsMedecins.numSecu = Patients.numSecu
            WHERE numRPPS = :numRPPS
            AND Patients.numSecu LIKE :numSecu";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam('numRPPS',$medecinRef);
    $stmt->bindParam('numSecu',$numSecu);

    $stmt->execute();

    return $stmt->fetchAll();

  }
  public function getListMedic($pdo,$formePharma = "%",$labelVoieAdministration = "%",$etatCommercialisation = -1,$tauxRemboursement = "",$prixMin = 0,$prixMax = 100000,$surveillanceRenforcee = -1, $designation = "%")
  {
    $sql = "SELECT codeCIS,formePharma,labelVoieAdministration,etatCommercialisation,tauxRemboursement,prix,designation,surveillanceRenforcee
            FROM listMedic
            WHERE formePharma LIKE :formePharma
            AND labelVoieAdministration LIKE :labelVoieAdministration
            AND designation LIKE :designation
            AND prix > :prixMin AND prix < :prixMax
            
            ";
    $param = array('formePharma' => $formePharma,
                   'labelVoieAdministration' => $labelVoieAdministration,
                   'prixMin' => $prixMin,
                   'prixMax' => $prixMax,
                   'designation' => $designation,);

    if ($etatCommercialisation != -1) {
      $sql = $sql . " AND etatCommercialisation = :etatCommercialisation";
      $param['etatCommercialisation'] = $etatCommercialisation;
    }

    if ($tauxRemboursement != "") {
      $sql = $sql . " AND tauxRemboursement = :tauxRemboursement";
      $param['tauxRemboursement'] = $tauxRemboursement;
    }

    if ($surveillanceRenforcee != -1) {
      $sql = $sql . " AND surveillanceRenforcee = :surveillanceRenforcee";
      $param['surveillanceRenforcee'] = $surveillanceRenforcee;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($param);

    return $stmt->fetchAll();
  }

  public function getparams($pdo,$param,$table)
  {
    $sql = "SELECT DISTINCT(" . $param .")"
           . " FROM " . $table;

    return $pdo->query($sql);
  }

  private static $defaultUsersService ;

  public static function getDefaultUsersService()
  {
      if (UsersServices::$defaultUsersService == null) {
          UsersServices::$defaultUsersService = new UsersServices();
      }
      return UsersServices::$defaultUsersService;
  }
}
