DELIMITER //

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
