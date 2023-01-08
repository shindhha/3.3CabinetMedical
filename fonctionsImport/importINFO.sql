DELIMITER //

DROP FUNCTION IF EXISTS importINFO//
CREATE FUNCTION importINFO(
                    N_codeCIS INT(11),
                    N_dateDebut DATE,
                    N_dateFin DATE,
                    N_texte TEXT)

    RETURNS INT DETERMINISTIC
BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;

    /* Insertion du texte dans la table contenant tous les textes d'informations */
    IF (SELECT COUNT(*) FROM Info_Texte WHERE labelTexte = N_texte) = 0 THEN
        INSERT INTO Info_Texte (labeltexte) VALUES (N_texte);
    END IF;


    /* Insertion dans CIS_INFO */
    INSERT INTO CIS_INFO (codeCIS,
                          dateDebutInformation,
                          DateFinInformation,
                          idTexte)
    VALUES (N_codeCIS,
            N_dateDebut,
            N_dateFin,
            (SELECT idTexte FROM Info_Texte WHERE labelTexte = N_texte));

return RETURN_CODE;

END//
