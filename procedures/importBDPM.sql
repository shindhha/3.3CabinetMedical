DELIMITER //

CREATE OR REPLACE FUNCTION importBDPM(
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