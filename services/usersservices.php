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

  public function modifVisite($pdo,$idVisite,$motifVisite,$dateVisite,$Description,$Conclusion)
  {
    $sql = "UPDATE Visites 
    SET motifVisite = :motifVisite,
    dateVisite = :dateVisite,
    Description = :Description,
    Conclusion = :Conclusion
    WHERE idVisite = :idVisite";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array('motifVisite' => $motifVisite,
                   'dateVisite' => $dateVisite,
                   'Description' => $Description,
                   'Conclusion' => $Conclusion,
                   'idVisite' => $idVisite));
  }


  public function deleteVisite($pdo,$idVisite)
  {
    $sql1 = "DELETE FROM Visites WHERE idVisite = :idVisite";
    $sql2 = "DELETE FROM ListeVisites WHERE idVisite = :idVisite";

    $stmt = $pdo->prepare($sql2);
    $stmt->execute(array('idVisite' => $idVisite));
    $stmt = $pdo->prepare($sql1);
    $stmt->execute(array('idVisite' => $idVisite));
  }

  public function insertVisite($pdo,$numSecu,$motifVisite,$dateVisite,$Description,$Conclusion)
  {
    $sql1 = "INSERT INTO Visites (motifVisite,dateVisite,Description,Conclusion)
            VALUES (:motifVisite,:dateVisite,:Description,:Conclusion)";
    

    $sql2 = "INSERT INTO ListeVisites (numSecu,idVisite) VALUES (:numSecu,LAST_INSERT_ID())";

    $stmt = $pdo->prepare($sql1);
    $stmt->execute(array('motifVisite' => $motifVisite,
                   'dateVisite' => $dateVisite,
                   'Description' => $Description,
                   'Conclusion' => $Conclusion));
    $lastInsertId = $pdo->lastInsertId();

    $stmt = $pdo->prepare($sql2);

    $stmt->execute(array('numSecu' => $numSecu));
    return $lastInsertId;
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

  public function getVisites($pdo,$numSecu,$idVisite = "%")
  {
    $sql = "SELECT motifVisite,dateVisite,Description,Conclusion,Visites.idVisite
            FROM Visites
            JOIN ListeVisites
            ON ListeVisites.idVisite = Visites.idVisite
            WHERE numSecu = :numSecu
            AND Visites.idVisite LIKE :idVisite";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam("numSecu",$numSecu);
    $stmt->bindParam("idVisite",$idVisite);
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
  public function getListMedic($pdo,$formePharma = "%",$labelVoieAdministration = "%",$etatCommercialisation = -1,$tauxRemboursement = "",$prixMin = 0,$prixMax = 100000,$surveillanceRenforcee = -1,$valeurASMR = "%",$libelleNiveauSMR = "%", $libellePresentation = "%")
  {
    $sql = "SELECT codeCIS,formePharma,labelVoieAdministration,etatCommercialisation,tauxRemboursement,prix,libellePresentation,surveillanceRenforcee,valeurASMR,libelleNiveauSMR
            FROM listMedic
            WHERE formePharma LIKE :formePharma
            AND labelVoieAdministration LIKE :labelVoieAdministration
            AND libellePresentation LIKE :libellePresentation
            AND prix > :prixMin AND prix < :prixMax
            AND valeurASMR LIKE :valeurASMR
            AND libelleNiveauSMR LIKE :libelleNiveauSMR
            LIMIT 50
            ";
    $param = array('formePharma' => $formePharma,
                   'labelVoieAdministration' => $labelVoieAdministration,
                   'prixMin' => $prixMin,
                   'prixMax' => $prixMax,
                   'libellePresentation' => $libellePresentation,
                   'valeurASMR' => $valeurASMR,
                   'libelleNiveauSMR' => $libelleNiveauSMR);

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
  public function addMedic($pdo,$idVisite,$codeCIS)
  {
    $sql = "INSERT INTO Ordonnances (idOrdonnance,codeCIS) VALUES (:idVisite,:codeCIS)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array("idVisite" => $idVisite, "codeCIS" => $codeCIS));
  }

  public function getparams($pdo,$param,$table)
  {
    $sql = "SELECT DISTINCT(" . $param .")"
           . " FROM " . $table
           . " ORDER BY " . $param;

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
