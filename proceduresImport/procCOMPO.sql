DELIMITER //

CREATE OR REPLACE PROCEDURE procCOMPO(
                    N_codeCIS INT(6),
                    N_designationElem TEXT,
                    N_idSubstance INT(6),
                    N_denomSubstance TEXT,
                    N_dosage TEXT,
                    N_refDosage TEXT,
                    N_natureComposant TEXT,
                    N_numeroLisaison INT(3))

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
    SELECT COUNT(*) INTO @nbCIS FROM CIS_COMPO WHERE codeCIS = N_codeCIS;

    IF (@nbCIS > 0) THEN
        -- On fait une update
        SET procAppelee = 'procCOMPO - UPDATE';
        START TRANSACTION;
        SELECT updateCOMPO(
            N_codeCIS,
            N_designationElem,
            N_idSubstance,
            N_denomSubstance,
            N_dosage,
            N_refDosage,
            N_natureComposant,
            N_numeroLisaison
            );
        COMMIT;

    ELSE
        -- On fait une insertion
        SET procAppelee = 'procCOMPO - INSERT';
        START TRANSACTION;
        SELECT importCOMPO(
            N_codeCIS,
            N_designationElem,
            N_idSubstance,
            N_denomSubstance,
            N_dosage,
            N_refDosage,
            N_natureComposant,
            N_numeroLisaison
            );
        COMMIT;
    END IF;
end //