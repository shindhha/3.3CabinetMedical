DELIMITER //

CREATE OR REPLACE FUNCTION importGENER(
                    N_idGroupeGener INT,
                    N_libellegroupeGener TEXT,
                    N_codeCis INT(11),
                    N_typeGener INT(1),
                    N_noTri INT(2)
                    )
    RETURNS INT DETERMINISTIC

BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;

    /* Insertion du label */
    IF (SELECT COUNT(*) FROM GroupeGener WHERE idGroupeGener = N_idGroupeGener) = 0 THEN
        INSERT INTO GroupeGener (idGroupeGener, labelGroupeGener) VALUES (N_idGroupeGener, N_libellegroupeGener);
    END IF;

    /* Insertion dans cis_gener */
    INSERT INTO CIS_GENER (codeCIS, idGroupeGener, typeGenerique, numeroTri) VALUES (N_codeCis, N_idGroupeGener, N_typeGener, N_noTri);

return RETURN_CODE;

END//
