DELIMITER //

CREATE OR REPLACE PROCEDURE procGENER(
                    N_idGroupeGener INT,
                    N_libellegroupeGener TEXT,
                    N_codeCis INT(11),
                    N_typeGener INT(1),
                    N_noTri INT(2)
                    )

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
    SELECT COUNT(*) INTO @nbCIS FROM CIS_GENER WHERE codeCIS = N_codeCIS;

    IF (@nbCIS > 0) THEN
        -- On fait une update
        SET procAppelee = 'procGENER - UPDATE';
        START TRANSACTION;
        SELECT updateGENER(
            N_idGroupeGener,
            N_libellegroupeGener,
            N_codeCis,
            N_typeGener,
            N_noTri
            );
        COMMIT;

    ELSE
        -- On fait une insertion
        SET procAppelee = 'procGENER - INSERT';
        START TRANSACTION;
        SELECT importGENER(
            N_idGroupeGener,
            N_libellegroupeGener,
            N_codeCis,
            N_typeGener,
            N_noTri
            );
        COMMIT;
    END IF;
end //