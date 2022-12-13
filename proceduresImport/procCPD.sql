DELIMITER //

CREATE OR REPLACE PROCEDURE procCPD(
                    N_codeCIS INT(11),
                    N_condition TEXT)

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
    SELECT COUNT(*) INTO @nbCIS FROM CIS_CPD WHERE codeCIS = N_codeCIS;

    IF (@nbCIS > 0) THEN
        -- On fait une update
        SET procAppelee = 'procCPD - UPDATE';
        START TRANSACTION;
        SELECT updateCPD(
            N_codeCIS,
            N_condition
            );
        COMMIT;

    ELSE
        -- On fait une insertion
        SET procAppelee = 'procCPD - INSERT';
        START TRANSACTION;
        SELECT importCPD(
            N_codeCIS,
            N_condition
            );
        COMMIT;
    END IF;
end //