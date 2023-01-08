DROP FUNCTION IF EXISTS updateCIP;
DELIMITER //

<<<<<<< HEAD
DROP FUNCTION IF EXISTS updateCIP//
CREATE FUNCTION updateCIP(
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
=======
CREATE FUNCTION updateCIP(
                        N_codeCIS INT(6),
                        N_codeCIP7 INT(7),
                        N_libellePresentation TEXT,
                        N_statutAdminiPresentation TEXT,
                        N_labelEtatCommercialisation TEXT,
                        N_dateCommercialisation DATE,
                        N_codeCIP13 BIGINT(13) UNSIGNED,
                        N_agrementCollectivite TEXT,
                        N_tauxRemboursement TEXT,
                        N_prix NUMERIC(6,2),
                        Unknown1 VARCHAR(250),
                        Unknown2 VARCHAR(250),
                        N_indicationRemboursement TEXT)
>>>>>>> 5fb3a739a9b31b87293b7f4bc05451222811cf35
    RETURNS INT DETERMINISTIC
BEGIN
    DECLARE RETURN_CODE INT DEFAULT 0;

    DECLARE idLibellePres INT;
    DECLARE idEtatComm INT;
    DECLARE codeCISTaux INT;

    /* Si l'id du libellePresentation existe on update le lien sinon on crée le libelle et on fait le lien */
    SELECT idLibellePresentation INTO idLibellePres FROM LibellePresentation WHERE libellePresentation = N_libellePresentation LIMIT 1;
    IF (idLibellePres IS NOT NULL) THEN
        UPDATE CIS_CIP_BDPM SET idLibellePresentation = idLibellePres WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO LibellePresentation (libellePresentation) VALUES (N_libellePresentation);
        SET idLibellePres = LAST_INSERT_ID();
        UPDATE CIS_CIP_BDPM SET idLibellePresentation = idLibellePres WHERE codeCIS = N_codeCIS;
    END IF;

    /* Conversion du texte statutAdmini en boolean */
    IF N_statutAdminiPresentation = 'Présentation active' THEN
        SET @statutAdminiPresentation = 1;
    ELSE
        SET @statutAdminiPresentation = 0;
    END IF;

    /* Update statutAdminiPresentation */
    UPDATE CIS_CIP_BDPM SET statutAdminiPresentation = @statutAdminiPresentation WHERE codeCIS = N_codeCIS;

    /* Si idEtatComm non NULL alors update sinon on crée l'EtatCommercialisation et on fait le lien */
    SELECT idEtatCommercialisation INTO idEtatComm FROM EtatCommercialisation WHERE labelEtatCommercialisation = N_labelEtatCommercialisation LIMIT 1;
    IF (idEtatComm IS NOT NULL) THEN
        UPDATE CIS_CIP_BDPM SET idEtatCommercialisation = idEtatComm WHERE codeCIS = N_codeCIS;    
    ELSE
        INSERT INTO EtatCommercialisation (labelEtatCommercialisation) VALUES (N_labelEtatCommercialisation);
        SET idEtatComm = LAST_INSERT_ID();
        UPDATE CIS_CIP_BDPM SET idEtatCommercialisation = idEtatComm WHERE codeCIS = N_codeCIS;
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
       On vérifie si le taux n'est pas vide, retire le '%' et on l'update */
    SELECT codeCIS into codeCISTaux FROM TauxRemboursement WHERE codeCIS = N_codeCIS LIMIT 1;
    IF (N_tauxRemboursement != '' AND codeCISTaux IS NOT NULL ) THEN
        SET @tauxRemboursement = REPLACE(N_tauxRemboursement, '%', '');
        SET @tauxRemboursement = CAST(@tauxRemboursement AS DECIMAL(5,2));
        UPDATE TauxRemboursement SET tauxRemboursement = @tauxRemboursement WHERE codeCIS = N_codeCIS;
    END IF;

    /* UPDATE codeCIP13 */
    UPDATE CIS_CIP_BDPM SET codeCIP7 = N_codeCIP7 WHERE codeCIS = N_codeCIS;
    
    /* UPDATE prix */
    UPDATE CIS_CIP_BDPM SET prix = N_prix WHERE codeCIS = N_codeCIS;

    /* UPDATE dateCommercialisation */
    UPDATE CIS_CIP_BDPM SET dateCommrcialisation = N_dateCommercialisation WHERE codeCIS = N_codeCIS;

    /* UPDATE indication Remboursement */
    UPDATE CIS_CIP_BDPM SET indicationRemboursement = N_indicationRemboursement;


return RETURN_CODE;

END//
