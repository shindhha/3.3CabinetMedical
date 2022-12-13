DELIMITER //

CREATE OR REPLACE FUNCTION updateGENER(
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

    SELECT idGroupeGener INTO grGener FROM GroupeGener WHERE idGroupeGener = N_idGroupeGener;

    IF (grGener IS NOT NULL) THEN 
        UPDATE GroupeGener SET labelGroupeGener = N_libellegroupeGener WHERE idGroupeGener = N_idGroupeGener;
    ELSE
        INSERT INTO GroupeGener (idGroupeGener, labelGroupeGener) VALUES (N_idGroupeGener, N_libellegroupeGener);
        UPDATE CIS_GENER SET idGroupeGener = N_idGroupeGener WHERE codeCIS = N_codeCIS;
    END IF;

    UPDATE CIS_GENER SET typeGenerique = N_typeGener WHERE codeCIS = N_codeCIS;

    UPDATE CIS_GENER SET numeroTri = N_noTri WHERE codeCIS = N_codeCIS;

return RETURN_CODE;

END//
