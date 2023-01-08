DELIMITER //

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
