DELIMITER //

CREATE OR REPLACE PROCEDURE procASMR(
                    N_codeCIS INT(6),
                    N_codeHAS TEXT,
                    N_MotifEval TEXT,
                    N_DateAvis DATE,
                    N_ValeurASMR VARCHAR(25),
                    N_LibelleASMR TEXT)

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
    SELECT COUNT(*) INTO @nbCIS FROM CIS_HAS_ASMR WHERE codeCIS = N_codeCIS AND codeHAS = N_codeHAS;

    IF (@nbCIS > 0) THEN
        -- On fait une update
        SET procAppelee = 'procASMR - UPDATE';
        START TRANSACTION;
        SELECT updateASMR(
            N_codeCIS,
            N_codeHAS,
            N_MotifEval,
            N_DateAvis,
            N_ValeurASMR,
            N_LibelleASMR
            );
        COMMIT;

    ELSE
        -- On fait une insertion
        SET procAppelee = 'procASMR - INSERT';
        START TRANSACTION;
        SELECT importASMR(
            N_codeCIS,
            N_codeHAS,
            N_MotifEval,
            N_DateAvis,
            N_ValeurASMR,
            N_LibelleASMR
            );
        COMMIT;
    END IF;
end //