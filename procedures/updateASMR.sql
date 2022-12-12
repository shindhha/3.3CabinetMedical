DELIMITER //

CREATE OR REPLACE FUNCTION updateASMR(
                            N_codeCIS INT(11),
                            N_codeHAS TEXT,
                            N_MotifEval TEXT,
                            N_DateAvis DATE,
                            N_ValeurASMR VARCHAR(25),
                            N_LibelleASMR TEXT)

    RETURNS INT DETERMINISTIC
BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;

    DECLARE idLibelleAs INT;
    DECLARE idMotifEv INT;

    SELECT idLibelleAsmr INTO idLibelleAs FROM CIS_HAS_ASMR WHERE codeCIS = N_codeCIS;
    /* Update du libelle si idLibelleAs non NULL sinon le crée */
    IF (idLibelleAs IS NOT NULL) THEN
        UPDATE LibelleAsmr SET libelleAsmr = N_LibelleASMR WHERE idLibelleAsmr = idLibelleAs;
    ELSE
        /* Insertion du libelle */
        INSERT INTO LibelleAsmr (libelleAsmr) VALUES (N_LibelleASMR);
        SET idLibelleAs = LAST_INSERT_ID();
        INSERT INTO CIS_HAS_ASMR (idLibelleAsmr) VALUES (idLibelleAs);
    END IF;

    SELECT idMotifEval INTO idMotifEv FROM CIS_HAS_ASMR WHERE codeCIS = N_codeCIS;
    /* Update du motif de l'évaluation dans ASMR si idMotifEval non NULL sinon le crée */
    IF (idMotifEv IS NOT NULL) THEN
        UPDATE MotifEval SET libelleMotifEval = N_MotifEval WHERE idMotifEval = idMotifEv;
    ELSE
        /* Insertion du motif de l'évaluation */
        INSERT INTO MotifEval (libelleMotifEval) VALUES (N_MotifEval);
        SET idMotifEv = LAST_INSERT_ID();
        INSERT INTO CIS_HAS_ASMR (idMotivEval) VALUES (idMotifEv);
    END IF;

    /* update de la date de l'avis de commission de la transparence */
    UPDATE CIS_HAS_ASMR SET dateAvis = N_DateAvis WHERE codeCIS = N_codeCIS;

    /* update de la valeur ASMR */
    UPDATE CIS_HAS_ASMR SET valeurASMR = N_ValeurASMR WHERE codeCIS = N_codeCIS;

    /* update  codeHAS */
    UPDATE CIS_HAS_ASMR SET codeHAS = N_codeHAS WHERE codeCIS = N_codeCIS;

return RETURN_CODE;

END//
