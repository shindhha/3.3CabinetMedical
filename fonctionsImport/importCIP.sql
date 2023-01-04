DROP FUNCTION IF EXISTS importCIP;
DELIMITER //

CREATE FUNCTION importCIP(
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
                    Unknown1 VARCHAR(250),
                    Unknown2 VARCHAR(250),
                    N_indicationRemboursement TEXT)
    RETURNS INT DETERMINISTIC
BEGIN
    DECLARE RETURN_CODE INT DEFAULT 0;

    /* Creation du libellé dans la bdd */
    IF (SELECT COUNT(*) FROM LibellePresentation WHERE libellePresentation = N_libellePresentation) = 0 THEN
        INSERT INTO LibellePresentation (libellePresentation) VALUES (N_libellePresentation);
    END IF;

    /* Conversion du texte statutAdmini en boolean */
    IF N_statutAdminiPresentation = 'Présentation active' THEN
        SET @statutAdminiPresentation = 1;
    ELSE
        SET @statutAdminiPresentation = 0;
    END IF;

    /* Creation du texte etat commercialisation dans la BDD */
    IF (SELECT COUNT(*) FROM EtatCommercialisation WHERE labelEtatCommercialisation = N_labelEtatCommercialisation) = 0 THEN
        INSERT INTO EtatCommercialisation (labelEtatCommercialisation) VALUES (N_labelEtatCommercialisation);
    END IF;

    /* Conversion du texte aggrément collectivités en booléen */
    if (N_agrementCollectivite = 'oui') THEN
        SET @agrementCollectivite = 1;
    ELSE
        SET @agrementCollectivite = 0;
    END IF;


    /* Insertion du taux de remboursement
       On vérifie si le taux n'est pas vide, retire le '%' et on l'insère */
    IF N_tauxRemboursement != '' AND (SELECT COUNT(*) FROM TauxRemboursement WHERE codeCIS = N_codeCIS) = 0 THEN
        SET @tauxRemboursement = REPLACE(N_tauxRemboursement, '%', '');
        SET @tauxRemboursement = CAST(@tauxRemboursement AS DECIMAL(5,2));
        INSERT INTO TauxRemboursement (codeCIS, tauxRemboursement) VALUE (N_codeCIS, @tauxRemboursement);
    END IF;


    /* Insertion du CIP */
    INSERT INTO CIS_CIP_BDPM (
                              codeCIS,
                              codeCIP7,
                              idLibellePresentation,
                              statutAdminiPresentation,
                              idEtatCommercialisation,
                              dateCommrcialisation,
                              codeCIP13,
                              agrementCollectivites,
                              prix,
                              indicationRemboursement
                              )
        VALUES (
                N_codeCIS,
                N_codeCIP7,
                (SELECT LibellePresentation.idLibellePresentation FROM LibellePresentation WHERE libellePresentation = N_libellePresentation),
                @statutAdminiPresentation,
                (SELECT EtatCommercialisation.idEtatCommercialisation FROM EtatCommercialisation WHERE labelEtatCommercialisation = N_labelEtatCommercialisation),
                N_dateCommercialisation,
                N_codeCIP13,
                @agrementCollectivite,
                N_prix,
                N_indicationRemboursement
                );

return RETURN_CODE;

END//
