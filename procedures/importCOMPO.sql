DELIMITER //

CREATE OR REPLACE FUNCTION importCOMPO(
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
