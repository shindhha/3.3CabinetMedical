DROP FUNCTION IF EXISTS NB_OCCURENCES;
DROP FUNCTION IF EXISTS SPLIT_EXPLODE;
DROP FUNCTION IF EXISTS INSERT_CODE_SUBSTANCE;
DROP FUNCTION IF EXISTS importBDPM;
DROP FUNCTION IF EXISTS updateBDPM;
DROP FUNCTION IF EXISTS importCIP;
DROP FUNCTION IF EXISTS updateCIP;
DROP FUNCTION IF EXISTS importCOMPO;
DROP FUNCTION IF EXISTS updateCOMPO;
DROP FUNCTION IF EXISTS importGENER;
DROP FUNCTION IF EXISTS updateGENER;
DELIMITER //

/* Fonction qui permet d'obtenir le nombre d'occurences d'un caractère dnas une chaine de caractères*/
CREATE FUNCTION NB_OCCURENCES(chaine TEXT, caractere TEXT) RETURNS INTEGER DETERMINISTIC
BEGIN
    RETURN LENGTH(chaine) - LENGTH(REPLACE(chaine, caractere, ''));
end //


/* Fonction qui permet d'obtenir à partir d'une chaine de plusieurs valeurs séparées par un délimiteur d'obtenir l'item de
   rang N (démarre à 1, renvoie NULL si position mauvaise)*/
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



DELIMITER //

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


DELIMITER //

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
        -- On update le lien avec le label de la voie d'administration si idVoieA existe sinon on le crée et on fait le lien
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
    WHILE @i <= @nb_titulaires DO
        SET @titulaire = SPLIT_EXPLODE(N_titulaires, ';', @i);
        SELECT idTitulaire INTO idTit FROM ID_Label_Titulaire WHERE labelTitulaire = @titulaire LIMIT 1;
        IF (idTit IS NOT NULL) THEN
            -- On update le label de la voie d'administration si idTit existe
            UPDATE CIS_Titulaires SET idTitulaire = idTit WHERE codeCIS = N_codeCIS;
        ELSE
            -- On enregistre le nom du titulaire dans la BDD et on fait le lien
            INSERT INTO ID_Label_Titulaire (labelTitulaire) VALUES (@titulaire);
            SELECT idTitulaire INTO idTit FROM ID_Label_Titulaire WHERE labelTitulaire = @titulaire LIMIT 1;
            UPDATE CIS_Titulaires SET idTitulaire = idTit WHERE codeCIS = N_codeCIS;
        END IF;
    
        SET @i = @i + 1;
    END WHILE;

    RETURN return_code;

end //


DELIMITER //

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
                    N_prix NUMERIC(6,2),
                    Unknown1 VARCHAR(250),
                    Unknown2 VARCHAR(250),
                    N_indicationRemboursement TEXT)
    RETURNS INT DETERMINISTIC
BEGIN
    DECLARE RETURN_CODE INT DEFAULT 0;

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


DELIMITER //

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
                        N_prix NUMERIC(6,2),
                        Unknown1 VARCHAR(250),
                        Unknown2 VARCHAR(250),
                        N_indicationRemboursement TEXT)
    RETURNS INT DETERMINISTIC
BEGIN
    DECLARE RETURN_CODE INT DEFAULT 0;

    DECLARE idLibellePres INT;
    DECLARE idEtatComm INT;
    DECLARE codeCISTaux INT;

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

    /* UPDATE codeCIP13 */
    UPDATE CIS_CIP_BDPM SET codeCIP7 = N_codeCIP7 WHERE codeCIS = N_codeCIS;
    
    /* UPDATE prix */
    UPDATE CIS_CIP_BDPM SET prix = N_prix WHERE codeCIS = N_codeCIS;

    /* UPDATE dateCommercialisation */
    UPDATE CIS_CIP_BDPM SET dateCommrcialisation = N_dateCommercialisation WHERE codeCIS = N_codeCIS;

    /* UPDATE indication Remboursement */
    UPDATE CIS_CIP_BDPM SET indicationRemboursement = N_indicationRemboursement;


return RETURN_CODE;

END//


DELIMITER //

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
    DECLARE variance INT DEFAULT -1;

    /* Création de la substance dans la bd (Utilisation d'une fn custom) */
    IF (SELECT COUNT(*) FROM CodeSubstance WHERE idSubstance = N_idSubstance AND codeSubstance = N_denomSubstance) = 0 THEN
        SET variance = INSERT_CODE_SUBSTANCE(N_idSubstance, N_denomSubstance);
    END IF;

    /* Insertion de la designation dans la bdd */
    IF (SELECT COUNT(*) FROM DesignationElem WHERE labelElem = N_designationElem) = 0 THEN
        INSERT INTO DesignationElem(labelElem) VALUES (N_designationElem);
    END IF;

    /* Si la variance vaut -1 (valeur par défaut si pas d'insertion), on la récupère depuis la bd */
    IF variance = -1 THEN
        SELECT idSubstance INTO variance FROM CodeSubstance WHERE idSubstance = N_idSubstance AND codeSubstance = N_denomSubstance;
    END IF;

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
            (SELECT idDesignationElemPharma FROM CodeSubstance WHERE codeSubstance = N_designationElem LIMIT 1),
            N_idSubstance,
            variance,
            (SELECT Dosage.idDosage FROM Dosage WHERE Dosage.idDosage = N_dosage),
            (SELECT idRefDosage FROM RefDosage WHERE labelRefDosage = N_refDosage LIMIT 1),
            N_natureComposant,
            N_numeroLisaison);

return RETURN_CODE;

END//


DELIMITER //

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
    IF (idSub IS NOT NULL) AND (variance IS NOT NULL) THEN
        UPDATE CIS_COMPO SET idCodeSubstance = idSub WHERE codeCIS = N_codeCIS;
        UPDATE CIS_COMPO SET varianceNomSubstance = variance WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO ErreursImportation (nomProcedure, messageErreur) VALUES ('updateCOMPO', 'je suis bien passé ici'); 
        SET variance = INSERT_CODE_SUBSTANCE(N_idSubstance, N_denomSubstance);
        SELECT idSubstance INTO idSub FROM CodeSubstance WHERE idSubstance = N_idSubstance AND codeSubstance = N_denomSubstance LIMIT 1;
        UPDATE CIS_COMPO SET varianceNomSubstance = variance WHERE codeCIS = N_codeCIS;
        UPDATE CIS_COMPO SET idCodeSubstance = idSub WHERE codeCIS = N_codeCIS;
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


DELIMITER //

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


DELIMITER //

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
