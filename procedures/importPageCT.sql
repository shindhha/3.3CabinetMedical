DELIMITER //

CREATE OR REPLACE FUNCTION importCT(
                    N_codeHAS VARCHAR(8),
                    N_lienPage TEXT) RETURNS INT
BEGIN
    -- INFO : il faut inpérativement exécuter l'import des liens avant l'import
    --        de SMR et ASMR pour que les liens ne soient pas vides.
    DECLARE RETURN_CODE INT DEFAULT 0;

    INSERT INTO HAS_LiensPageCT (codeHAS, lienPage) VALUES (N_codeHAS, N_lienPage);

    RETURN RETURN_CODE;

end //