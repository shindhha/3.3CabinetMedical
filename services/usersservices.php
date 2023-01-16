<?php


namespace services;

use PDOException;
use Exception;

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

  public function updatePatient($pdo,$patientID,$numSecu,$LieuNaissance,$nom,$prenom,$dateNaissance,$adresse,$codePostal,$medecinRef,$numTel,$email,$sexe,$notes)
  {
    if (!preg_match("#[1-9]{13}#",$numSecu)) {
      throw new PDOException("Le numéro de sécurité sociale n'est pas valide ! ", 1);
    }
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
            notes = :notes,
            numSecu = :numSecu
            WHERE idPatient = :patientID";

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
                         'notes' => $notes,
                         'patientID' => $patientID));

  }

  public function editInstruction($pdo,$idVisite,$codeCIS,$instruction)
  {
    $sql = "UPDATE Ordonnances SET instruction = :instruction WHERE idVisite = :idVisite AND codeCIS = :codeCIS";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array('instruction' => $instruction, 'idVisite' => $idVisite, 'codeCIS' => $codeCIS));
  }

  public function getMedecins($pdo)
  {
    $sql = "SELECT * 
            FROM Medecins";

    return $pdo->query($sql);
  }

  public function deleteMedicament($pdo,$idVisite,$codeCIS)
  {
    $sql = "DELETE FROM Ordonnances WHERE idVisite = :idVisite AND codeCIS = :codeCIS";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array('idVisite' => $idVisite, 'codeCIS' => $codeCIS));
  }
  public function deletePatientFrom($pdo,$table,$idPatient)
  {
    $sql = "DELETE FROM " . $table . " WHERE idPatient = :idPatient";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam("idPatient",$idPatient);
    $stmt->execute();
  }

  public function insertPatient($pdo,$numSecu,$LieuNaissance,$nom,$prenom,$dateNaissance,$adresse,$codePostal,$medecinRef,$numTel,$email,$sexe,$notes)
  {
    if (!preg_match("#[1-9]{13}#",$numSecu)) {
      throw new PDOException("Le numéro de sécurité sociale n'est pas valide ! ", 1);
    }
    $sql = "INSERT INTO Patients (numSecu,LieuNaissance,nom,prenom,dateNaissance,adresse,codePostal,medecinRef,numTel,email,sexe,notes) VALUES (:numSecu,:LieuNaissance,:nom,:prenom,:dateNaissance,:adresse,:codePostal,:medecinRef,:numTel,:email,:sexe,:notes)";
    


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
    return $pdo->lastInsertId();

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

  public function deleteVisiteFrom($pdo,$table,$idVisite)
  {
    $sql = "DELETE FROM " . $table . " WHERE idVisite = :idVisite";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam('idVisite',$idVisite);
    $stmt->execute();
  }

  public function insertVisite($pdo,$idPatient,$motifVisite,$dateVisite,$Description,$Conclusion)
  {
    $sql1 = "INSERT INTO Visites (motifVisite,dateVisite,Description,Conclusion)
            VALUES (:motifVisite,:dateVisite,:Description,:Conclusion)";
    

    $sql2 = "INSERT INTO ListeVisites (idPatient,idVisite) VALUES (:idPatient,LAST_INSERT_ID())";

    $stmt = $pdo->prepare($sql1);
    $stmt->execute(array('motifVisite' => $motifVisite,
                   'dateVisite' => $dateVisite,
                   'Description' => $Description,
                   'Conclusion' => $Conclusion));
    $lastInsertId = $pdo->lastInsertId();

    $stmt = $pdo->prepare($sql2);

    $stmt->execute(array('idPatient' => $idPatient));
    return $lastInsertId;
  }

  public function getOrdonnances($pdo,$idVisite)
  {
    $sql = "SELECT DISTINCT(Ordonnances.codeCIS),instruction,designation,libellePresentation
            FROM Ordonnances
            JOIN CIS_BDPM
            ON Ordonnances.codeCIS = CIS_BDPM.codeCIS
            JOIN DesignationElemPharma
            ON DesignationElemPharma.idDesignation = CIS_BDPM.idDesignation
            JOIN CIS_CIP_BDPM
            ON Ordonnances.codeCIS = CIS_CIP_BDPM.codeCIS
            JOIN LibellePresentation
            ON LibellePresentation.idLibellePresentation = CIS_CIP_BDPM.idLibellePresentation
            WHERE idVisite = :idVisite";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam("idVisite",$idVisite);
    $stmt->execute();

    return $stmt->fetchAll();
  }

  public function getVisites($pdo,$idPatient)
  {
    $sql = "SELECT motifVisite,dateVisite,Description,Conclusion,Visites.idVisite
            FROM Visites
            JOIN ListeVisites
            ON ListeVisites.idVisite = Visites.idVisite
            WHERE idPatient = :idPatient";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam("idPatient",$idPatient);
    $stmt->execute();

    return $stmt->fetchAll();
  }
  public function getVisite($pdo,$idPatient,$idVisite)
  {
    $sql = "SELECT motifVisite,dateVisite,Description,Conclusion,Visites.idVisite
            FROM Visites
            JOIN ListeVisites
            ON ListeVisites.idVisite = Visites.idVisite
            WHERE idPatient = :idPatient
            AND Visites.idVisite = :idVisite";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam("idPatient",$idPatient);
    $stmt->bindParam("idVisite",$idVisite);
    $stmt->execute();

    return $stmt->fetch();
  }

  public function getPatient($pdo,$idPatient)
  {
    $sql = "SELECT idPatient, Patients.numSecu,LieuNaissance,nom,prenom,dateNaissance,adresse,codePostal,medecinRef,numTel,email,sexe,notes
            FROM Patients
            WHERE Patients.idPatient LIKE :idPatient";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam('idPatient',$idPatient);


    $stmt->execute();

    return $stmt->fetch();

  }

  public function getMedicament($pdo,$codeCIS)
  {
    $sql = "
    SELECT 
       CIS_BDPM.codeCIS as codeCIS,
       designation,
       formePharma,
       StatutAdAMM.statutAdAMM as statutAdAMM,
       typeProc,
       autoEur,
       tauxRemboursement,
       codeCIP7,
       libellePresentation,
       statutAdminiPresentation,
       labelEtatCommercialisation,
       dateCommrcialisation,
       codeCIP13,
       agrementCollectivites,
       prix,
       IndicationRemboursement,
       labelGroupeGener,
       typeGenerique,
       numeroTri,
       labelElem,
       codesubstance,
       labelDosage,
       labelRefDosage,
       labelVoieAdministration,
       labelcondition,
       dateDebutInformation,
       dateFinInformation,
       labelTexte,
       labelTitulaire,
       dateAMM
    FROM CIS_BDPM
         LEFT JOIN DesignationElemPharma
                   ON CIS_BDPM.idDesignation = DesignationElemPharma.idDesignation
         LEFT JOIN FormePharma
                   ON CIS_BDPM.idFormePharma = FormePharma.idFormePharma
         LEFT JOIN StatutAdAMM
                   ON CIS_BDPM.idStatutAdAMM = StatutAdAMM.idStatutAdAMM
         LEFT JOIN TypeProc
                   ON CIS_BDPM.idTypeProc = TypeProc.idTypeProc
         LEFT JOIN AutorEurop
                   ON CIS_BDPM.idAutoEur = AutorEurop.idAutoEur
         LEFT JOIN TauxRemboursement
                   ON CIS_BDPM.codeCIS = TauxRemboursement.codeCIS
         LEFT JOIN CIS_CIP_BDPM
                   ON CIS_CIP_BDPM.codeCIS = CIS_BDPM.codeCIS
         LEFT JOIN LibellePresentation
                   ON CIS_CIP_BDPM.idLibellePresentation = LibellePresentation.idLibellePresentation
         LEFT JOIN EtatCommercialisation
                   ON CIS_CIP_BDPM.idEtatCommercialisation = EtatCommercialisation.idEtatCommercialisation
         LEFT JOIN CIS_GENER
                   ON CIS_GENER.codeCIS = CIS_BDPM.codeCIS
         LEFT JOIN GroupeGener
                   ON CIS_GENER.idGroupeGener = GroupeGener.idGroupeGener
         LEFT JOIN CIS_COMPO
                   ON CIS_COMPO.codeCIS = CIS_BDPM.codeCIS
         LEFT JOIN DesignationElem
                   ON CIS_COMPO.idDesignationElemPharma = DesignationElem.idElem
         LEFT JOIN CodeSubstance
                   ON CIS_COMPO.idCodeSubstance = CodeSubstance.idSubstance
                       AND CIS_COMPO.varianceNomSubstance = CodeSubstance.varianceNom
         LEFT JOIN Dosage
                   ON CIS_COMPO.idDosage = Dosage.idDosage
         LEFT JOIN RefDosage
                   ON CIS_COMPO.idRefDosage = RefDosage.idRefDosage
         LEFT JOIN CIS_VoieAdministration
                   ON CIS_BDPM.codeCIS = CIS_VoieAdministration.codeCIS
         LEFT JOIN ID_Label_VoieAdministration
                   ON CIS_VoieAdministration.idVoieAdministration = ID_Label_VoieAdministration.idVoieAdministration
         LEFT JOIN CIS_CPD
                   ON CIS_CPD.codeCIS = CIS_BDPM.codeCIS
         LEFT JOIN LabelCondition
                   ON CIS_CPD.idCondition = LabelCondition.idCondition
         LEFT JOIN CIS_INFO
                   ON CIS_BDPM.codeCIS = CIS_INFO.codeCIS
         LEFT JOIN Info_Texte
                   ON CIS_INFO.idTexte = Info_Texte.idTexte
         LEFT JOIN CIS_Titulaires
                   ON CIS_BDPM.codeCIS = CIS_Titulaires.codeCIS
         LEFT JOIN ID_Label_Titulaire
                   ON CIS_Titulaires.idTitulaire = ID_Label_Titulaire.idTitulaire
      WHERE CIS_BDPM.codeCIS = :codeCIS
      ";
      $stmt = $pdo->prepare($sql);

      $stmt->bindParam('codeCIS',$codeCIS);
      $stmt->execute();

      return $stmt->fetch();
  }

  public function getAllSMR($pdo, $codeCIS)
  {
    $sql = "
        SELECT dateAvis, libelleNiveauSMR, libelleSmr, lienPage, libelleMotifEval 
        FROM CIS_HAS_SMR
        JOIN LibelleSmr LS on CIS_HAS_SMR.idLibelleSmr = LS.idLibelleSMR
        LEFT JOIN HAS_LiensPageCT HLPCT on CIS_HAS_SMR.codeHAS = HLPCT.codeHAS
        JOIN NiveauSMR NS on CIS_HAS_SMR.niveauSMR = NS.idNiveauSMR
        JOIN MotifEval ME on CIS_HAS_SMR.idMotifEval = ME.idMotifEval
        WHERE codeCIS = :codeCIS
        ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam('codeCIS',$codeCIS);
    $stmt->execute();

    return $stmt->fetchAll();
  }

  public function getAllASMR($pdo, $codeCIS) {
    $sql = "
        SELECT dateAvis, valeurASMR, lienPage, libelleAsmr, libelleMotifEval FROM CIS_HAS_ASMR
        LEFT JOIN HAS_LiensPageCT HLPCT on CIS_HAS_ASMR.codeHAS = HLPCT.codeHAS
        JOIN LibelleAsmr LA on CIS_HAS_ASMR.idLibelleAsmr = LA.idLibelleAsmr
        JOIN MotifEval ME on CIS_HAS_ASMR.idMotifEval = ME.idMotifEval
        WHERE codeCIS = :codeCIS
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam('codeCIS',$codeCIS);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  public function getListPatients($pdo,$medecinTraitant,$nom,$prenom)
  {
    $sql = "SELECT idPatient, Patients.numSecu,LieuNaissance,nom,prenom,dateNaissance,adresse,codePostal,medecinRef,numTel,email,sexe,notes
            FROM Patients
            WHERE ((nom LIKE :search1 OR prenom LIKE :search2)
                   OR (nom LIKE :search3 OR prenom LIKE :search4))
            AND medecinRef LIKE :medecinTraitant";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam('search1',$nom);
    $stmt->bindParam('search2',$prenom); 
    $stmt->bindParam('search3',$prenom);
    $stmt->bindParam('search4',$nom); 
    $stmt->bindParam('medecinTraitant',$medecinTraitant);


    $stmt->execute();

    return $stmt->fetchAll();

  }
  public function getListMedic($pdo,$formePharma = "%",$labelVoieAdministration = "%",$etatCommercialisation = -1,$tauxRemboursement = "",$prixMin = 0,$prixMax = 100000,$surveillanceRenforcee = -1,$valeurASMR = "%",$libelleNiveauSMR = "%", $libellePresentation = "%")
  {
    $sql = "SELECT codeCIS,formePharma,labelVoieAdministration,etatCommercialisation,tauxRemboursement,prix,libellePresentation,surveillanceRenforcee,valeurASMR,libelleNiveauSMR,designation
            FROM listMedic
            WHERE (formePharma LIKE :formePharma OR formePharma IS NULL)
            AND (labelVoieAdministration LIKE :labelVoieAdministration OR labelVoieAdministration IS NULL)
            AND (libellePresentation LIKE :libellePresentation OR libellePresentation IS NULL)
            AND (prix >= :prixMin AND prix < :prixMax OR prix IS NULL)
            AND (valeurASMR LIKE :valeurASMR OR valeurASMR IS NULL)
            AND (libelleNiveauSMR LIKE :libelleNiveauSMR OR libelleNiveauSMR IS NULL)
            
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
    $sql .= " LIMIT 50";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($param);

    return $stmt->fetchAll();
  }
  public function addMedic($pdo,$idVisite,$codeCIS,$instruction)
  {
    $sql = "INSERT INTO Ordonnances (idVisite,codeCIS,instruction) VALUES (:idVisite,:codeCIS,:instruction)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array("idVisite" => $idVisite, "codeCIS" => $codeCIS, "instruction" => $instruction));
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
