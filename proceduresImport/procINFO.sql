DELIMITER //

CREATE OR REPLACE PROCEDURE procINFO(
                    N_codeCIS INT(11),
                    N_dateDebut DATE,
                    N_dateFin DATE,
                    N_texte TEXT)

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
    SELECT COUNT(*) INTO @nbCIS FROM CIS_INFO WHERE codeCIS = N_codeCIS;

    IF (@nbCIS > 0) THEN
        -- On fait une update
        SET procAppelee = 'procINFO - UPDATE';
        START TRANSACTION;
        SELECT updateINFO(
            N_codeCIS,
            N_dateDebut,
            N_dateFin,
            N_texte
            );
        COMMIT;

    ELSE
        -- On fait une insertion
        SET procAppelee = 'procINFO - INSERT';
        START TRANSACTION;
        SELECT importINFO(
            N_codeCIS,
            N_dateDebut,
            N_dateFin,
            N_texte
            );
        COMMIT;
    END IF;
end //