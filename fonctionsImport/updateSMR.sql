DELIMITER //

DROP FUNCTION IF EXISTS updateSMR//
CREATE FUNCTION updateSMR(
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
    DECLARE motif_id INT;
    DECLARE idNiveauS INT;

    /* Si l'id du libelle SMR existe on update le lien sinon on crée le libelle et on update le lien */
    SELECT idLibelleSmr INTO libelle_id FROM LibelleSmr WHERE libelleSMR = N_LibelleSMR LIMIT 1;    
    IF (libelle_id IS NOT NULL) THEN
        UPDATE CIS_HAS_SMR SET idLibelleSmr = libelle_id WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO LibelleSmr (libelleSMR) VALUES (N_LibelleSMR);
        SET libelle_id = LAST_INSERT_ID();
        UPDATE CIS_HAS_SMR SET idLibelleSmr = libelle_id WHERE codeCIS = N_codeCIS;
    END IF;

    /* Si le lien n'existe pas, on le crée sinon on l'update */
    IF (SELECT COUNT(*) FROM HAS_LiensPageCT WHERE codeHAS = N_codeHAS) = 0 THEN
        SELECT importCT(N_codeHAS, "") INTO RETURN_CODE;
        UPDATE CIS_HAS_SMR SET codeHAS = N_codeHAS WHERE codeCIS = N_codeCIS;
    ELSE
        UPDATE CIS_HAS_SMR SET codeHAS = N_codeHAS WHERE codeCIS = N_codeCIS;
    END IF;

    /* Si l'id du motif existe on update le lien sinon on crée le le motivEval et on update le lien */
    SELECT idMotifEval INTO motif_id FROM MotifEval WHERE libelleMotifEval = N_MotifEval LIMIT 1;
    IF (motif_id IS NOT NULL) THEN
        UPDATE CIS_HAS_SMR SET idMotifEval = motif_id WHERE codeCIS = N_codeCIS;
    ELSE    
        INSERT INTO MotifEval (libelleMotifEval) VALUES (N_MotifEval);
        SET motif_id = LAST_INSERT_ID();
        UPDATE CIS_HAS_SMR SET idMotifEval = motif_id WHERE codeCIS = N_codeCIS;
    END IF;

    /* Si l'id du niveau SMR existe on update le lien sinon on crée le libelle du niveauSMR et on update le lien */
    SELECT idNiveauSMR INTO idNiveauS FROM NiveauSMR WHERE libelleNiveauSMR = N_ValeurSMR;
    IF (idNiveauS IS NOT NULL) THEN
        UPDATE CIS_HAS_SMR SET niveauSMR = idNiveauS WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO NiveauSMR (libelleNiveauSMR) VALUES (N_ValeurSMR);
        SET idNiveauS = LAST_INSERT_ID();
        UPDATE CIS_HAS_SMR SET niveauSMR = idNiveauS WHERE codeCIS = N_codeCIS;
    END IF;

    /* On update la date de l'avis */
    UPDATE CIS_HAS_SMR SET dateAvis = N_DateAvis WHERE codeCIS = N_codeCIS;

return RETURN_CODE;

END//
