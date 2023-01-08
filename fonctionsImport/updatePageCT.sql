DELIMITER //

DROP FUNCTION IF EXISTS updateCT//
CREATE FUNCTION updateCT(
                    N_codeHAS VARCHAR(8),
                    N_lienPage TEXT) RETURNS INT DETERMINISTIC
BEGIN
    -- INFO : il faut inpérativement exécuter l'update des liens avant l'update
    --        de SMR et ASMR pour que les liens ne soient pas vides.
    DECLARE RETURN_CODE INT DEFAULT 0;

    /* Si le lien n'existe pas, on le crée sinon on l'update  */
    IF (SELECT COUNT(*) FROM HAS_LiensPageCT WHERE codeHAS = N_codeHAS) = 0 THEN
        SELECT importCT(N_codeHAS, N_lienPage) INTO RETURN_CODE;
    ELSE
        UPDATE HAS_LiensPageCT SET lienPage = N_lienPage WHERE codeHAS = N_codeHAS;
    END IF;

    RETURN RETURN_CODE;

end //