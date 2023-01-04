DROP FUNCTION IF EXISTS updateGENER;
DELIMITER //

CREATE FUNCTION updateGENER(
                    N_idGroupeGener INT,
                    N_libellegroupeGener TEXT,
                    N_codeCis INT(11),
                    N_typeGener INT(1),
                    N_noTri INT(2)
                    )
    RETURNS INT DETERMINISTIC

BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;
    DECLARE grGener INT;

    /* Si l'id du groupeGener existe alors on update le lien sinon on le crée et on fait le lien */
    SELECT idGroupeGener INTO grGener FROM GroupeGener WHERE idGroupeGener = N_idGroupeGener LIMIT 1;
    IF (grGener IS NOT NULL) THEN 
        UPDATE GroupeGener SET labelGroupeGener = N_libellegroupeGener WHERE idGroupeGener = N_idGroupeGener;
    END IF;

    /* On update le type générique */
    UPDATE CIS_GENER SET typeGenerique = N_typeGener WHERE codeCIS = N_codeCIS;

    /* On update le numero de tri */
    UPDATE CIS_GENER SET numeroTri = N_noTri WHERE codeCIS = N_codeCIS;

return RETURN_CODE;

END//
