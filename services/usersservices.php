<?php


namespace services;

use PDOException;
use Exception;

/**
 *
 */
class UsersServices
{
  /**
   * Cherche à l'interieur de la base de données si le l'utilisateur
   * '@username' existe et si le '@password' associer est correct .
   * Cette fonction est utiliser pour se connecter a l'application
   * @param pdo la connexion a la base de données
   * @param username l'identifiant de l'utilisateur (numRPPS ou 'admin')
   * @param password mot de passe de l'utilisateur
   * @return true si au moin une ligne a été trouver sinon false
   */
  public function findIfUserExists($pdo,$username,$password)
  {
    $sql = "SELECT *
         FROM users
         WHERE login = :username AND `password` = MD5(:password)";

    $request = $pdo->prepare($sql);
    $request->execute(['username' => $username , 'password' => $password]);
    $nbRow = $request->rowcount();
    return $nbRow >= 1;
  }


  /**
   * Met à jour les données d'un patient dont l'identifiant dans la base de
   * données correspond a '@patientID'
   * @param pdo            La connexion a la base de données
   * @param patientID      Identifiant du patient dans la base de données
   * @param numSecu        Numéro de sécurité sociale du patient sans sa clé de vérification (13 chiffre)
   * @param LieuNaissance  Lieu de naissance du patient 
   * @param nom            Nom du patient
   * @param prenom         Prenom du patient
   * @param dateNaissance  Date de naissance du patient 
   * @param adresse        Adresse du patient 
   * @param codePostal     Code Postal du patient (entre 01001 et 98800)
   * @param medecinRef     Le numéro RPPS du Medecin Traitant du patient 
   * @param numTel         Numéro de téléphone du patient (entre 100000000 et 999999999)
   * @param email          Email du patient (de la forme %@%.%)
   * @param sexe           Sexe du patient (0 => Femme ou 1 => Homme)
   * @param notes          Notes relatives au patient
   * @throws PDOException Si le numéro de sécurité sociale est invalide (contient des lettres ou contient un nombre de charactère != 13)
   */
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
  /**
   * Modifie les instruction d'un medicament avec l'identifiant 'codeCIS' 
   * pour l'ordonnance avec le numéro d'identification 'idVisite'
   * 
   * @param pdo         La connexion a la base de données
   * @param idVisite    Identifiant de la visite dans la base de données
   *                    (L'identifiant d'une ordonnance est le meme que pour
   *                     celui de la visite associer) 
   * @param codeCIS     Identifiant du medicament 
   * @param instruction Nouvlles intructions du medecin pour se medicament dans cette visite
   */
  public function editInstruction($pdo,$idVisite,$codeCIS,$instruction)
  {
    $sql = "UPDATE Ordonnances SET instruction = :instruction WHERE idVisite = :idVisite AND codeCIS = :codeCIS";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array('instruction' => $instruction, 'idVisite' => $idVisite, 'codeCIS' => $codeCIS));
  }

  /**
   * @param pdo La connexion a la base de données
   * @return La liste des medecins de la base de données
   */
  public function getMedecins($pdo)
  {
    $sql = "SELECT * 
            FROM Medecins";

    return $pdo->query($sql);
  }
  /**
   * Supprime le medicament avec l'identifiant 'codeCIS' 
   * de l'ordonnance avec l'identifiant 'idVisite' 
   * @param pdo      La connexion a la base de données
   * @param idVisite L'identifiant de la viste dans la base de données
   * @param codeCIS  L'identifiant du medicament
   */
  public function deleteMedicament($pdo,$idVisite,$codeCIS)
  {
    $sql = "DELETE FROM Ordonnances WHERE idVisite = :idVisite AND codeCIS = :codeCIS";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(array('idVisite' => $idVisite, 'codeCIS' => $codeCIS));
  }
  /**
   * Supprime toute les occurences du patient avec l'identifiant 
   * 'idPatient' dans la table 'table'
   * @param pdo   La connexion a la base de données
   * @param table La table dans laquelle supprimer le patient
   * @param idPatient L'identifiant du patient a supprimer
   */
  public function deletePatientFrom($pdo,$table,$idPatient)
  {
    $sql = "DELETE FROM " . $table . " WHERE idPatient = :idPatient";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam("idPatient",$idPatient);
    $stmt->execute();
  }
  /**
   * Insere un nouveau patient dans la base de données
   * et retourne sont identifiant
   * @param pdo            La connexion a la base de données
   * @param numSecu        Numéro de sécurité sociale du patient sans sa clé de vérification (13 chiffre)
   * @param LieuNaissance  Lieu de naissance du patient 
   * @param nom            Nom du patient
   * @param prenom         Prenom du patient
   * @param dateNaissance  Date de naissance du patient 
   * @param adresse        Adresse du patient 
   * @param codePostal     Code Postal du patient (entre 01001 et 98800)
   * @param medecinRef     Le numéro RPPS du Medecin Traitant du patient (11 chiffre)
   * @param numTel         Numéro de téléphone du patient (entre 100000000 et 999999999)
   * @param email          Email du patient (de la forme %@%.%)
   * @param sexe           Sexe du patient (0 => Femme ou 1 => Homme)
   * @param notes          Notes relatives au patient
   * @throws PDOException  Si le numéro de sécurité sociale est invalide (contient des lettres ou contient un nombre de charactère != 13)
   * @return l'Identifiant Du patient dans la base de données venant d'être crée
   */
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

  /**
   * Modifie les informations de la visite avec le numéro d'identification 'idVisite'
   * 
   * @param pdo La connexion a la base de données
   * @param idVisite L'identifiant de la visite dans la base de données
   * @param motifVisite La raison de la consultation du patient
   * @param dateVisite  La date a laquelle la visite a été faite
   * @param Description Description du déroulement de la consultation
   * @param Conclusion  Le traitement que prescrit le médecin au patient 
   */
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


  /**
   * Supprime toute les occurences de la visite avec l'identifiant 
   * 'idVisite' dans la table 'table'
   * @param table     La table dans laquelle supprimer le patient
   * @param idVisite  L'identifiant de la visite 
   */
  public function deleteVisiteFrom($pdo,$table,$idVisite)
  {
    $sql = "DELETE FROM " . $table . " WHERE idVisite = :idVisite";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam('idVisite',$idVisite);
    $stmt->execute();
  }
  /**
   * Insere une nouvelle visite pour le patient n° 'idPatient'
   * dans la base de données et renvoie le numéro de la visite
   * @param pdo            La connexion a la base de données
   * @param idVisite       L'identifiant de la visite dans la base de données
   * @param motifVisite    La raison de la consultation du patient
   * @param dateVisite     La date a laquelle la visite a été faite
   * @param Description    Description du déroulement de la consultation
   * @param Conclusion     Le traitement que prescrit le médecin au patient 
   * @return L'identifiant de la visite venant d'être insérer
   */
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
  /**
   * @param pdo La connexion à la base de données
   * @param idVisite Identifiant de l'ordonnance dans la base de données
   *                 (L'identifiant d'une ordonnance est le meme que pour
   *                  celui de la visite associer)
   * @return La designation , la presentation , et les instruction du medecin associer
   *         precedement ajouter a l'ordonnance.
   */
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
  /**
   * Retourne la liste des visites du patient avec l'identifiant 'idPatient'
   * @param pdo La connexion à la base de données
   * @param idPatient Identifiant du patient dans la base de données
   * @return La liste des visites du patient dans le cabinet
   */
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

  /**
   * Retourne les informations de la visite avec l'identifiant
   * 'idVisite'
   * @param pdo La connexion à la base de données
   * @param idVisite Identifiant de la visite dans la base de données
   * @return Le motif de la visite , la date a laquelle elle a été réaliser
   *         La description du déroulement de la consultation
   *         Le traitement que prescrit le médecin au patient 
   */
  public function getVisite($pdo,$idVisite)
  {
    $sql = "SELECT motifVisite,dateVisite,Description,Conclusion,Visites.idVisite
            FROM Visites
            WHERE idVisite = :idVisite";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam("idVisite",$idVisite);
    $stmt->execute();

    return $stmt->fetch();
  }
  /**
   * Retourne les information du patient avec l'identifiant 'idPatient'
   * @param pdo La connexion à la base de données
   * @param idPatient Identifiant du patient dans la base de données
   * @return Numéro de sécurité sociale du patient sans sa clé de vérification (13 chiffre)
   *         Lieu de naissance du patient 
   *         Nom du patient
   *         Prenom du patient
   *         Date de naissance du patient 
   *         Adresse du patient 
   *         Code Postal du patient (entre 01001 et 98800)
   *         Le numéro RPPS du Medecin Traitant du patient (11 chiffre)
   *         Numéro de téléphone du patient (entre 100000000 et 999999999)
   *         Email du patient (de la forme %@%.%)
   *         Sexe du patient (0 => Femme ou 1 => Homme)
   *         Notes relatives au patient
   * 
   */
  public function getPatient($pdo,$idPatient)
  {
    $sql = "SELECT Patients.numSecu,LieuNaissance,nom,prenom,dateNaissance,adresse,codePostal,medecinRef,numTel,email,sexe,notes
            FROM Patients
            WHERE Patients.idPatient LIKE :idPatient";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam('idPatient',$idPatient);


    $stmt->execute();

    return $stmt->fetch();

  }

  public function getMedicament($pdo,$codeCIS)
  {
    $sql = "SELECT designation,formePharma,statutAdAMM,typeProc,autoEur,tauxRemboursement,codeCIP7,libellePresentation,statutAdminiPresentation,labelEtatCommercialisation,dateCommrcialisation,codeCIP13,agrementCollectivites,prix,IndicationRemboursement,labelGroupeGener,typeGenerique,numeroTri,labelElem,codesubstance,labelDosage,labelRefDosage,labelVoieAdministration,labelcondition,dateDebutInformation,dateFinInformation,labelTexte,labelTitulaire,libelleMotifEval,CIS_HAS_SMR.dateAvis as dateAvisSMR,niveauSMR,libelleSMR,lienPage,CIS_HAS_ASMR.dateAvis as dateAvisASMR,valeurASMR,libelleASMR
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
      LEFT JOIN CIS_HAS_SMR
      ON CIS_BDPM.codeCIS = CIS_HAS_SMR.codeCIS
      LEFT JOIN MotifEval
      ON CIS_HAS_SMR.idMotifEval = MotifEval.idMotifEval
      LEFT JOIN LibelleSmr 
      ON CIS_HAS_SMR.idLibelleSmr = LibelleSmr.idLibelleSMR
      LEFT JOIN HAS_LiensPageCT
      ON CIS_HAS_SMR.codeHAS = HAS_LiensPageCT.codeHAS
      LEFT JOIN CIS_HAS_ASMR
      ON CIS_BDPM.codeCIS = CIS_HAS_ASMR.codeCIS
      LEFT JOIN LibelleAsmr 
      ON CIS_HAS_ASMR.idLibelleAsmr = LibelleAsmr.idLibelleAsmr
      WHERE CIS_BDPM.codeCIS = :codeCIS
      ";
      $stmt = $pdo->prepare($sql);

      $stmt->bindParam('codeCIS',$codeCIS);
      $stmt->execute();

      return $stmt->fetch();
  }

  /**
   * Recherche dans la base de données les patients répondant au différents
   * critère si dessous.
   * On tire ces informations du formulaire n°
   * La recherche autorise en réalité l'inversion des nom/prenom
   * lors de la recherche.
   *                   Nom    Prenom
   * Ex :   Le patient Dupont Moretti est stocker dans la base de données
   * Si les information fournit : prenom = Dupont et nom = Moretti
   * Alors le patient sera tout de même retourner 
   * Cela permet d'eviter certaine potentielles erreur de saisi
   * @param pdo             La connexion a la base de données
   * @param nom             Le nom du patient rechercher
   * @param prenom          Le prenom du patient rechercher
   * @param medecinTraitant Le medecin traitant du patient rechercher
   *                        Le medecin doit faire partie du cabinet
   * 
   * @return L'identifiant des patients             
   *         Leurs numéro de sécurités sociales
   *         Leurs lieu de naissances
   *         Leurs nom , prenom                    qui répondent aux critères
   *         Leurs date de Naissance
   *         Leurs medecin traitant
   *         Leurs numéro de téléphone
   *         Leurs adresse 
   */        
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
