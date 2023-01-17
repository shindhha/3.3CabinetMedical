-- Création des tables liés à l'enregistrement des médicaments


/* -------------------------------------------- Creation de la table des designations (DesignationElemPharma) -------------------------------------------- */

CREATE TABLE DesignationElemPharma (
    idDesignation INT(3) AUTO_INCREMENT PRIMARY KEY,
    designation TEXT
);

/* -------------------------------------------- Creation de la table des formes pharmaceutiques (FormePharma) -------------------------------------------- */

CREATE TABLE FormePharma (
    idFormePharma INT(3),
    formePharma TEXT
);
ALTER TABLE FormePharma ADD CONSTRAINT PK_FormePharma PRIMARY KEY (idFormePharma);
ALTER TABLE FormePharma MODIFY idFormePharma INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table des statuts d'AMM (StatutAdAMM) -------------------------------------------- */

CREATE TABLE StatutAdAMM (
    idStatutAdAMM INT(3),
    statutAdAMM VARCHAR(25)
);
ALTER TABLE StatutAdAMM ADD CONSTRAINT PK_StatutAdAMM PRIMARY KEY (idStatutAdAMM);
ALTER TABLE StatutAdAMM MODIFY idStatutAdAMM INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table des types de procédure (TypeProc) -------------------------------------------- */

CREATE TABLE TypeProc (
    idTypeProc INT(3),
    typeProc TEXT
);
ALTER TABLE TypeProc ADD CONSTRAINT PK_TypeProc PRIMARY KEY (idTypeProc);
ALTER TABLE TypeProc MODIFY idTypeProc INT(3) AUTO_INCREMENT;


/* -------------------------------------------- Creation de la table des autorisations européennes (AutorEurop)  -------------------------------------------- */

CREATE TABLE AutorEurop (
    idAutoEur INT(3),
    autoEur TEXT
);
ALTER TABLE AutorEurop ADD CONSTRAINT PK_AutorEurop PRIMARY KEY (idAutoEur);
ALTER TABLE AutorEurop MODIFY idAutoEur INT(3) AUTO_INCREMENT;



/* -------------------------------------------- Creation de la table principale des medicaments (CIS_BDPM) -------------------------------------------- */

CREATE TABLE CIS_BDPM (
    codeCIS INT(6),
    idDesignation INT(3),
    idFormePharma INT(3),
    idStatutAdAMM INT(3),
    idTypeProc INT(3),
    etatCommercialisation BOOL,
    dateAMM DATE,
    statutBdm BOOL,
    idAutoEur INT(3),
    surveillanceRenforcee BOOL
);
ALTER TABLE CIS_BDPM ADD CONSTRAINT PK_CIS_BDPM PRIMARY KEY (codeCIS);
ALTER TABLE CIS_BDPM ADD CONSTRAINT FK_CIS_BDPM_DesignationElemPharma FOREIGN KEY (idDesignation) REFERENCES DesignationElemPharma(idDesignation);
ALTER TABLE CIS_BDPM ADD CONSTRAINT FK_CIS_BDPM_AutorEurop FOREIGN KEY (idAutoEur) REFERENCES AutorEurop(idAutoEur);
ALTER TABLE CIS_BDPM ADD CONSTRAINT FK_CIS_BDPM_FormePharma FOREIGN KEY (idFormePharma) REFERENCES FormePharma(idFormePharma);
ALTER TABLE CIS_BDPM ADD CONSTRAINT FK_CIS_BDPM_StatutAdAMM FOREIGN KEY (idStatutAdAMM) REFERENCES StatutAdAMM(idStatutAdAMM);
ALTER TABLE CIS_BDPM ADD CONSTRAINT FK_CIS_BDPM_TypeProc FOREIGN KEY (idTypeProc) REFERENCES TypeProc(idTypeProc);




/* -------------------------------------------- Creation de la table TauxRemboursement -------------------------------------------- */

CREATE TABLE TauxRemboursement (
    codeCIS INT(6),
    tauxRemboursement NUMERIC(6,2)
);
ALTER TABLE TauxRemboursement ADD CONSTRAINT PK_TauxRemboursement PRIMARY KEY (codeCIS);
ALTER TABLE TauxRemboursement ADD CONSTRAINT FK_TauxRemboursement_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);





/* -------------------------------------------- Creation de la table LibellePresentation -------------------------------------------- */

CREATE TABLE LibellePresentation (
    idLibellePresentation INT(3),
    libellePresentation TEXT
);
ALTER TABLE LibellePresentation ADD CONSTRAINT PK_LibellePresentation PRIMARY KEY (idLibellePresentation);
ALTER TABLE LibellePresentation MODIFY idLibellePresentation INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table EtatCommercialisation -------------------------------------------- */

CREATE TABLE EtatCommercialisation (
    idEtatCommercialisation INT(3),
    labelEtatCommercialisation TEXT
);
ALTER TABLE EtatCommercialisation ADD CONSTRAINT PK_EtatCommercialisation PRIMARY KEY (idEtatCommercialisation);
ALTER TABLE EtatCommercialisation MODIFY idEtatCommercialisation INT(3) AUTO_INCREMENT;


/* -------------------------------------------- Creation de la table CIS_CIP_BDPM -------------------------------------------- */

CREATE TABLE CIS_CIP_BDPM (
    codeCIS INT(6),
    codeCIP7 INT(7),
    idLibellePresentation INT(3),
    statutAdminiPresentation BOOL,
    idEtatCommercialisation INT(1),
    dateCommrcialisation DATE,
    codeCIP13 BIGINT(13) UNSIGNED, -- Bypass de la limite de 2.147 Md pour un int normal
    agrementCollectivites BOOL,
    prix NUMERIC(8,2),
    indicationRemboursement TEXT
);
ALTER TABLE CIS_CIP_BDPM ADD CONSTRAINT PK_CIS_CIP PRIMARY KEY (codeCIP13);
ALTER TABLE CIS_CIP_BDPM ADD CONSTRAINT FK_CIS_CIP_BDPM_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);
ALTER TABLE CIS_CIP_BDPM ADD CONSTRAINT FK_CIS_BDPM_LibellePresentation FOREIGN KEY (idLibellePresentation) REFERENCES LibellePresentation(idLibellePresentation);
ALTER TABLE CIS_CIP_BDPM ADD CONSTRAINT FK_CIS_BDPM_EtatCommercialisation FOREIGN KEY (idEtatCommercialisation) REFERENCES EtatCommercialisation(idEtatCommercialisation);





/* -------------------------------------------- Creation de la table GroupeGener -------------------------------------------- */

CREATE TABLE GroupeGener (
    idGroupeGener INT(4),
    labelGroupeGener TEXT
);
ALTER TABLE GroupeGener ADD CONSTRAINT PK_GroupeGener PRIMARY KEY (idGroupeGener);

/* -------------------------------------------- Creation de la table CIS_GENER -------------------------------------------- */

CREATE TABLE CIS_GENER (
    codeCIS INT(6),
    idGroupeGener INT(4),
    typeGenerique INT(1),
    numeroTri INT(2)
);
ALTER TABLE CIS_GENER ADD CONSTRAINT PK_CIS_GENER PRIMARY KEY (codeCIS);
ALTER TABLE CIS_GENER ADD CONSTRAINT FK_CIS_GENER_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);
ALTER TABLE CIS_GENER ADD CONSTRAINT FK_CIS_GENER_GroupeGener FOREIGN KEY (idGroupeGener) REFERENCES GroupeGener(idGroupeGener);




/* -------------------------------------------- Creation de la table DesignationElem -------------------------------------------- */
CREATE TABLE DesignationElem (
    idElem INT(3),
    labelElem VARCHAR(100)
);
ALTER TABLE DesignationElem ADD CONSTRAINT PK_DesignationElem PRIMARY KEY (idElem);
ALTER TABLE DesignationElem MODIFY idElem INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table CodeSubstance -------------------------------------------- */
CREATE TABLE CodeSubstance (
    idSubstance INT(3),
    varianceNom INT(2),
    codeSubstance TEXT
);
ALTER TABLE CodeSubstance ADD CONSTRAINT PK_CodeSubstance PRIMARY KEY (idSubstance, varianceNom);

/* -------------------------------------------- Creation de la table Dosage -------------------------------------------- */
CREATE TABLE Dosage (
    idDosage INT(3),
    labelDosage VARCHAR(100)
);
ALTER TABLE Dosage ADD CONSTRAINT PK_Dosage PRIMARY KEY (idDosage);
ALTER TABLE Dosage MODIFY idDosage INT(3) AUTO_INCREMENT;


/* -------------------------------------------- Creation de la table RefDosage -------------------------------------------- */
CREATE TABLE RefDosage (
    idRefDosage INT(3),
    labelRefDosage VARCHAR(100)
);
ALTER TABLE RefDosage ADD CONSTRAINT PK_RefDosage PRIMARY KEY (idRefDosage);
ALTER TABLE RefDosage MODIFY idRefDosage INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table CIS_COMPO -------------------------------------------- */

CREATE TABLE CIS_COMPO (
    codeCIS INT(6),
    idDesignationElemPharma INT(3),
    idCodeSubstance INT(6),
    varianceNomSubstance INT(3),
    idDosage INT(3),
    idRefDosage INT(3),
    natureCompo BOOL,
    noLiaison INT(3)
);
ALTER TABLE CIS_COMPO ADD CONSTRAINT PK_CIS_COMPO PRIMARY KEY (codeCIS);
ALTER TABLE CIS_COMPO ADD CONSTRAINT FK_CIS_COMPO_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);
ALTER TABLE CIS_COMPO ADD CONSTRAINT FK_CIS_COMPO_DesignationElem FOREIGN KEY (idDesignationElemPharma) REFERENCES DesignationElem(idElem);
ALTER TABLE CIS_COMPO ADD CONSTRAINT FK_CIS_COMPO_CodeSubstance FOREIGN KEY (idCodeSubstance, varianceNomSubstance) REFERENCES CodeSubstance(idSubstance, varianceNom);
ALTER TABLE CIS_COMPO ADD CONSTRAINT FK_CIS_COMPO_RefDosage FOREIGN KEY (idRefDosage) REFERENCES RefDosage(idRefDosage);
ALTER TABLE CIS_COMPO ADD CONSTRAINT FK_CIS_COMPO_Dosage FOREIGN KEY (idDosage) REFERENCES Dosage(idDosage);


/* -------------------------------------------- Creation de la table ID_Label_VoieAdministration -------------------------------------------- */
CREATE TABLE ID_Label_VoieAdministration (
    idVoieAdministration INT(3),
    labelVoieAdministration TEXT
);
ALTER TABLE ID_Label_VoieAdministration ADD CONSTRAINT PK_ID_Label_VoieAdministration PRIMARY KEY (idVoieAdministration);
ALTER TABLE ID_Label_VoieAdministration MODIFY idVoieAdministration INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table CIS_VoieAdministration -------------------------------------------- */
CREATE TABLE CIS_VoieAdministration (
    codeCIS INT(6),
    idVoieAdministration INT(3)
);
ALTER TABLE CIS_VoieAdministration ADD CONSTRAINT PK_CIS_VoieAdministration PRIMARY KEY (codeCIS, idVoieAdministration);
ALTER TABLE CIS_VoieAdministration ADD CONSTRAINT FK_CIS_VoieAdministration_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);
ALTER TABLE CIS_VoieAdministration ADD CONSTRAINT FK_CIS_VoieAdministration_ID_Label_VoieAdministration FOREIGN KEY (idVoieAdministration) REFERENCES ID_Label_VoieAdministration(idVoieAdministration);





/* -------------------------------------------- Creation de la table Condition -------------------------------------------- */
CREATE TABLE LabelCondition (
    idCondition INT(3),
    labelCondition TEXT
);
ALTER TABLE LabelCondition ADD CONSTRAINT PK_Condition PRIMARY KEY (idCondition);
ALTER TABLE LabelCondition MODIFY idCondition INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table CIS_CPD -------------------------------------------- */
CREATE TABLE CIS_CPD (
    codeCIS INT(6),
    idCondition INT(3)
);
ALTER TABLE CIS_CPD ADD CONSTRAINT PK_CIS_CPD PRIMARY KEY (codeCIS);
ALTER TABLE CIS_CPD ADD CONSTRAINT FK_CIS_CPD_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);
ALTER TABLE CIS_CPD ADD CONSTRAINT FK_CIS_CPD_Condition FOREIGN KEY (idCondition) REFERENCES LabelCondition(idCondition);




/* -------------------------------------------- Creation de la table Info_Texte -------------------------------------------- */
CREATE TABLE Info_Texte (
    idTexte INT(3),
    labelTexte TEXT
);
ALTER TABLE Info_Texte ADD CONSTRAINT PK_Info_Texte PRIMARY KEY (idTexte);
ALTER TABLE Info_Texte MODIFY idTexte INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table CIS_INFO -------------------------------------------- */
CREATE TABLE CIS_INFO (
    codeCIS INT(6),
    dateDebutInformation DATE,
    DateFinInformation DATE,
    idTexte INT(3)
);
ALTER TABLE CIS_INFO ADD CONSTRAINT PK_CIS_INFO PRIMARY KEY (codeCIS);
ALTER TABLE CIS_INFO ADD CONSTRAINT FK_CIS_INFO_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);
ALTER TABLE CIS_INFO ADD CONSTRAINT FK_CIS_INFO_Info_Texte FOREIGN KEY (idTexte) REFERENCES Info_Texte(idTexte);





/* -------------------------------------------- Creation de la table ID_Label_Titulaire -------------------------------------------- */
CREATE TABLE ID_Label_Titulaire (
    idTitulaire INT(3),
    labelTitulaire TEXT
);
ALTER TABLE ID_Label_Titulaire ADD CONSTRAINT PK_ID_Label_Titulaire PRIMARY KEY (idTitulaire);
ALTER TABLE ID_Label_Titulaire MODIFY idTitulaire INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table CIS_Titulaires -------------------------------------------- */
CREATE TABLE CIS_Titulaires (
    codeCIS INT(6),
    idTitulaire INT(3)
);
ALTER TABLE CIS_Titulaires ADD CONSTRAINT PK_CIS_Titulaires PRIMARY KEY (codeCIS, idTitulaire);
ALTER TABLE CIS_Titulaires ADD CONSTRAINT FK_CIS_Titulaires_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);
ALTER TABLE CIS_Titulaires ADD CONSTRAINT FK_CIS_Titulaires_ID_Label_Titulaire FOREIGN KEY (idTitulaire) REFERENCES ID_Label_Titulaire(idTitulaire);




/* -------------------------------------------- Creation de la table LibelleSmr -------------------------------------------- */
CREATE TABLE LibelleSmr (
    idLibelleSMR INT(3),
    libelleSmr TEXT
);
ALTER TABLE LibelleSmr ADD CONSTRAINT PK_LibelleSmr PRIMARY KEY (idLibelleSMR);
ALTER TABLE LibelleSmr MODIFY idLibelleSMR INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table HAS_LiensPageCT -------------------------------------------- */
CREATE TABLE HAS_LiensPageCT (
    codeHAS VARCHAR(8),
    lienPage TEXT
);
ALTER TABLE HAS_LiensPageCT ADD CONSTRAINT PK_HAS_LiensPageCT PRIMARY KEY (codeHAS);

/* -------------------------------------------- Creation de la table LibelleAsmr -------------------------------------------- */
CREATE TABLE LibelleAsmr (
    idLibelleAsmr INT(3),
    libelleAsmr TEXT
);
ALTER TABLE LibelleAsmr ADD CONSTRAINT PK_LibelleAsmr PRIMARY KEY (idLibelleAsmr);
ALTER TABLE LibelleAsmr MODIFY idLibelleAsmr INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table MotifEval -------------------------------------------- */
CREATE TABLE MotifEval ( -- INFO : pas sur le schéma car limite de forme lucid chart :/
    idMotifEval INT(3),
    libelleMotifEval VARCHAR(255)
);
ALTER TABLE MotifEval ADD CONSTRAINT PK_MotifEval PRIMARY KEY (idMotifEval);
ALTER TABLE MotifEval MODIFY idMotifEval INT(3) AUTO_INCREMENT;


/* -------------------------------------------- Creation de la table NiveauSMR -------------------------------------------- */
-- TODO Ajouter sur le schéma
CREATE TABLE NiveauSMR (
    idNiveauSMR INT(2),
    libelleNiveauSMR VARCHAR(255)
);
ALTER TABLE NiveauSMR ADD CONSTRAINT PK_NiveauSMR PRIMARY KEY (idNiveauSMR);
ALTER TABLE NiveauSMR MODIFY idNiveauSMR INT(2) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table CIS_HAS_SMR -------------------------------------------- */
CREATE TABLE CIS_HAS_SMR (
    codeCIS INT(6),
    codeHAS VARCHAR(8),
    idMotifEval INT(2),
    dateAvis DATE,
    niveauSMR INT(2),
    idLibelleSmr INT(3)
);
ALTER TABLE CIS_HAS_SMR ADD CONSTRAINT FK_CIS_HAS_SMR_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);
ALTER TABLE CIS_HAS_SMR ADD CONSTRAINT FK_CIS_HAS_SMR_HAS_LiensPageCT FOREIGN KEY (codeHAS) REFERENCES HAS_LiensPageCT(codeHAS);
ALTER TABLE CIS_HAS_SMR ADD CONSTRAINT FK_CIS_HAS_SMR_MotifEval FOREIGN KEY (idMotifEval) REFERENCES MotifEval(idMotifEval);
ALTER TABLE CIS_HAS_SMR ADD CONSTRAINT FK_CIS_HAS_SMR_LibelleSmr FOREIGN KEY (idLibelleSmr) REFERENCES LibelleSmr(idLibelleSMR);
ALTER TABLE CIS_HAS_SMR ADD CONSTRAINT FK_CIS_HAS_SMR_NiveauSMR FOREIGN KEY (niveauSMR) REFERENCES NiveauSMR(idNiveauSMR);

/* -------------------------------------------- Creation de la table CIS_HAS_ASMR -------------------------------------------- */
CREATE TABLE CIS_HAS_ASMR (
    codeCIS INT(6),
    codeHAS VARCHAR(8),
    idMotifEval INT(2),
    dateAvis DATE,
    valeurASMR TEXT,
    idLibelleAsmr INT(3)
);
ALTER TABLE CIS_HAS_ASMR ADD CONSTRAINT FK_CIS_HAS_ASMR_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);
ALTER TABLE CIS_HAS_ASMR ADD CONSTRAINT FK_CIS_HAS_ASMR_HAS_LiensPageCT FOREIGN KEY (codeHAS) REFERENCES HAS_LiensPageCT(codeHAS);
ALTER TABLE CIS_HAS_ASMR ADD CONSTRAINT FK_CIS_HAS_ASMR_MotifEval FOREIGN KEY (idMotifEval) REFERENCES MotifEval(idMotifEval);
ALTER TABLE CIS_HAS_ASMR ADD CONSTRAINT FK_CIS_HAS_ASMR_LibelleAsmr FOREIGN KEY (idLibelleAsmr) REFERENCES LibelleAsmr(idLibelleAsmr);

/* -------------------------------------------- Creation de la table ErreursImportation -------------------------------------------- */
CREATE TABLE ErreursImportation (
    idErreur INT(5),
    dateErreur DATETIME DEFAULT CURRENT_TIMESTAMP,
    nomProcedure TEXT,
    messageErreur TEXT
);

ALTER TABLE ErreursImportation ADD CONSTRAINT PK_ErreursImportation PRIMARY KEY (idErreur);
ALTER TABLE ErreursImportation MODIFY idErreur INT(5) AUTO_INCREMENT;

-- Création des tables liés aux médecins / patients / utilisateurs
DROP TABLE IF EXISTS ListeVisites;
DROP TABLE IF EXISTS Patients;
DROP TABLE IF EXISTS Medecins;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS Ordonnances;
DROP TABLE IF EXISTS Visites;
DROP TABLE IF EXISTS Cabinet;



CREATE TABLE Patients (
	idPatient INT AUTO_INCREMENT PRIMARY KEY,
	numSecu CHAR(13) UNIQUE,
	LieuNaissance VARCHAR(200),
	nom VARCHAR(25),
	prenom VARCHAR(25),
	dateNaissance DATE,
	adresse VARCHAR(50),
	codePostal INT,
	ville VARCHAR(255),
	medecinRef CHAR(11),
	numTel INT(9),
	email VARCHAR(50),
	notes TEXT,
	sexe BOOLEAN
);

CREATE TABLE Medecins (
    idUser INT,
	idMedecin INT AUTO_INCREMENT ,
	numRPPS CHAR(11) UNIQUE,
	nom VARCHAR(25),
	prenom VARCHAR(25),
	adresse VARCHAR(255),
	numTel INT,
	email VARCHAR(100),
	dateInscription DATE,
	dateDebutActivites DATE,
	activite VARCHAR(100),
	codePostal INT,
	ville VARCHAR(255),
	lieuAct VARCHAR(100),
	PRIMARY KEY (idMedecin)
);
CREATE TABLE Visites (
	idVisite INT AUTO_INCREMENT,
	motifVisite TEXT,
	dateVisite DATE,
	Description TEXT,
	Conclusion TEXT,
	PRIMARY KEY (idVisite)
);

CREATE TABLE ListeVisites (
	idMedecin INT,
	idPatient INT,
	idVisite INT,
	PRIMARY KEY (idVisite,idPatient)
);

CREATE TABLE Cabinet (
	adresse VARCHAR(255),
	codePostal INT,
	ville VARCHAR(255),
	dateOuverture DATE
);
CREATE TABLE Ordonnances (
	idVisite INT,
	codeCIP7 INT,
	instruction TEXT,
	PRIMARY KEY (idVisite,codeCIP7)
);
CREATE TABLE users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	login VARCHAR(11) UNIQUE,
	`password` CHAR(32)

);
ALTER TABLE Medecins ADD CONSTRAINT FK_Medecins_Users FOREIGN KEY (idUser) REFERENCES users(id);
ALTER TABLE Patients ADD CONSTRAINT CK_Email_Patients CHECK (email LIKE '%@%.%');
ALTER TABLE ListeVisites ADD CONSTRAINT FK_ListeVisites_Medecins FOREIGN KEY (idMedecin) REFERENCES Medecins(idMedecin);
ALTER TABLE ListeVisites ADD CONSTRAINT FK_ListeVisites_Visites FOREIGN KEY (idVisite) REFERENCES Visites(idVisite);
ALTER TABLE ListeVisites ADD CONSTRAINT FK_ListeVisites_Patients FOREIGN KEY (idPatient) REFERENCES Patients(idPatient);
ALTER TABLE Ordonnances ADD CONSTRAINT FK_Visites_Ordonnances FOREIGN KEY (idVisite) REFERENCES Visites(idVisite);
ALTER TABLE Medecins ADD CONSTRAINT CK_Email_Medecins CHECK (email LIKE '%@%.%');

-- Créations des vues

DROP VIEW IF EXISTS listMedic;

CREATE VIEW listMedic as
select CIS_BDPM.codeCIS,formePharma,labelVoieAdministration,etatCommercialisation,tauxRemboursement,prix,libellePresentation,surveillanceRenforcee,valeurASMR,libelleNiveauSMR,designation,codeCIP7 from CIS_BDPM
LEFT JOIN CIS_CIP_BDPM
ON CIS_BDPM.codeCIS = CIS_CIP_BDPM.codeCIS
LEFT JOIN CIS_VoieAdministration
ON CIS_BDPM.codeCIS = CIS_VoieAdministration.codeCIS
LEFT JOIN CIS_HAS_SMR
ON CIS_BDPM.codeCIS = CIS_HAS_SMR.codeCIS
LEFT JOIN CIS_HAS_ASMR
ON CIS_BDPM.codeCIS = CIS_HAS_ASMR.codeCIS
LEFT JOIN FormePharma
ON CIS_BDPM.idFormePharma = FormePharma.idFormePharma
LEFT JOIN ID_Label_VoieAdministration
ON CIS_VoieAdministration.idVoieAdministration = ID_Label_VoieAdministration.idVoieAdministration
LEFT JOIN TauxRemboursement
ON CIS_BDPM.codeCIS = TauxRemboursement.codeCIS
LEFT JOIN LibellePresentation
ON LibellePresentation.idLibellePresentation = CIS_CIP_BDPM.idLibellePresentation
LEFT JOIN NiveauSMR
ON NiveauSMR.idNiveauSMR = CIS_HAS_SMR.niveauSMR
LEFT JOIN DesignationElemPharma
ON CIS_BDPM.idDesignation = DesignationElemPharma.idDesignation;

-- Créations des fonctions utilisés pour l'importations des médicaments

DELIMITER //
DROP FUNCTION IF EXISTS NB_OCCURENCES//
DROP FUNCTION IF EXISTS SPLIT_EXPLODE//
DROP FUNCTION IF EXISTS INSERT_CODE_SUBSTANCE//
DROP FUNCTION IF EXISTS NB_OCCURENCES//

/* Fonction qui permet d'obtenir le nombre d'occurences d'un caractère dnas une chaine de caractères*/
CREATE FUNCTION NB_OCCURENCES(chaine TEXT, caractere TEXT) RETURNS INTEGER DETERMINISTIC
BEGIN
    RETURN LENGTH(chaine) - LENGTH(REPLACE(chaine, caractere, ''));
end //


/* Fonction qui permet d'obtenir à partir d'une chaine de plusieurs valeurs séparées par un délimiteur d'obtenir l'item de
   rang N (démarre à 1, renvoie NULL si position mauvaise)*/
DROP FUNCTION IF EXISTS SPLIT_EXPLODE//
CREATE FUNCTION SPLIT_EXPLODE(texte TEXT, delimiteur TEXT, position INT) RETURNS TEXT DETERMINISTIC
BEGIN
    DECLARE nb_cases INT;
    DECLARE resultat TEXT;

    SET nb_cases = NB_OCCURENCES(texte, delimiteur) + 1; -- Nombre de 'cases' dans le texte. Ici on veut la case position
    IF position > nb_cases OR position <= 0 THEN
        RETURN NULL;
    ELSE
        SET resultat = SUBSTRING_INDEX(SUBSTRING_INDEX(texte, delimiteur, position), delimiteur, -1);
        RETURN resultat;
    END IF;
end //


/*
 * Fonction qui permet l'insertion dans la table CodeSubstance
 * Utilisation d'une procédure pour pouvoir avoir une PK composite et un auto increment
 * Renvoie varianceNom (voir schéma table CodeSubstance
 *
 * (NB : Utilisation d'une variance pour avoir plusieurs nom pour une même substance)
 */
DROP FUNCTION IF EXISTS INSERT_CODE_SUBSTANCE//
CREATE FUNCTION INSERT_CODE_SUBSTANCE(N_idSubstance TEXT, N_libelle TEXT) RETURNS INT DETERMINISTIC
BEGIN
    DECLARE currentVariance INT;

    /* Sélection de la variance actuelle */
    SET currentVariance = (SELECT MAX(varianceNom) FROM CodeSubstance WHERE idSubstance = N_idSubstance);
    IF currentVariance IS NULL THEN
        SET currentVariance = -1;
    END IF;

    SET currentVariance = currentVariance + 1;

    /* Insertion de la nouvelle valeur */
    INSERT INTO CodeSubstance (idSubstance, varianceNom, codeSubstance) VALUES (N_idSubstance, currentVariance, N_libelle);

    RETURN currentVariance;
end //

DROP FUNCTION IF EXISTS updateSMR//
CREATE FUNCTION updateSMR(
                    N_codeCIS INT(11),
                    N_codeHAS TEXT,
                    N_MotifEval TEXT,
                    N_DateAvis DATE,
                    N_ValeurSMR TEXT,
                    N_LibelleSMR TEXT)

    RETURNS INT DETERMINISTIC
BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;
    DECLARE libelle_id INT;
    DECLARE motif_id INT;
    DECLARE idNiveauS INT;

    /* Si l'id du libelle SMR existe on update le lien sinon on crée le libelle et on update le lien */
    SELECT idLibelleSmr INTO libelle_id FROM LibelleSmr WHERE libelleSMR = N_LibelleSMR LIMIT 1;    
    IF (libelle_id IS NOT NULL) THEN
        UPDATE CIS_HAS_SMR SET idLibelleSmr = libelle_id WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO LibelleSmr (libelleSMR) VALUES (N_LibelleSMR);
        SET libelle_id = LAST_INSERT_ID();
        UPDATE CIS_HAS_SMR SET idLibelleSmr = libelle_id WHERE codeCIS = N_codeCIS;
    END IF;

    /* Si le lien n'existe pas, on le crée sinon on l'update */
    IF (SELECT COUNT(*) FROM HAS_LiensPageCT WHERE codeHAS = N_codeHAS) = 0 THEN
        SELECT importCT(N_codeHAS, "") INTO RETURN_CODE;
        UPDATE CIS_HAS_SMR SET codeHAS = N_codeHAS WHERE codeCIS = N_codeCIS;
    ELSE
        UPDATE CIS_HAS_SMR SET codeHAS = N_codeHAS WHERE codeCIS = N_codeCIS;
    END IF;

    /* Si l'id du motif existe on update le lien sinon on crée le le motivEval et on update le lien */
    SELECT idMotifEval INTO motif_id FROM MotifEval WHERE libelleMotifEval = N_MotifEval LIMIT 1;
    IF (motif_id IS NOT NULL) THEN
        UPDATE CIS_HAS_SMR SET idMotifEval = motif_id WHERE codeCIS = N_codeCIS;
    ELSE    
        INSERT INTO MotifEval (libelleMotifEval) VALUES (N_MotifEval);
        SET motif_id = LAST_INSERT_ID();
        UPDATE CIS_HAS_SMR SET idMotifEval = motif_id WHERE codeCIS = N_codeCIS;
    END IF;

    /* Si l'id du niveau SMR existe on update le lien sinon on crée le libelle du niveauSMR et on update le lien */
    SELECT idNiveauSMR INTO idNiveauS FROM NiveauSMR WHERE libelleNiveauSMR = N_ValeurSMR;
    IF (idNiveauS IS NOT NULL) THEN
        UPDATE CIS_HAS_SMR SET niveauSMR = idNiveauS WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO NiveauSMR (libelleNiveauSMR) VALUES (N_ValeurSMR);
        SET idNiveauS = LAST_INSERT_ID();
        UPDATE CIS_HAS_SMR SET niveauSMR = idNiveauS WHERE codeCIS = N_codeCIS;
    END IF;

    /* On update la date de l'avis */
    UPDATE CIS_HAS_SMR SET dateAvis = N_DateAvis WHERE codeCIS = N_codeCIS;

return RETURN_CODE;

END//

DROP PROCEDURE IF EXISTS PREPARE_IMPORT//
CREATE PROCEDURE PREPARE_IMPORT()
BEGIN

    SET FOREIGN_KEY_CHECKS = 0;
    TRUNCATE ErreursImportation;
    TRUNCATE CIS_HAS_SMR;
    TRUNCATE CIS_HAS_ASMR;
    TRUNCATE MotifEval;
    TRUNCATE NiveauSMR;
    TRUNCATE LibelleAsmr;
    TRUNCATE LibelleSmr;
    SET FOREIGN_KEY_CHECKS = 1;

END //

DROP FUNCTION IF EXISTS updateCT//
CREATE FUNCTION updateCT(
                    N_codeHAS VARCHAR(8),
                    N_lienPage TEXT) RETURNS INT DETERMINISTIC
BEGIN
    -- INFO : il faut inpérativement exécuter l'update des liens avant l'update
    --        de SMR et ASMR pour que les liens ne soient pas vides.
    DECLARE RETURN_CODE INT DEFAULT 0;

    /* Si le lien n'existe pas, on le crée sinon on l'update  */
    IF (SELECT COUNT(*) FROM HAS_LiensPageCT WHERE codeHAS = N_codeHAS) = 0 THEN
        SELECT importCT(N_codeHAS, N_lienPage) INTO RETURN_CODE;
    ELSE
        UPDATE HAS_LiensPageCT SET lienPage = N_lienPage WHERE codeHAS = N_codeHAS;
    END IF;

    RETURN RETURN_CODE;

end //

DROP FUNCTION IF EXISTS updateINFO//
CREATE FUNCTION updateINFO(
                    N_codeCIS INT(11),
                    N_dateDebut DATE,
                    N_dateFin DATE,
                    N_texte TEXT)

    RETURNS INT DETERMINISTIC
BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;
    DECLARE idText INT;

    /* Si l'id du texte existe on update le lien sinon on crée le texte et on update le lien */
    SELECT idTexte INTO idText FROM Info_Texte WHERE labelTexte = N_texte;
    IF (idText IS NOT NULL) THEN
        UPDATE CIS_INFO SET idTexte = idText WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO Info_Texte (labeltexte) VALUES (N_texte);
        SET idText = LAST_INSERT_ID();
        UPDATE CIS_INFO SET idTexte = idText WHERE codeCIS = N_codeCIS;
    END IF;

    /* On update la date de debut de l'info */
    UPDATE CIS_INFO SET dateDebutInformation = N_dateDebut WHERE codeCIS = N_codeCIS;

    /* On update la date de fin de l'info */
    UPDATE CIS_INFO SET DateFinInformation = N_dateFin WHERE codeCIS = N_codeCIS;

    return RETURN_CODE;

END//

DROP FUNCTION IF EXISTS updateGENER//
CREATE FUNCTION updateGENER(
                    N_idGroupeGener INT,
                    N_libellegroupeGener TEXT,
                    N_codeCis INT(11),
                    N_typeGener INT(1),
                    N_noTri INT(2)
                    )
    RETURNS INT DETERMINISTIC

BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;
    DECLARE grGener INT;

    /* Si l'id du groupeGener existe alors on update le lien sinon on le crée et on fait le lien */
    SELECT idGroupeGener INTO grGener FROM GroupeGener WHERE idGroupeGener = N_idGroupeGener LIMIT 1;
    IF (grGener IS NOT NULL) THEN 
        UPDATE GroupeGener SET labelGroupeGener = N_libellegroupeGener WHERE idGroupeGener = N_idGroupeGener;
    END IF;

    /* On update le type générique */
    UPDATE CIS_GENER SET typeGenerique = N_typeGener WHERE codeCIS = N_codeCIS;

    /* On update le numero de tri */
    UPDATE CIS_GENER SET numeroTri = N_noTri WHERE codeCIS = N_codeCIS;

return RETURN_CODE;

END//

DROP FUNCTION IF EXISTS updateCPD//
CREATE FUNCTION updateCPD(
                    N_codeCIS INT(11),
                    N_condition TEXT)

    RETURNS INT DETERMINISTIC
BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;
    DECLARE idCond INT;
    
    /* Si idCondition existe on update le lien sinon on le crée et on fait le lien */
    SELECT idCondition INTO idCond FROM LabelCondition WHERE labelCondition = N_condition LIMIT 1;
    IF (idCond IS NOT NULL) THEN
        UPDATE CIS_CPD SET idCondition = idCond WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO LabelCondition (labelcondition) VALUES (N_condition);
        SET idCond = LAST_INSERT_ID();
        UPDATE CIS_CPD SET idCondition = idCond WHERE codeCIS = N_codeCIS;
    END IF;

return RETURN_CODE;

END//

DROP FUNCTION IF EXISTS updateCOMPO//
CREATE FUNCTION updateCOMPO(
                    N_codeCIS INT(6),
                    N_designationElem TEXT,
                    N_idSubstance INT(6),
                    N_denomSubstance TEXT,
                    N_dosage TEXT,
                    N_refDosage TEXT,
                    N_natureComposant TEXT,
                    N_numeroLisaison INT(3))

    RETURNS INT DETERMINISTIC
BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;
    DECLARE variance INT;
    DECLARE idSub INT;
    DECLARE idDos INT;
    DECLARE idRefD INT;
    DECLARE idDes INT;

    /* On récupere les deux liens (varianceNom et idSubstance) si ils existent alors on update le lien 
       sinon on crée la substance et on fait le lien en commencant par la variance puis l'idSubstance */
    SELECT varianceNom INTO variance FROM CodeSubstance WHERE idSubstance = N_idSubstance AND codeSubstance = N_denomSubstance LIMIT 1;
    SELECT idSubstance INTO idSub FROM CodeSubstance WHERE idSubstance = N_idSubstance AND codeSubstance = N_denomSubstance LIMIT 1;
    IF (idSub IS NOT NULL AND variance IS NOT NULL) THEN
        UPDATE CIS_COMPO SET idCodeSubstance = idSub, varianceNomSubstance = variance WHERE codeCIS = N_codeCIS;
    ELSE
        SET variance = INSERT_CODE_SUBSTANCE(N_idSubstance, N_denomSubstance);
        SELECT idSubstance INTO idSub FROM CodeSubstance WHERE idSubstance = N_idSubstance AND codeSubstance = N_denomSubstance LIMIT 1;
        UPDATE CIS_COMPO SET idCodeSubstance = idSub, varianceNomSubstance = variance WHERE codeCIS = N_codeCIS;
    END IF;

    /* Si idDosage existe alors on update le lien sinon on crée le labelDosage et on fait le lien */
    SELECT idDosage INTO idDos FROM Dosage WHERE labelDosage = N_dosage LIMIT 1;
    IF (idDos IS NOT NULL) THEN
        UPDATE CIS_COMPO SET idDosage = idDos WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO Dosage (labelDosage) VALUES (N_dosage);
        SELECT idDosage INTO idDos FROM Dosage WHERE labelDosage = N_dosage LIMIT 1;
        UPDATE CIS_COMPO SET idDosage = idDos WHERE codeCIS = N_codeCIS;
    END IF;

    /* Si idRefDosage existe alors on update le lien sinon on crée le labelRefDosage et on fait le lien */
    SELECT idRefDosage INTO idRefD FROM RefDosage WHERE labelRefDosage = N_refDosage LIMIT 1;
    IF (idRefD IS NOT NULL) THEN
        UPDATE CIS_COMPO SET idRefDosage = idRefD WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO RefDosage (labelRefDosage) VALUES (N_refDosage);
        SELECT idRefDosage INTO idRefD FROM RefDosage WHERE labelRefDosage = N_refDosage LIMIT 1;
        UPDATE CIS_COMPO SET idRefDosage = idRefD WHERE codeCIS = N_codeCIS;
    END IF;

    /* Détermination de la nature compo (boolean, SA = true, FT = false) */
    IF N_natureComposant = 'SA' THEN
        SET N_natureComposant = 1;
    ELSE
        SET N_natureComposant = 0;
    END IF;

    /* Update de la nature de la composition */
    UPDATE CIS_COMPO SET natureCompo = N_natureComposant WHERE codeCIS = N_codeCIS;

    /* Update du numero de liaison */
    UPDATE CIS_COMPO SET noLiaison = N_numeroLisaison WHERE codeCIS = N_codeCIS;

    /* Si idElem (idDes) existe on update le nom du médicament sinon on crée le nom du médicament et on fait le lien */
    SELECT idElem INTO idDes FROM DesignationElem WHERE labelElem = N_designationElem LIMIT 1;
    IF (idDes IS NOT NULL) THEN
        UPDATE CIS_COMPO SET idDesignationElemPharma = idDes WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO DesignationElem (labelElem) VALUES (N_designationElem);
        SET idDes = LAST_INSERT_ID();
        UPDATE CIS_COMPO SET idDesignationElemPharma = idDes WHERE codeCIS = N_codeCIS;
    END IF;
    

return RETURN_CODE;

END//

DROP FUNCTION IF EXISTS updateCIP//
CREATE FUNCTION updateCIP(
                        N_codeCIS INT(6),
                        N_codeCIP7 INT(7),
                        N_libellePresentation TEXT,
                        N_statutAdminiPresentation TEXT,
                        N_labelEtatCommercialisation TEXT,
                        N_dateCommercialisation DATE,
                        N_codeCIP13 BIGINT(13) UNSIGNED,
                        N_agrementCollectivite TEXT,
                        N_tauxRemboursement TEXT,
                        N_prix1 VARCHAR(10),
                        Unknown1 VARCHAR(250),
                        Unknown2 VARCHAR(250),
                        N_indicationRemboursement TEXT)
    RETURNS INT DETERMINISTIC
BEGIN
    DECLARE RETURN_CODE INT DEFAULT 0;

    DECLARE idLibellePres INT;
    DECLARE idEtatComm INT;
    DECLARE codeCISTaux INT;
    DECLARE N_prix DECIMAL(8,2);

    IF N_prix1 = "" THEN
        SET N_prix = 0.00;
    ELSE
        SET N_prix = CAST(N_prix1 AS DECIMAL(8,2));
    END IF;

    /* Si l'id du libellePresentation existe on update le lien sinon on crée le libelle et on fait le lien */
    SELECT idLibellePresentation INTO idLibellePres FROM LibellePresentation WHERE libellePresentation = N_libellePresentation LIMIT 1;
    IF (idLibellePres IS NOT NULL) THEN
        UPDATE CIS_CIP_BDPM SET idLibellePresentation = idLibellePres WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO LibellePresentation (libellePresentation) VALUES (N_libellePresentation);
        SET idLibellePres = LAST_INSERT_ID();
        UPDATE CIS_CIP_BDPM SET idLibellePresentation = idLibellePres WHERE codeCIS = N_codeCIS;
    END IF;

    /* Conversion du texte statutAdmini en boolean */
    IF N_statutAdminiPresentation = 'Présentation active' THEN
        SET @statutAdminiPresentation = 1;
    ELSE
        SET @statutAdminiPresentation = 0;
    END IF;

    /* Update statutAdminiPresentation */
    UPDATE CIS_CIP_BDPM SET statutAdminiPresentation = @statutAdminiPresentation WHERE codeCIS = N_codeCIS;

    /* Si idEtatComm non NULL alors update sinon on crée l'EtatCommercialisation et on fait le lien */
    SELECT idEtatCommercialisation INTO idEtatComm FROM EtatCommercialisation WHERE labelEtatCommercialisation = N_labelEtatCommercialisation LIMIT 1;
    IF (idEtatComm IS NOT NULL) THEN
        UPDATE CIS_CIP_BDPM SET idEtatCommercialisation = idEtatComm WHERE codeCIS = N_codeCIS;    
    ELSE
        INSERT INTO EtatCommercialisation (labelEtatCommercialisation) VALUES (N_labelEtatCommercialisation);
        SET idEtatComm = LAST_INSERT_ID();
        UPDATE CIS_CIP_BDPM SET idEtatCommercialisation = idEtatComm WHERE codeCIS = N_codeCIS;
    END IF;

    /* Conversion du texte aggrément collectivités en booléen */
    if (N_agrementCollectivite = 'oui') THEN
        SET @agrementCollectivite = 1;
    ELSE
        SET @agrementCollectivite = 0;
    END IF;
    
    /* Update de agrementCollectivite */
    UPDATE CIS_CIP_BDPM SET agrementCollectivites = @agrementCollectivite WHERE codeCIS = N_codeCIS;

    /* update du taux de remboursement
       On vérifie si le taux n'est pas vide, retire le '%' et on l'update */
    SELECT codeCIS into codeCISTaux FROM TauxRemboursement WHERE codeCIS = N_codeCIS LIMIT 1;
    IF (N_tauxRemboursement != '' AND codeCISTaux IS NOT NULL ) THEN
        SET @tauxRemboursement = REPLACE(N_tauxRemboursement, '%', '');
        SET @tauxRemboursement = CAST(@tauxRemboursement AS DECIMAL(5,2));
        UPDATE TauxRemboursement SET tauxRemboursement = @tauxRemboursement WHERE codeCIS = N_codeCIS;
    END IF;

    /* UPDATE codeCIP7 */
    UPDATE CIS_CIP_BDPM SET codeCIP7 = N_codeCIP7 WHERE codeCIS = N_codeCIS;
    
    /* UPDATE prix */
    UPDATE CIS_CIP_BDPM SET prix = N_prix WHERE codeCIS = N_codeCIS;

    /* UPDATE dateCommercialisation */
    UPDATE CIS_CIP_BDPM SET dateCommrcialisation = N_dateCommercialisation WHERE codeCIS = N_codeCIS;

    /* UPDATE indication Remboursement */
    UPDATE CIS_CIP_BDPM SET indicationRemboursement = N_indicationRemboursement WHERE codeCIS = N_codeCIS;


return RETURN_CODE;

END//

DROP FUNCTION IF EXISTS updateBDPM//
CREATE FUNCTION updateBDPM(
                    N_codeCIS INT(6),
                    N_desElemPharma TEXT,   -- V
                    N_formePharma TEXT,     -- V
                    N_voieAdministration TEXT,      -- V
                    N_statutAdAMM VARCHAR(25),      -- V
                    N_typeProc TEXT,        -- V
                    N_etatCommercialisation TEXT,   -- V
                    N_dateAAM DATE,                 -- V
                    N_statutBDM TEXT,               -- V
                    N_autoEur TEXT,         -- V
                    N_titulaires TEXT,              -- V
                    N_surveillanceRenforcee TEXT)   -- V
    RETURNS INT DETERMINISTIC
BEGIN

    DECLARE idDes INT;
    DECLARE idFormPharm INT;
    DECLARE idStatAdAMM INT;
    DECLARE idType INT;
    DECLARE idAuto INT;
    DECLARE idVoieA INT;
    DECLARE idTit INT;

    DECLARE return_code INT;
    SET return_code = 0;

    /* Si idDes existe Update le nom du médicament sinon crée le nom du médicament et on fait le lien */
    SELECT idDesignation INTO idDes FROM DesignationElemPharma WHERE designation = N_desElemPharma LIMIT 1;
    IF (idDes IS NOT NULL) THEN
        UPDATE CIS_BDPM SET idDesignation = idDes WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO DesignationElemPharma (designation) VALUES (N_desElemPharma);
        SET idDes = LAST_INSERT_ID();
        UPDATE CIS_BDPM SET idDesignation = idDes WHERE codeCIS = N_codeCIS;
    END IF;

    /* Si idFormPharm existe Update la forme pharmaceutique du médicament sinon crée la forme pharmaceutique du médicament */
    SELECT idFormePharma INTO idFormPharm FROM FormePharma WHERE formePharma = N_formePharma LIMIT 1;
    IF (idFormPharm IS NOT NULL) THEN
        UPDATE CIS_BDPM SET idFormePharma = idFormPharm WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO FormePharma (formePharma) VALUES (N_formePharma);
        SET idFormPharm = LAST_INSERT_ID();
        UPDATE CIS_BDPM SET idFormePharma = idFormPharm WHERE codeCIS = N_codeCIS;
    END IF;

    /* 
     * Si idStatAdAMM existe Update le statutAdAMM du médicament 
     * sinon crée le statutAdAMM du médicament 
     */
    SELECT idStatutAdAMM INTO idStatAdAMM FROM StatutAdAMM WHERE statutAdAMM = N_statutAdAMM LIMIT 1;
    IF (idStatAdAMM IS NOT NULL) THEN
        UPDATE CIS_BDPM SET idStatutAdAMM = idStatAdAMM WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO StatutAdAMM (statutAdAMM) VALUES (N_statutAdAMM);
        SET idStatAdAMM = LAST_INSERT_ID();
        UPDATE CIS_BDPM SET idStatutAdAMM = idStatAdAMM WHERE codeCIS = N_codeCIS;
    END IF;

    /* 
     * Si idType existe Update le type de procédure du médicament 
     * sinon crée le type de procédure du médicament 
     */
    SELECT idTypeProc INTO idType FROM TypeProc WHERE typeProc = N_typeProc LIMIT 1;
    IF (idType IS NOT NULL) THEN
        UPDATE CIS_BDPM SET idTypeProc = idType WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO TypeProc (typeProc) VALUES (N_typeProc);
        SET idType = LAST_INSERT_ID();
        UPDATE CIS_BDPM SET idTypeProc = idType WHERE codeCIS = N_codeCIS;
    END IF;

    /* Détermination du booléen état commericlaisation */
    IF N_etatCommercialisation = 'Commercialisée' THEN
        SET @etatCommercialisation = 1;
    ELSE
        SET @etatCommercialisation = 0;
    END IF;

    /* Détermination du booléen du statut BDM  */
    IF N_statutBDM = 'Alerte' THEN
        SET @statutBDM = 1;
    ELSE
        IF N_statutBDM = 'Warning disponibilité' THEN
            SET @statutBDM = 0;
        ELSE
            SET @statutBDM = NULL;
        END IF;
    END IF;

    /* Détermination du booléen surveillance renforcée */
    IF N_surveillanceRenforcee = 'Oui' THEN
        SET @surveillanceRenforcee = 1;
    ELSE
        SET @surveillanceRenforcee = 0;
    END IF;

    /* Si idAuto existe Update AutorEuro sinon crée AutorEuro */
    SELECT idAutoEur INTO idAuto FROM AutorEurop WHERE autoEur = N_autoEur  LIMIT 1;
    IF (idAuto IS NOT NULL) THEN
        UPDATE CIS_BDPM SET idAutoEur = idAuto WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO AutorEurop (autoEur) VALUES (N_autoEur);
        SET idAuto = LAST_INSERT_ID();
        UPDATE CIS_BDPM SET idAutoEur = idAuto WHERE codeCIS = N_codeCIS;
    END IF;

    /* Update de l'etat commercial */
    UPDATE CIS_BDPM SET etatCommercialisation = @etatCommercialisation where codeCIS = N_codeCIS;
    
    /* Update de la dateAMM */
    UPDATE CIS_BDPM SET dateAMM = N_dateAAM where codeCIS = N_codeCIS;
    
    /* Update du statut BDM */
    UPDATE CIS_BDPM SET statutBDM = @statutBDM where codeCIS = N_codeCIS;
    
    /* Update de surveillance renforcée */
    UPDATE CIS_BDPM SET surveillanceRenforcee = @surveillanceRenforcee where codeCIS = N_codeCIS;

    
    /* Update des voies d'administrations du médicament dans la BDD
       Splitté par le caractère ";" */
    SET @voieAdm = NB_OCCURENCES(N_voieAdministration, ';') + 1;
    SET @i = 1;
    /* Supprime les liens */
    DELETE FROM CIS_VoieAdministration WHERE codeCIS = N_codeCIS;
    WHILE @i <= @voieAdm DO
        SET @voie = SPLIT_EXPLODE(N_voieAdministration, ';', @i);
        SELECT idVoieAdministration INTO idVoieA FROM ID_Label_VoieAdministration WHERE labelVoieAdministration = @voie LIMIT 1;
        -- On recrée le lien dans la table si la voie d'administration existe
        IF (idVoieA IS NOT NULL) THEN
            INSERT INTO CIS_VoieAdministration (codeCIS, idVoieAdministration) VALUES (N_codeCIS, idVoieA);
        ELSE
            -- On enregistre le nom de la voie dans la BDD et on fait le lien
            INSERT INTO ID_Label_VoieAdministration (labelVoieAdministration) VALUES (@voie);
            SELECT idVoieAdministration INTO idVoieA FROM ID_Label_VoieAdministration WHERE labelVoieAdministration = @voie LIMIT 1;
            INSERT INTO CIS_VoieAdministration (codeCIS, idVoieAdministration) VALUES (N_codeCIS, idVoieA);
        END IF;

        SET @i = @i + 1;
    END WHILE;

    /* Update dans BDD de titulaires, séparé dans de très rares cas par ';' */
    SET @i = 1;
    SET @nb_titulaires = NB_OCCURENCES(N_titulaires, ';') + 1;
    DELETE FROM CIS_Titulaires WHERE codeCIS = N_codeCIS;
    WHILE @i <= @nb_titulaires DO
        SET @titulaire = SPLIT_EXPLODE(N_titulaires, ';', @i);
        SELECT idTitulaire INTO idTit FROM ID_Label_Titulaire WHERE labelTitulaire = @titulaire LIMIT 1;
        -- On recrée le lien dans la table si le(s) titulaire(s) existe
        IF (idTit IS NOT NULL) THEN
            -- On update le label de la voie d'administration si idTit existe
            INSERT INTO CIS_Titulaires (codeCIS, idTitulaire) VALUES (N_codeCIS, idTit);
        ELSE
            -- On enregistre le nom du titulaire dans la BDD et on fait le lien
            INSERT INTO ID_Label_Titulaire (labelTitulaire) VALUES (@titulaire);
            SELECT idTitulaire INTO idTit FROM ID_Label_Titulaire WHERE labelTitulaire = @titulaire LIMIT 1;
            INSERT INTO CIS_Titulaires (codeCIS, idTitulaire) VALUES (N_codeCIS, idTit);
        END IF;
    
        SET @i = @i + 1;
    END WHILE;

    RETURN return_code;

end //

DROP FUNCTION IF EXISTS updateASMR//
CREATE FUNCTION updateASMR(
                            N_codeCIS INT(11),
                            N_codeHAS TEXT,
                            N_MotifEval TEXT,
                            N_DateAvis DATE,
                            N_ValeurASMR TEXT,
                            N_LibelleASMR TEXT)

    RETURNS INT DETERMINISTIC
BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;

    DECLARE idLibelleAs INT;
    DECLARE idMotifEv INT;
    DECLARE codeHASTest VARCHAR(8);

    /* Update du libelle si idLibelleAs non NULL */
    SELECT idLibelleAsmr INTO idLibelleAs FROM LibelleAsmr WHERE libelleAsmr = N_LibelleASMR LIMIT 1;
    IF (idLibelleAs IS NOT NULL) THEN
        UPDATE CIS_HAS_ASMR SET idLibelleAsmr = idLibelleAs WHERE codeCIS = N_codeCIS;
    ELSE
        /* Insertion du libelle et création du lien */
        INSERT INTO LibelleAsmr (libelleAsmr) VALUES (N_LibelleASMR);
        SET idLibelleAs = LAST_INSERT_ID();
        UPDATE CIS_HAS_ASMR SET idLibelleAsmr = idLibelleAs WHERE codeCIS = N_codeCIS;
    END IF;
    
    /* Update du motif de l'évaluation dans ASMR si idMotifEval non NULL sinon le crée */
    SELECT idMotifEval INTO idMotifEv FROM MotifEval WHERE libelleMotifEval = N_MotifEval LIMIT 1;
    IF (idMotifEv IS NOT NULL) THEN
        UPDATE CIS_HAS_ASMR SET idMotifEval = idMotifEv WHERE codeCIS = N_codeCIS;
    ELSE
        /* Insertion du motif de l'évaluation et création du lien */
        INSERT INTO MotifEval (libelleMotifEval) VALUES (N_MotifEval);
        SET idMotifEv = LAST_INSERT_ID();
        UPDATE CIS_HAS_ASMR SET idMotifEval = idMotifEv WHERE codeCIS = N_codeCIS;
    END IF;

    /* update de la date de l'avis de commission de la transparence */
    UPDATE CIS_HAS_ASMR SET dateAvis = N_DateAvis WHERE codeCIS = N_codeCIS;

    /* update de la valeur ASMR */
    UPDATE CIS_HAS_ASMR SET valeurASMR = N_ValeurASMR WHERE codeCIS = N_codeCIS;

    /* update codeHAS */
    SELECT codeHAS INTO codeHASTest FROM HAS_LiensPageCT WHERE codeHAS = N_codeHAS LIMIT 1;
    if (codeHASTest IS NOT NULL) THEN
        UPDATE CIS_HAS_ASMR SET codeHAS = N_codeHAS WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO HAS_LiensPageCT (codeHAS, lienPage) VALUES (N_codeHAS, ' ');
        UPDATE CIS_HAS_ASMR SET codeHAS = N_codeHAS WHERE codeCIS = N_codeCIS;
    END IF;

return RETURN_CODE;

END//

DROP FUNCTION IF EXISTS importSMR//
CREATE FUNCTION importSMR(
                    N_codeCIS INT(11),
                    N_codeHAS TEXT,
                    N_MotifEval TEXT,
                    N_DateAvis DATE,
                    N_ValeurSMR TEXT,
                    N_LibelleSMR TEXT)

    RETURNS INT DETERMINISTIC
BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;
    DECLARE libelle_id INT;

    /* Insertion du libelle */
    INSERT INTO LibelleSmr (libelleSmr) VALUES (N_LibelleSMR);
    SET libelle_id = LAST_INSERT_ID();

    /* Si le lien n'existe pas, on crée un lien vide */
    IF (SELECT COUNT(*) FROM HAS_LiensPageCT WHERE codeHAS = N_codeHAS) = 0 THEN
        INSERT INTO HAS_LiensPageCT (codeHAS, lienPage) VALUES (N_codeHAS, '');
    END IF;

    /* Insertion du motif de l'évaluation */
    IF (SELECT COUNT(*) FROM MotifEval WHERE idMotifEval = N_MotifEval) = 0 THEN
        INSERT INTO MotifEval (libelleMotifEval) VALUES (N_MotifEval);
    END IF;

    /* Insertion du niveauSMR dans la bdd */
    IF (SELECT COUNT(*) FROM NiveauSMR WHERE libelleNiveauSMR = N_ValeurSMR) = 0 THEN
        INSERT INTO NiveauSMR (libelleNiveauSmr) VALUES (N_ValeurSMR);
    END IF;

    /* Insertion de l'avis */
    INSERT INTO CIS_HAS_SMR (codeCIS,
                             codeHAS,
                             idMotifEval,
                             dateAvis,
                             niveauSMR,
                             idLibelleSmr)
    VALUES
        (N_codeCIS,
        N_codeHAS,
        (SELECT idMotifEval FROM MotifEval WHERE libelleMotifEval = N_MotifEval LIMIT 1),
        N_DateAvis,
        (SELECT idNiveauSmr FROM NiveauSMR WHERE libelleNiveauSmr = N_ValeurSMR LIMIT 1),
        libelle_id);

return RETURN_CODE;

END//

DROP FUNCTION IF EXISTS importCT//
CREATE FUNCTION importCT(
                    N_codeHAS VARCHAR(8),
                    N_lienPage TEXT) RETURNS INT DETERMINISTIC
BEGIN
    -- INFO : il faut inpérativement exécuter l'import des liens avant l'import
    --        de SMR et ASMR pour que les liens ne soient pas vides.
    DECLARE RETURN_CODE INT DEFAULT 0;

    INSERT INTO HAS_LiensPageCT (codeHAS, lienPage) VALUES (N_codeHAS, N_lienPage);

    RETURN RETURN_CODE;

end //

DROP FUNCTION IF EXISTS importINFO//
CREATE FUNCTION importINFO(
                    N_codeCIS INT(11),
                    N_dateDebut DATE,
                    N_dateFin DATE,
                    N_texte TEXT)

    RETURNS INT DETERMINISTIC
BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;

    /* Insertion du texte dans la table contenant tous les textes d'informations */
    IF (SELECT COUNT(*) FROM Info_Texte WHERE labelTexte = N_texte) = 0 THEN
        INSERT INTO Info_Texte (labeltexte) VALUES (N_texte);
    END IF;


    /* Insertion dans CIS_INFO */
    INSERT INTO CIS_INFO (codeCIS,
                          dateDebutInformation,
                          DateFinInformation,
                          idTexte)
    VALUES (N_codeCIS,
            N_dateDebut,
            N_dateFin,
            (SELECT idTexte FROM Info_Texte WHERE labelTexte = N_texte));

return RETURN_CODE;

END//

DELIMITER //

DROP FUNCTION IF EXISTS importGENER//
CREATE FUNCTION importGENER(
                    N_idGroupeGener INT,
                    N_libellegroupeGener TEXT,
                    N_codeCis INT(11),
                    N_typeGener INT(1),
                    N_noTri INT(2)
                    )
    RETURNS INT DETERMINISTIC

BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;

    /* Insertion du label */
    IF (SELECT COUNT(*) FROM GroupeGener WHERE idGroupeGener = N_idGroupeGener) = 0 THEN
        INSERT INTO GroupeGener (idGroupeGener, labelGroupeGener) VALUES (N_idGroupeGener, N_libellegroupeGener);
    END IF;

    /* Insertion dans cis_gener */
    INSERT INTO CIS_GENER (codeCIS, idGroupeGener, typeGenerique, numeroTri) VALUES (N_codeCis, N_idGroupeGener, N_typeGener, N_noTri);

return RETURN_CODE;

END//

DROP FUNCTION IF EXISTS importCPD//
CREATE FUNCTION importCPD(
                    N_codeCIS INT(11),
                    N_condition TEXT)

    RETURNS INT DETERMINISTIC
BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;

    /* Insertion de la condition dans la DB */
    IF (SELECT COUNT(*) FROM LabelCondition WHERE labelCondition = N_condition) = 0 THEN
        INSERT INTO LabelCondition (labelcondition) VALUES (N_condition);
    END IF;

    /* Insertion dans CIS_CPD */
    INSERT INTO CIS_CPD (codeCIS, idCondition) VALUES (N_codeCIS, (SELECT idCondition FROM LabelCondition WHERE labelCondition = N_condition));

return RETURN_CODE;

END//

DROP FUNCTION IF EXISTS importCOMPO//
CREATE FUNCTION importCOMPO(
                    N_codeCIS INT(6),
                    N_designationElem TEXT,
                    N_idSubstance INT(6),
                    N_denomSubstance TEXT,
                    N_dosage TEXT,
                    N_refDosage TEXT,
                    N_natureComposant TEXT,
                    N_numeroLisaison INT(3))

    RETURNS INT DETERMINISTIC
BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;
    DECLARE variance INT;

    /* Création de la substance dans la bd (Utilisation d'une fn custom) */
    IF (SELECT COUNT(*) FROM CodeSubstance WHERE idSubstance = N_idSubstance AND codeSubstance = N_denomSubstance) = 0 THEN
        SET variance = INSERT_CODE_SUBSTANCE(N_idSubstance, N_denomSubstance);
    ELSE
        SELECT varianceNom INTO variance FROM CodeSubstance WHERE idSubstance = N_idSubstance AND codeSubstance = N_denomSubstance;
    END IF;

    /* Insertion de la designation dans la bdd */
    IF (SELECT COUNT(*) FROM DesignationElem WHERE labelElem = N_designationElem) = 0 THEN
        INSERT INTO DesignationElem(labelElem) VALUES (N_designationElem);
    END IF;

    /* Si la variance vaut -1 (valeur par défaut si pas d'insertion), on la récupère depuis la bd
    IF variance = -1 THEN
        SELECT varianceNom INTO variance FROM CodeSubstance WHERE idSubstance = N_idSubstance AND codeSubstance = N_denomSubstance;
    END IF;
    */

    /* Insertion du dosage dans la bd */
    IF (SELECT COUNT(*) FROM RefDosage WHERE labelRefDosage = N_refDosage) = 0 THEN
        INSERT INTO RefDosage(labelRefDosage) VALUES (N_refDosage);
    END IF;

    /* Détermination de la nature compo (boolean, SA = true, FT = false) */
    IF N_natureComposant = 'SA' THEN
        SET N_natureComposant = 1;
    ELSE
        SET N_natureComposant = 0;
    END IF;

    /* Insertion du dosage das la DB */
    IF (SELECT COUNT(*) FROM Dosage WHERE labelDosage = N_dosage) = 0 THEN
        INSERT INTO Dosage(labelDosage) VALUES (N_dosage);
    END IF;

    /* Insertion dans COMPO */
    INSERT INTO CIS_COMPO(codeCIS,
                          idDesignationElemPharma,
                          idCodeSubstance,
                          varianceNomSubstance,
                          idDosage,
                          idRefDosage,
                          natureCompo,
                          noLiaison)
    VALUES (N_codeCIS,
            (SELECT idDesignationElemPharma FROM DesignationElem WHERE labelElem = N_designationElem LIMIT 1),
            N_idSubstance,
            variance,
            (SELECT Dosage.idDosage FROM Dosage WHERE Dosage.idDosage = N_dosage),
            (SELECT idRefDosage FROM RefDosage WHERE labelRefDosage = N_refDosage LIMIT 1),
            N_natureComposant,
            N_numeroLisaison);

return RETURN_CODE;

END//

DROP FUNCTION IF EXISTS importCIP//
CREATE FUNCTION importCIP(
                    N_codeCIS INT(6),
                    N_codeCIP7 INT(7),
                    N_libellePresentation TEXT,
                    N_statutAdminiPresentation TEXT,
                    N_labelEtatCommercialisation TEXT,
                    N_dateCommercialisation DATE,
                    N_codeCIP13 BIGINT(13) UNSIGNED, -- Bypass de la limite de 2.147 Md pour un int normal
                    N_agrementCollectivite TEXT,
                    N_tauxRemboursement TEXT,
                    N_prix1 VARCHAR(10),
                    Unknown1 VARCHAR(250),
                    Unknown2 VARCHAR(250),
                    N_indicationRemboursement TEXT)
    RETURNS INT DETERMINISTIC
BEGIN
    DECLARE RETURN_CODE INT DEFAULT 0;
    DECLARE N_prix DECIMAL(8,2);

    IF N_prix1 = "" THEN
        SET N_prix = 0.00;
    ELSE
        SET N_prix = CAST(N_prix1 AS DECIMAL(8,2));
    END IF;

    /* Creation du libellé dans la bdd */
    IF (SELECT COUNT(*) FROM LibellePresentation WHERE libellePresentation = N_libellePresentation) = 0 THEN
        INSERT INTO LibellePresentation (libellePresentation) VALUES (N_libellePresentation);
    END IF;

    /* Conversion du texte statutAdmini en boolean */
    IF N_statutAdminiPresentation = 'Présentation active' THEN
        SET @statutAdminiPresentation = 1;
    ELSE
        SET @statutAdminiPresentation = 0;
    END IF;

    /* Creation du texte etat commercialisation dans la BDD */
    IF (SELECT COUNT(*) FROM EtatCommercialisation WHERE labelEtatCommercialisation = N_labelEtatCommercialisation) = 0 THEN
        INSERT INTO EtatCommercialisation (labelEtatCommercialisation) VALUES (N_labelEtatCommercialisation);
    END IF;

    /* Conversion du texte aggrément collectivités en booléen */
    if (N_agrementCollectivite = 'oui') THEN
        SET @agrementCollectivite = 1;
    ELSE
        SET @agrementCollectivite = 0;
    END IF;


    /* Insertion du taux de remboursement
       On vérifie si le taux n'est pas vide, retire le '%' et on l'insère */
    IF N_tauxRemboursement != '' AND (SELECT COUNT(*) FROM TauxRemboursement WHERE codeCIS = N_codeCIS) = 0 THEN
        SET @tauxRemboursement = REPLACE(N_tauxRemboursement, '%', '');
        SET @tauxRemboursement = CAST(@tauxRemboursement AS DECIMAL(5,2));
        INSERT INTO TauxRemboursement (codeCIS, tauxRemboursement) VALUE (N_codeCIS, @tauxRemboursement);
    END IF;


    /* Insertion du CIP */
    INSERT INTO CIS_CIP_BDPM (
                              codeCIS,
                              codeCIP7,
                              idLibellePresentation,
                              statutAdminiPresentation,
                              idEtatCommercialisation,
                              dateCommrcialisation,
                              codeCIP13,
                              agrementCollectivites,
                              prix,
                              indicationRemboursement
                              )
        VALUES (
                N_codeCIS,
                N_codeCIP7,
                (SELECT LibellePresentation.idLibellePresentation FROM LibellePresentation WHERE libellePresentation = N_libellePresentation),
                @statutAdminiPresentation,
                (SELECT EtatCommercialisation.idEtatCommercialisation FROM EtatCommercialisation WHERE labelEtatCommercialisation = N_labelEtatCommercialisation),
                N_dateCommercialisation,
                N_codeCIP13,
                @agrementCollectivite,
                N_prix,
                N_indicationRemboursement
                );

return RETURN_CODE;

END//

DROP FUNCTION IF EXISTS importBDPM//
CREATE FUNCTION importBDPM(
                    N_codeCIS INT(6),
                    N_desElemPharma TEXT,   -- V
                    N_formePharma TEXT,     -- V
                    N_voieAdministration TEXT,      -- V
                    N_statutAdAMM VARCHAR(25),      -- V
                    N_typeProc TEXT,        -- V
                    N_etatCommercialisation TEXT,   -- V
                    N_dateAAM DATE,                 -- V
                    N_statutBDM TEXT,               -- V
                    N_autoEur TEXT,         -- V
                    N_titulaires TEXT,              -- V
                    N_surveillanceRenforcee TEXT)   -- V
    RETURNS INT DETERMINISTIC
BEGIN
    DECLARE return_code INT;
    SELECT 0 INTO return_code;

    /* Création du nom du médicament dans la BDD */
    IF (SELECT COUNT(*) FROM DesignationElemPharma WHERE designation = N_desElemPharma) = 0 THEN
        INSERT INTO DesignationElemPharma (designation) VALUES (N_desElemPharma);
    END IF;

    /* Création de la forme pharmaceutique du médicament dans la BDD */
    IF (SELECT COUNT(*) FROM FormePharma WHERE formePharma = N_formePharma) = 0 THEN
        INSERT INTO FormePharma (formePharma) VALUES (N_formePharma);
    END IF;

    /* Création du statut AdAMM dans la BDD */
    IF (SELECT COUNT(*) FROM StatutAdAMM WHERE statutAdAMM = N_statutAdAMM) = 0 THEN
        INSERT INTO StatutAdAMM (statutAdAMM) VALUES (N_statutAdAMM);
    END IF;

    /* Création du type de procédure dans la BDD */
    IF (SELECT COUNT(*) FROM TypeProc WHERE typeProc = N_typeProc) = 0 THEN
        INSERT INTO TypeProc (typeProc) VALUES (N_typeProc);
    END IF;

    /* Détermination du booléen état commericlaisation */
    IF N_etatCommercialisation = 'Commercialisée' THEN
        SET @etatCommercialisation = 1;
    ELSE
        SET @etatCommercialisation = 0;
    END IF;

    /* Détermination du booléen du statut BDM  */
    IF N_statutBDM = 'Alerte' THEN
        SET @statutBDM = 1;
    ELSE
        IF N_statutBDM = 'Warning disponibilité' THEN
            SET @statutBDM = 0;
        ELSE
            SET @statutBDM = NULL;
        END IF;
    END IF;

    /* Détermination du booléen surveillance renforcée */
    IF N_surveillanceRenforcee = 'Oui' THEN
        SET @surveillanceRenforcee = 1;
    ELSE
        SET @surveillanceRenforcee = 0;
    END IF;

    /* Insertion dans BDD de autoEur */
    IF (SELECT COUNT(*) FROM AutorEurop WHERE autoEur = N_autoEur) = 0 THEN
        INSERT INTO AutorEurop (autoEur) VALUES (N_autoEur);
    END IF;

    /* Insertion dans BDD BDPM */
    INSERT INTO CIS_BDPM (
        codeCIS,
        idDesignation,
        idFormePharma,
        idStatutAdAMM,
        idTypeProc,
        etatCommercialisation,
        dateAMM,
        statutBDM,
        idAutoEur,
        surveillanceRenforcee
    ) VALUES (
        N_codeCIS,
        (SELECT idDesignation FROM DesignationElemPharma WHERE designation = N_desElemPharma),
        (SELECT idFormePharma FROM FormePharma WHERE formePharma = N_formePharma),
        (SELECT idStatutAdAMM FROM StatutAdAMM WHERE statutAdAMM = N_statutAdAMM),
        (SELECT idTypeProc FROM TypeProc WHERE typeProc = N_typeProc),
        @etatCommercialisation,
        N_dateAAM,
        @statutBDM,
        (SELECT idAutoEur FROM AutorEurop WHERE autoEur = N_autoEur),
        @surveillanceRenforcee
    );


    /* Créations des voies d'administrations du médicament dans la BDD
       Splitté par le caractère ";" */
    SET @voieAdm = NB_OCCURENCES(N_voieAdministration, ';') + 1;
    SET @i = 1;
    WHILE @i <= @voieAdm DO
        SET @voie = SPLIT_EXPLODE(N_voieAdministration, ';', @i);
        -- On enregistre le nom de la voie dans la BDD
        IF (SELECT COUNT(*) FROM ID_Label_VoieAdministration WHERE labelVoieAdministration = @voie) = 0 THEN
            INSERT INTO ID_Label_VoieAdministration (labelVoieAdministration) VALUES (@voie);
        END IF;

        -- On définit le medicament comme étant administré par cette voie
        INSERT INTO CIS_VoieAdministration (codeCIS, idVoieAdministration) VALUES (N_codeCIS, (SELECT idVoieAdministration FROM ID_Label_VoieAdministration WHERE labelVoieAdministration = @voie));

        SET @i = @i + 1;
    END WHILE;

    /* Insertion dans BDD de titulaires, séparé dans ded très rares cas par ';' */
    SET @i = 1;
    SET @nb_titulaires = NB_OCCURENCES(N_titulaires, ';') + 1;
    WHILE @i <= @nb_titulaires DO
        -- On enregistre le nom du titulaire dans la BDD
        SET @titulaire = SPLIT_EXPLODE(N_titulaires, ';', @i);
        IF (SELECT COUNT(*) FROM ID_Label_Titulaire WHERE labelTitulaire = @titulaire) = 0 THEN
            INSERT INTO ID_Label_Titulaire (labelTitulaire) VALUES (@titulaire);
        END IF;

        -- On définit le medicament comme étant administré par cette voie
        INSERT INTO CIS_Titulaires (codeCIS, idTitulaire) VALUES (N_codeCIS, (SELECT idTitulaire FROM ID_Label_Titulaire WHERE labelTitulaire = @titulaire));
        SET @i = @i + 1;
    END WHILE;

    RETURN return_code;

end //

DROP FUNCTION IF EXISTS importASMR//
CREATE FUNCTION importASMR(
                    N_codeCIS INT(6),
                    N_codeHAS TEXT,
                    N_MotifEval TEXT,
                    N_DateAvis DATE,
                    N_ValeurASMR TEXT,
                    N_LibelleASMR TEXT)

    RETURNS INT DETERMINISTIC
BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;
    DECLARE libelle_id INT;

    /* Insertion du libelle */
    INSERT INTO LibelleAsmr (libelleAsmr) VALUES (N_LibelleASMR);
    SET libelle_id = LAST_INSERT_ID();

    /* Si le lien n'existe pas, on crée un lien vide */
    IF (SELECT COUNT(*) FROM HAS_LiensPageCT WHERE codeHAS = N_codeHAS) = 0 THEN
        INSERT INTO HAS_LiensPageCT (codeHAS, lienPage) VALUES (N_codeHAS, '');
    END IF;

    /* Insertion du motif de l'évaluation */
    IF (SELECT COUNT(*) FROM MotifEval WHERE idMotifEval = N_MotifEval) = 0 THEN
        INSERT INTO MotifEval (libelleMotifEval) VALUES (N_MotifEval);
    END IF;

    /* Insertion de l'avis */
    INSERT INTO CIS_HAS_ASMR (codeCIS,
                             codeHAS,
                             idMotifEval,
                             dateAvis,
                             valeurASMR,
                             idLibelleAsmr)
    VALUES
        (N_codeCIS,
        N_codeHAS,
        (SELECT idMotifEval FROM MotifEval WHERE libelleMotifEval = N_MotifEval LIMIT 1),
        N_DateAvis,
        N_ValeurASMR,
        libelle_id);

return RETURN_CODE;

END//

DELIMITER ;

INSERT INTO users (id, login, password) VALUES (1, 'admin', MD5('admin'));
