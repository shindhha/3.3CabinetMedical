DELIMITER //

CREATE OR REPLACE FUNCTION updateINFO(
                    N_codeCIS INT(11),
                    N_dateDebut DATE,
                    N_dateFin DATE,
                    N_texte TEXT)

    RETURNS INT DETERMINISTIC
BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;
    DECLARE idText INT;

    SELECT idTexte INTO idText FROM CIS_INFO WHERE codeCIS = N_codeCIS;
    /* Insertion du texte dans la table contenant tous les textes d'informations */

    IF (idText IS NOT NULL) THEN
        UPDATE Info_Texte SET labelTexte = N_texte WHERE idTexte = idText;
    ELSE
        INSERT INTO Info_Texte (labeltexte) VALUES (N_texte);
        SET idtex = LAST_INSERT_ID();
        UPDATE CIS_INFO SET idTexte = idtex WHERE codeCIS = N_codeCIS;
    END IF;

    UPDATE CIS_INFO SET dateDebutInformation = N_dateDebut WHERE codeCIS = N_codeCIS;

    UPDATE CIS_INFO SET DateFinInformation = N_dateFin WHERE codeCIS = N_codeCIS;

    return RETURN_CODE;

END//
