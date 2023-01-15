<?php


namespace services;

use PDOException;
use Exception;

/**
 *
 */
class UsersServices
{

  public function getPatientID($pdo,$numSecu)
  {
    $sql = "SELECT id FROM Patients where numSecu = :numSecu";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam("numSecu",$numSecu);
        $stmt->execute();
        return $stmt->fetch();
  }
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
            WHERE id = :patientID";

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
    $sql = "SELECT Ordonnances.codeCIS,instruction,designation,libellePresentation
            FROM Ordonnances
            JOIN CIS_BDPM
            ON Ordonnances.codeCIS = CIS_BDPM.codeCIS
            JOIN designationelempharma
            ON designationelempharma.idDesignation = CIS_BDPM.idDesignation
            JOIN CIS_CIP_BDPM
            ON Ordonnances.codeCIS = CIS_CIP_BDPM.codeCIS
            JOIN libellePresentation
            ON libellePresentation.idLibellePresentation = CIS_CIP_BDPM.idLibellePresentation
            WHERE idVisite = :idVisite";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam("idVisite",$idVisite);
    $stmt->execute();

    return $stmt->fetchAll();
  }

  public function getVisites($pdo,$numSecu)
  {
    $sql = "SELECT motifVisite,dateVisite,Description,Conclusion,Visites.idVisite
            FROM Visites
            JOIN ListeVisites
            ON ListeVisites.idVisite = Visites.idVisite
            WHERE numSecu = :numSecu";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam("numSecu",$numSecu);
    $stmt->execute();

    return $stmt->fetchAll();
  }
  public function getVisite($pdo,$numSecu,$idVisite)
  {
    $sql = "SELECT motifVisite,dateVisite,Description,Conclusion,Visites.idVisite
            FROM Visites
            JOIN ListeVisites
            ON ListeVisites.idVisite = Visites.idVisite
            WHERE numSecu = :numSecu
            AND Visites.idVisite = :idVisite";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam("numSecu",$numSecu);
    $stmt->bindParam("idVisite",$idVisite);
    $stmt->execute();

    return $stmt->fetch();
  }

  public function getPatient($pdo,$numSecu)
  {
    $sql = "SELECT Patients.numSecu,LieuNaissance,nom,prenom,dateNaissance,adresse,codePostal,medecinRef,numTel,email,sexe,notes
            FROM Patients
            WHERE Patients.numSecu LIKE :numSecu";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam('numSecu',$numSecu); 


    $stmt->execute();

    return $stmt->fetch();

  }

  public function getMedicament($pdo,$codeCIS)
  {
    $sql = "SELECT designation,formePharma,statutAdAMM,typeProc,autoEur,tauxRemboursement,codeCIP7,libellePresentation,statutAdminiPresentation,labelEtatCommercialisation,dateCommrcialisation,codeCIP13,agrementCollectivites,prix,IndicationRemboursement,labelGroupeGener,typeGenerique,numeroTri,labelElem,codesubstance,labelDosage,labelRefDosage,labelVoieAdministration,labelcondition,dateDebutInformation,dateFinInformation,labelTexte,labelTitulaire,libelleMotifEval,cis_has_smr.dateAvis as dateAvisSMR,niveauSMR,libelleSMR,lienPage,cis_has_asmr.dateAvis as dateAvisASMR,valeurASMR,libelleASMR
      FROM cis_bdpm
      LEFT JOIN DesignationElemPharma
      ON cis_bdpm.idDesignation = DesignationElemPharma.idDesignation
      LEFT JOIN FormePharma
      ON cis_bdpm.idFormePharma = FormePharma.idFormePharma
      LEFT JOIN StatutAdAMM 
      ON cis_bdpm.idStatutAdAMM = StatutAdAMM.idStatutAdAMM
      LEFT JOIN TypeProc 
      ON cis_bdpm.idTypeProc = TypeProc.idTypeProc
      LEFT JOIN AutorEurop
      ON cis_bdpm.idAutoEur = AutorEurop.idAutoEur
      LEFT JOIN TauxRemboursement 
      ON cis_bdpm.codeCIS = TauxRemboursement.codeCIS
      LEFT JOIN cis_cip_bdpm
      ON cis_cip_bdpm.codeCIS = cis_bdpm.codeCIS
      LEFT JOIN LibellePresentation 
      ON cis_cip_bdpm.idLibellePresentation = LibellePresentation.idLibellePresentation
      LEFT JOIN EtatCommercialisation
      ON cis_cip_bdpm.idEtatCommercialisation = EtatCommercialisation.idEtatCommercialisation
      LEFT JOIN cis_gener
      ON cis_gener.codeCIS = cis_bdpm.codeCIS
      LEFT JOIN GroupeGener 
      ON cis_gener.idGroupeGener = GroupeGener.idGroupeGener
      LEFT JOIN cis_compo
      ON cis_compo.codeCIS = cis_bdpm.codeCIS
      LEFT JOIN DesignationElem
      ON cis_compo.idDesignationElemPharma = DesignationElem.idElem
      LEFT JOIN CodeSubstance 
      ON cis_compo.idCodeSubstance = CodeSubstance.idSubstance
      AND cis_compo.varianceNomSubstance = CodeSubstance.varianceNom
      LEFT JOIN Dosage 
      ON cis_compo.idDosage = Dosage.idDosage
      LEFT JOIN RefDosage 
      ON cis_compo.idRefDosage = RefDosage.idRefDosage
      LEFT JOIN cis_voieadministration
      ON cis_bdpm.codeCIS = cis_voieadministration.codeCIS
      LEFT JOIN id_label_voieadministration
      ON cis_voieadministration.idVoieAdministration = id_label_voieadministration.idVoieAdministration 
      LEFT JOIN cis_cpd
      ON cis_cpd.codeCIS = cis_bdpm.codeCIS
      LEFT JOIN LabelCondition 
      ON cis_cpd.idCondition = LabelCondition.idCondition
      LEFT JOIN cis_info
      ON cis_bdpm.codeCIS = cis_info.codeCIS
      LEFT JOIN info_texte
      ON cis_info.idTexte = info_texte.idTexte
      LEFT JOIN cis_titulaires
      ON cis_bdpm.codeCIS = cis_titulaires.codeCIS
      LEFT JOIN id_label_titulaire
      ON cis_titulaires.idTitulaire = id_label_titulaire.idTitulaire
      LEFT JOIN cis_has_smr
      ON cis_bdpm.codeCIS = cis_has_smr.codeCIS
      LEFT JOIN motifeval
      ON cis_has_smr.idMotifEval = motifeval.idMotifEval
      LEFT JOIN LibelleSMR 
      ON cis_has_smr.idLibelleSmr = LibelleSMR.idLibelleSMR
      LEFT JOIN has_lienspagect
      ON cis_has_smr.codeHAS = has_lienspagect.codeHAS
      LEFT JOIN cis_has_asmr
      ON cis_bdpm.codeCIS = cis_has_asmr.codeCIS
      LEFT JOIN LibelleASMR 
      ON cis_has_asmr.idLibelleAsmr = LibelleASMR.idLibelleAsmr
      WHERE cis_bdpm.codeCIS = :codeCIS
      ";
      $stmt = $pdo->prepare($sql);

      $stmt->bindParam('codeCIS',$codeCIS);
      $stmt->execute();

      return $stmt->fetch();
  }

  public function getListPatients($pdo,$medecinTraitant,$nom,$prenom)
  {
    $sql = "SELECT Patients.numSecu,LieuNaissance,nom,prenom,dateNaissance,adresse,codePostal,medecinRef,numTel,email,sexe,notes
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
    $sql = "SELECT codeCIS,formePharma,labelVoieAdministration,etatCommercialisation,tauxRemboursement,prix,libellePresentation,surveillanceRenforcee,valeurASMR,libelleNiveauSMR
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
