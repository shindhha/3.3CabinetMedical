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

    /* Si l'id du texte existe on update le lien sinon on crée le texte et on update le lien */
    SELECT idTexte INTO idText FROM Info_Texte WHERE labelTexte = N_texte;
    IF (idText IS NOT NULL) THEN
        UPDATE CIS_INFO SET idTexte = idText WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO Info_Texte (labeltexte) VALUES (N_texte);
        SET idText = LAST_INSERT_ID();
        UPDATE CIS_INFO SET idTexte = idText WHERE codeCIS = N_codeCIS;
    END IF;

    /* On update la date de debut de l'info */
    UPDATE CIS_INFO SET dateDebutInformation = N_dateDebut WHERE codeCIS = N_codeCIS;

    /* On update la date de fin de l'info */
    UPDATE CIS_INFO SET DateFinInformation = N_dateFin WHERE codeCIS = N_codeCIS;

    return RETURN_CODE;

END//
