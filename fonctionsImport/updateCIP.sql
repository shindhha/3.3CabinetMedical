DELIMITER //

CREATE OR REPLACE FUNCTION updateCIP(
                            N_codeCIS INT(6),
                            N_codeCIP7 INT(7),
                            N_libellePresentation VARCHAR(100),
                            N_statutAdminiPresentation TEXT,
                            N_labelEtatCommercialisation VARCHAR(100),
                            N_dateCommercialisation DATE,
                            N_codeCIP13 BIGINT(13) UNSIGNED,
                            N_agrementCollectivite TEXT,
                            N_tauxRemboursement TEXT,
                            N_prix NUMERIC(6,2),
                            N_indicationRemboursement TEXT)
    RETURNS INT DETERMINISTIC
BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;

    DECLARE idLibellePres INT;
    DECLARE idEtatComm INT;
    DECLARE codeCISTaux INT;

    SELECT idLibellePresentation INTO idLibellePres FROM CIS_CIP_BDPM WHERE CodeCIS = N_codeCIS;
    /* Si idLibellePress non NULL alors update sinon crée le libéllé */
    IF (idLibellePres IS NOT NULL) THEN
        UPDATE LibellePresentation SET libellePresentation = N_libellePresentation WHERE idLibellePresentation = idLibellePres;
    ELSE
        INSERT INTO LibellePresentation (libellePresentation) VALUES (N_libellePresentation);
        SET idLibellePres = LAST_INSERT_ID();
        INSERT INTO CIS_CIP_BDPM (idLibellePresentation) VALUES (idLibellePres);
    END IF;

    /* Conversion du texte statutAdmini en boolean */
    IF N_statutAdminiPresentation = 'Présentation active' THEN
        SET @statutAdminiPresentation = 1;
    ELSE
        SET @statutAdminiPresentation = 0;
    END IF;

    /* Update statutAdminiPresentation */
    UPDATE CIS_CIP_BDPM SET statutAdminiPresentation = @statutAdminiPresentation WHERE codeCIS = N_codeCIS;

    SELECT idEtatCommercialisation INTO idEtatComm FROM CIS_CIP_BDPM WHERE CodeCIS = N_codeCIS;
    /* Si idEtatComm non NULL alors update sinon crée le EtatCommercialisation */
    IF (idEtatComm IS NOT NULL) THEN
        UPDATE EtatCommercialisation SET labelEtatCommercialisation = N_labelEtatCommercialisation WHERE idEtatCommercialisation = idEtatComm;
    ELSE
        INSERT INTO EtatCommercialisation (labelEtatCommercialisation) VALUES (N_labelEtatCommercialisation);
        SET idEtatComm = LAST_INSERT_ID();
        INSERT INTO CIS_CIP_BDPM (idEtatCommercialisation) VALUES (idEtatComm);
    END IF;

    /* Conversion du texte aggrément collectivités en booléen */
    if (N_agrementCollectivite = 'oui') THEN
        SET @agrementCollectivite = 1;
    ELSE
        SET @agrementCollectivite = 0;
    END IF;
    
    /* Update de agrementCollectivite */
    UPDATE CIS_CIP_BDPM SET agrementCollectivites = @agrementCollectivite WHERE codeCIS = N_codeCIS;

    /* update du taux de remboursement
       On vérifie si le taux n'est pas vide, retire le '%' et on l'insère */
    SELECT codeCIS into codeCISTaux FROM TauxRemboursement WHERE codeCIS = N_codeCIS;
    IF (N_tauxRemboursement != '' AND codeCISTaux IS NOT NULL ) THEN
        SET @tauxRemboursement = REPLACE(N_tauxRemboursement, '%', '');
        SET @tauxRemboursement = CAST(@tauxRemboursement AS DECIMAL(5,2));
        UPDATE TauxRemboursement SET tauxRemboursement = @tauxRemboursement WHERE codeCIS = N_codeCIS;
    END IF;

    /* UPDATE codeCIP13 */
    UPDATE CIS_CIP_BDPM SET codeCIP7 = N_codeCIP7 WHERE codeCIS = N_codeCIS;
    
    /* UPDATE codeCIP7 */
    UPDATE CIS_CIP_BDPM SET codeCIP13 = N_codeCIP13 WHERE codeCIS = N_codeCIS;
    
    /* UPDATE prix */
    UPDATE CIS_CIP_BDPM SET prix = N_prix WHERE codeCIS = N_codeCIS;

    /* UPDATE dateCommercialisation */
    UPDATE CIS_CIP_BDPM SET dateCommrcialisation = N_dateCommercialisation WHERE codeCIS = N_codeCIS;

    /* UPDATE indication Remboursement */
    UPDATE CIS_CIP_BDPM SET indicationRemboursement = N_indicationRemboursement;


return RETURN_CODE;

END//
