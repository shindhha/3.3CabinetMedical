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

    /* Si idDes existe Update le nom du médicament sinon crée le nom du médicament du médicament */
    SELECT idDesignation INTO idDes FROM CIS_BDPM WHERE CodeCIS = N_codeCIS;

    IF (idDes IS NOT NULL) THEN
        UPDATE DesignationElemPharma Set designation = N_desElemPharma where idDesignation = idDes;
    ELSE
        INSERT INTO DesignationElemPharma (designation) VALUES (N_desElemPharma);
        SET idDes = LAST_INSERT_ID();
        INSERT INTO CIS_BDPM (idDesignation) VALUES (idDes);
    END IF;

    /* Si idFormPharm existe Update la forme pharmaceutique du médicament sinon crée la forme pharmaceutique du médicament */
    SELECT idFormePharma INTO idFormPharm FROM CIS_BDPM WHERE CodeCIS = N_codeCIS;

    IF (idFormPharm IS NOT NULL) THEN
        UPDATE FormePharma Set formePharma = N_formePharma where idFormePharma = idFormPharm ;
    ELSE
        INSERT INTO FormePharma (formePharma) VALUES (N_formePharma);
        SET idFormPharm = LAST_INSERT_ID();
        INSERT INTO CIS_BDPM (idFormePharma) VALUES (idFormPharm);
    END IF;

    /* 
     * Si idStatAdAMM existe Update le statutAdAMM du médicament 
     * sinon crée le statutAdAMM du médicament 
     */
    SELECT idStatutAdAMM INTO idStatAdAMM FROM CIS_BDPM WHERE CodeCIS = N_codeCIS;

     IF (idStatAdAMM IS NOT NULL) THEN
        UPDATE StatutAdAMM Set statutAdAMM = N_statutAdAMM where idStatutAdAMM = idStatAdAMM ;
    ELSE
        INSERT INTO StatutAdAMM (statutAdAMM) VALUES (N_statutAdAMM);
        SET idStatAdAMM = LAST_INSERT_ID();
        INSERT INTO CIS_BDPM (idStatutAdAMM) VALUES (idStatAdAMM);
    END IF;

    /* 
     * Si idType existe Update le type de procédure du médicament 
     * sinon crée le type de procédure du médicament 
     */
    SELECT idTypeProc INTO idType FROM CIS_BDPM WHERE CodeCIS = N_codeCIS;

    IF (idType IS NOT NULL) THEN
        UPDATE TypeProc Set typeProc = N_typeProc where idTypeProc = idType ;
    ELSE
        INSERT INTO TypeProc (typeProc) VALUES (N_typeProc);
        SET idType = LAST_INSERT_ID();
        INSERT INTO CIS_BDPM (idTypeProc) VALUES (idType);
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

    /* 
     * Si idAuto existe Update AutorEuro
     * sinon crée AutorEuro
     */
    SELECT idAutoEur INTO idAuto FROM CIS_BDPM WHERE CodeCIS = N_codeCIS;

    IF (idAuto IS NOT NULL) THEN
        UPDATE AutorEurop Set autoEur = N_autoEur where idAutoEur = idType;
    ELSE
        INSERT INTO AutorEurop (autoEur) VALUES (N_autoEur);
        SET idAuto = LAST_INSERT_ID();
        INSERT INTO CIS_BDPM (idAutoEur) VALUES (idAuto);
    END IF;

    /* Update de l'etat commercial */
    UPDATE CIS_BDPM SET etatCommercialisation = @etatCommercialisation where codeCIS = N_codeCIS ;
    
    /* Update de la dateAMM */
    UPDATE CIS_BDPM SET dateAMM = N_dateAAM where codeCIS = N_codeCIS ;
    
    /* Update du statut BDM */
    UPDATE CIS_BDPM SET statutBDM = @statutBDM where codeCIS = N_codeCIS ;
    
    /* Update de surveillance renforcée */
    UPDATE CIS_BDPM SET surveillanceRenforcee = @surveillanceRenforcee where codeCIS = N_codeCIS ;

    SELECT idVoieAdministration INTO idVoieA FROM CIS_VoieAdministration WHERE CodeCIS = N_codeCIS;
    /* Créations des voies d'administrations du médicament dans la BDD
       Splitté par le caractère ";" */
    SET @voieAdm = NB_OCCURENCES(N_voieAdministration, ';') + 1;
    SET @i = 1;
    WHILE @i <= @voieAdm DO
        SET @voie = SPLIT_EXPLODE(N_voieAdministration, ';', @i);
        
        IF (idVoieA IS NOT NULL) THEN
            -- On update le label de la voie d'administration
            UPDATE ID_Label_VoieAdministration Set labelVoieAdministration = @voie where idVoieAdministration = idVoieA;
        ELSE
            -- On enregistre le nom de la voie dans la BDD
            INSERT INTO ID_Label_VoieAdministration (labelVoieAdministration) VALUES (@voie);
            -- On définit le medicament comme étant administré par cette voie
            INSERT INTO CIS_VoieAdministration (codeCIS, idVoieAdministration) VALUES (N_codeCIS, (SELECT idVoieAdministration FROM ID_Label_VoieAdministration WHERE labelVoieAdministration = @voie));
        END IF;

        SET @i = @i + 1;
    END WHILE;

    SELECT idTitulaire INTO idTit FROM CIS_Titulaires WHERE CodeCIS = N_codeCIS;
    /* Insertion dans BDD de titulaires, séparé dans de très rares cas par ';' */
    SET @i = 1;
    SET @nb_titulaires = NB_OCCURENCES(N_titulaires, ';') + 1;
    WHILE @i <= @nb_titulaires DO
        -- On enregistre le nom du titulaire dans la BDD
        SET @titulaire = SPLIT_EXPLODE(N_titulaires, ';', @i);

        IF (idTit IS NOT NULL) THEN
            -- On update le label de la voie d'administration
            UPDATE ID_Label_Titulaire Set labelTitulaire = @titulaire where idTitulaire = idTit;
        ELSE
            -- On enregistre le nom du titulaire dans la BDD
            INSERT INTO ID_Label_Titulaire (labelTitulaire) VALUES (@titulaire);
            -- On définit le medicament comme étant administré par cette voie
            INSERT INTO CIS_Titulaires (codeCIS, idTitulaire) VALUES (N_codeCIS, (SELECT idTitulaire FROM ID_Label_Titulaire WHERE labelTitulaire = @titulaire));
        END IF;
    
        SET @i = @i + 1;
    END WHILE;

    RETURN return_code;

end //