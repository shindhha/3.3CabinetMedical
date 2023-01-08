DELIMITER //

DROP FUNCTION IF EXISTS importASMR//
CREATE FUNCTION importASMR(
                    N_codeCIS INT(6),
                    N_codeHAS TEXT,
                    N_MotifEval TEXT,
                    N_DateAvis DATE,
                    N_ValeurASMR VARCHAR(25),
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
