DELIMITER //

CREATE OR REPLACE PROCEDURE procBDPM(
                    N_codeCIS INT(6),
                    N_desElemPharma TEXT,   -- V
                    N_formePharma TEXT,     -- V
                    N_voieAdministration TEXT,      -- V
                    N_statutAdAMM VARCHAR(25),      -- V
                    N_typeProc TEXT,        -- V
                    N_etatCommercialisation TEXT,   -- V
                    N_dateAAM DATE,                 -- V
                    N_statutBDM TEXT,               -- V
                    N_autoEur TEXT,         -- V
                    N_titulaires TEXT,              -- V
                    N_surveillanceRenforcee TEXT)   -- V

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
    SELECT COUNT(*) INTO @nbCIS FROM CIS_BDPM WHERE codeCIS = N_codeCIS;

    IF (@nbCIS > 0) THEN
        -- On fait une update
        SET procAppelee = 'procBDPM - UPDATE';
        START TRANSACTION;
        SELECT updateBDPM(
            N_codeCIS,
            N_desElemPharma,
            N_formePharma,
            N_voieAdministration,
            N_statutAdAMM,
            N_typeProc,
            N_etatCommercialisation,
            N_dateAAM,
            N_statutBDM,
            N_autoEur,
            N_titulaires,
            N_surveillanceRenforcee);
        COMMIT;

    ELSE
        -- On fait une insertion
        SET procAppelee = 'procBDPM - INSERT';
        START TRANSACTION;
        SELECT importBDPM(
            N_codeCIS,
            N_desElemPharma,
            N_formePharma,
            N_voieAdministration,
            N_statutAdAMM,
            N_typeProc,
            N_etatCommercialisation,
            N_dateAAM,
            N_statutBDM,
            N_autoEur,
            N_titulaires,
            N_surveillanceRenforcee);
        COMMIT;
    END IF;
end //