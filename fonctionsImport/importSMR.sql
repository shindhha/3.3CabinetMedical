DELIMITER //

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
