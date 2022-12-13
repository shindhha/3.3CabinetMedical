DELIMITER //

CREATE OR REPLACE FUNCTION updateBDPM(
                    N_codeCIS INT(6),
                    N_desElemPharma VARCHAR(100),   -- V
                    N_formePharma VARCHAR(100),     -- V
                    N_voieAdministration TEXT,      -- V
                    N_statutAdAMM VARCHAR(25),      -- V
                    N_typeProc VARCHAR(255),        -- V
                    N_etatCommercialisation TEXT,   -- V
                    N_dateAAM DATE,                 -- V
                    N_statutBDM TEXT,               -- V
                    N_autoEur VARCHAR(255),         -- V
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
    WHILE @i <= @voieAdm DO
        SET @voie = SPLIT_EXPLODE(N_voieAdministration, ';', @i);
        SELECT idVoieAdministration INTO idVoieA FROM ID_Label_VoieAdministration WHERE labelVoieAdministration = @voie LIMIT 1;
        -- On update le lien avec le label de la voie d'administration si idVoieA existe sinon on le crée et on fait le lien
        IF (idVoieA IS NOT NULL) THEN
            UPDATE CIS_VoieAdministration SET idVoieAdministration = idVoieA WHERE codeCIS = N_codeCIS;
        ELSE
            -- On enregistre le nom de la voie dans la BDD et on fait le lien
            INSERT INTO ID_Label_VoieAdministration (labelVoieAdministration) VALUES (@voie);
            SELECT idVoieAdministration INTO idVoieA FROM ID_Label_VoieAdministration WHERE labelVoieAdministration = @voie LIMIT 1;
            UPDATE CIS_VoieAdministration SET idVoieAdministration = idVoieA WHERE codeCIS = N_codeCIS;
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