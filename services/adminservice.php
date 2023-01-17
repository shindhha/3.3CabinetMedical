<?php


namespace services;

use PDOException;

/**
 *
 */
class AdminService
{


    public function deleteUser($pdo,$userID)
    {
        $sql = "DELETE FROM users where id = :userID";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam("userID",$userID);
        $stmt->execute();
    }

    public function deleteMedecin($pdo,$idMedecin)
    {
        $sql = "DELETE FROM Medecins WHERE idMedecin = :idMedecin";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam("idMedecin",$idMedecin);
        $stmt->execute();
    }
    public function updateUser($pdo,$idUser,$login,$password)
    {
        $sql = "UPDATE users 
                SET login = :login , `password` = MD5(:password)
                WHERE id = :idUser";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam("idUser",$idUser);
        $stmt->bindParam("login",$login);
        $stmt->bindParam("password",$password);
        $stmt->execute();
    }
    /**
     * Inserer un nouvel utilisateur dans la base de données
     * avec comme identifiant de connexion 'login' 
     * (correspond au numéro RPPS du medecin)
     * et comme mot de passe 'password'
     * @param pdo      La connexion a la base de données
     * @param login    L'identifiant de connexion au site
     * @param password Le mot de connexion au site
     * @return L'identifiant fixe du medecin dans la base de données
     * 
     */
    public function addUser($pdo,$login,$password)
    {
        $sql = "INSERT INTO users (login,`password`) VALUES (:login,MD5(:password))";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam("login",$login);
        $stmt->bindParam("password",$password);
        $stmt->execute();
        return $pdo->lastInsertId();
    }

    public function getMedecinsList($pdo)
    {
        $sql = "SELECT idUser,idMedecin,numRPPS, nom, prenom, dateDebutActivites, numTel FROM Medecins";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
    /**
     * 
     * @param pdo        La connexion a la base de données
     * @param idMedecin  L'identifiant du medecin 
     * @return Dans l'odre des paramètre : 
     *         L'identifiant en tant qu'utilisateur du site 'idUser'
     *         L'identifiant en tant que medecin 'idMedecin' dans la base de données
     *         L'identifiant en tant que medecin pratiquant 'numRPPS'
     *         Son nom
     *         Son prenom
     *         Son adresse
     *         Son numéro de téléphone 
     *         Son adresse mail
     *         La date a laquelle il a été inscrit sur le site
     *         La date a laquelle il a commencer ses activités
     *         Le domaine dans le quel il pratique la medecine
     *         Son code Postal
     *         La ville où il habite
     */
    public function getMedecin($pdo, $idMedecin)
    {
        $sql = "SELECT idUser,idMedecin,numRPPS, nom, prenom, adresse, numTel, email, dateInscription, dateDebutActivites, activite, codePostal, ville 
                FROM Medecins 
                WHERE idMedecin = :idMedecin";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam('idMedecin', $idMedecin);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function updateMedecin($pdo,$idMedecin, $numRPPS, $nom, $prenom, $adresse, $cp, $ville, $tel, $mail, $secteurActivite, $dateDebutActivites) {
        if (!preg_match("#[1-9]{11}#",$numRPPS)) {
            throw new PDOException("Le numéro de Répertoire Partagé des Professionnels intervenant dans le système de Santé (RPPS) n'est pas valide ! ", 1);
        }
        if ($dateDebutActivites == "") {
            throw new PDOException("Veuillez selectionner une date ! ",2);
        }
        $sql = "UPDATE Medecins 
        SET nom = :nom, 
            prenom = :prenom, 
            adresse = :adresse ,
            codePostal = :cp, 
            ville = :ville, 
            numTel = :tel, 
            email = :mail, 
            activite = :secteurActivite, 
            dateDebutActivites = :dateDebutActivites,
            numRPPS = :numRPPS 
            WHERE idMedecin = :idMedecin";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam('numRPPS', $numRPPS);
        $stmt->bindParam('nom', $nom);
        $stmt->bindParam('prenom', $prenom);
        $stmt->bindParam('adresse', $adresse);
        $stmt->bindParam('cp', $cp);
        $stmt->bindParam('ville', $ville);
        $stmt->bindParam('tel', $tel);
        $stmt->bindParam('mail', $mail);
        $stmt->bindParam('secteurActivite', $secteurActivite);
        $stmt->bindParam('dateDebutActivites', $dateDebutActivites);
        $stmt->bindParam('idMedecin', $idMedecin);
        $stmt->execute();
    }

    public function addMedecin($pdo,$idUser ,$numRPPS, $nom, $prenom, $adresse, $cp, $ville, $tel, $mail, $secteurActivite, $dateDebutActivites) {
        if (!preg_match("#[1-9]{11}#",$numRPPS)) {
            throw new PDOException("Le numéro de Répertoire Partagé des Professionnels intervenant dans le système de Santé (RPPS) n'est pas valide ! ", 1);
        }
        if ($dateDebutActivites == "") {
            throw new PDOException("Veuillez selectionner une date ! ",2);
        }
        $sql = "INSERT INTO Medecins (idUser,numRPPS, nom, prenom, adresse, codePostal, ville, numTel, email, activite, dateDebutActivites, dateInscription) VALUE (:idUser,:numRPPS, :nom, :prenom, :adresse, :cp, :ville, :tel, :mail, :secteurActivite, :dateDebutActivites, CURDATE())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam('idUser', $idUser);
        $stmt->bindParam('numRPPS', $numRPPS);
        $stmt->bindParam('nom', $nom);
        $stmt->bindParam('prenom', $prenom);
        $stmt->bindParam('adresse', $adresse);
        $stmt->bindParam('cp', $cp);
        $stmt->bindParam('ville', $ville);
        $stmt->bindParam('tel', $tel);
        $stmt->bindParam('mail', $mail);
        $stmt->bindParam('secteurActivite', $secteurActivite);
        $stmt->bindParam('dateDebutActivites', $dateDebutActivites);
        $stmt->execute();
        return $pdo->lastInsertId();
    }

    public function getErreursImportShort($pdo) {
        $sql = "SELECT messageErreur, COUNT(messageErreur) as nbreErreurs FROM ErreursImportation GROUP BY messageErreur ORDER BY nbreErreurs DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private static $defaultAdminService ;

    public static function getDefaultAdminService()
    {
        if (AdminService::$defaultAdminService == null) {
            AdminService::$defaultAdminService = new AdminService();
        }
        return AdminService::$defaultAdminService;
    }

}
