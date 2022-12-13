DELIMITER //

CREATE OR REPLACE PROCEDURE procCT(
                    N_codeHAS VARCHAR(8),
                    N_lienPage TEXT)

BEGIN

    DECLARE procAppelee TEXT;

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        GET DIAGNOSTICS CONDITION 1
            @errCode = RETURNED_SQLSTATE,
            @errMsg = MESSAGE_TEXT;

        INSERT INTO ErreursImportation (nomProcedure, messageErreur) VALUES (procAppelee, CONCAT(@errCode, ' - ', @errMsg));
    END;

    -- Vérification si le codeCIS existe dans la bdd ou non
    SELECT COUNT(*) INTO @nbCIS FROM HAS_LiensPageCT WHERE codeHAS = N_codeHAS;

    IF (@nbCIS > 0) THEN
        -- On fait une update
        SET procAppelee = 'procCT - UPDATE';
        START TRANSACTION;
        SELECT updateCT(
            N_codeHAS,
            N_lienPage
            );
        COMMIT;

    ELSE
        -- On fait une insertion
        SET procAppelee = 'procCT - INSERT';
        START TRANSACTION;
        SELECT importCT(
            N_codeHAS,
            N_lienPage
            );
        COMMIT;
    END IF;
end //