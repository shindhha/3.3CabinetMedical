DELIMITER //

CREATE OR REPLACE FUNCTION importSMR(
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





return RETURN_CODE;

END//
