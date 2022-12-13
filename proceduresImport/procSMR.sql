DELIMITER //

CREATE OR REPLACE PROCEDURE procSMR(
                    N_codeCIS INT(11),
                    N_codeHAS TEXT,
                    N_MotifEval TEXT,
                    N_DateAvis DATE,
                    N_ValeurSMR TEXT,
                    N_LibelleSMR TEXT)

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
    SELECT COUNT(*) INTO @nbCIS FROM CIS_HAS_SMR WHERE codeCIS = N_codeCIS;

    IF (@nbCIS > 0) THEN
        -- On fait une update
        SET procAppelee = 'procSMR - UPDATE';
        START TRANSACTION;
        SELECT updateSMR(
            N_codeCIS,
            N_codeHAS,
            N_MotifEval,
            N_DateAvis,
            N_ValeurSMR,
            N_LibelleSMR
            );
        COMMIT;

    ELSE
        -- On fait une insertion
        SET procAppelee = 'procSMR - INSERT';
        START TRANSACTION;
        SELECT importSMR(
            N_codeCIS,
            N_codeHAS,
            N_MotifEval,
            N_DateAvis,
            N_ValeurSMR,
            N_LibelleSMR
            );
        COMMIT;
    END IF;
end //