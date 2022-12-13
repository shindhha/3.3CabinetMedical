DELIMITER //

CREATE OR REPLACE PROCEDURE procCIP(
                    N_codeCIS INT(6),
                    N_codeCIP7 INT(7),
                    N_libellePresentation TEXT,
                    N_statutAdminiPresentation TEXT,
                    N_labelEtatCommercialisation TEXT,
                    N_dateCommercialisation DATE,
                    N_codeCIP13 BIGINT(13) UNSIGNED, -- Bypass de la limite de 2.147 Md pour un int normal
                    N_agrementCollectivite TEXT,
                    N_tauxRemboursement TEXT,
                    N_prix NUMERIC(6,2),
                    N_indicationRemboursement TEXT)

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
    SELECT COUNT(*) INTO @nbCIS FROM CIS_CIP_BDPM WHERE codeCIP13 = N_codeCIP13;

    IF (@nbCIS > 0) THEN
        -- On fait une update
        SET procAppelee = 'procCIP - UPDATE';
        START TRANSACTION;
        SELECT updateCIP(
            N_codeCIS,
            N_codeCIP7,
            N_libellePresentation,
            N_statutAdminiPresentation,
            N_labelEtatCommercialisation,
            N_dateCommercialisation,
            N_codeCIP13,
            N_agrementCollectivite,
            N_tauxRemboursement,
            N_prix,
            N_indicationRemboursement
            );
        COMMIT;

    ELSE
        -- On fait une insertion
        SET procAppelee = 'procCIP - INSERT';
        START TRANSACTION;
        SELECT importCIP(
            N_codeCIS,
            N_codeCIP7,
            N_libellePresentation,
            N_statutAdminiPresentation,
            N_labelEtatCommercialisation,
            N_dateCommercialisation,
            N_codeCIP13,
            N_agrementCollectivite,
            N_tauxRemboursement,
            N_prix,
            N_indicationRemboursement
            );
        COMMIT;
    END IF;
end //