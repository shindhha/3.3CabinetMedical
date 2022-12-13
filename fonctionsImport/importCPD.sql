DELIMITER //

CREATE OR REPLACE FUNCTION importCPD(
                    N_codeCIS INT(11),
                    N_condition TEXT)

    RETURNS INT DETERMINISTIC
BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;

    /* Insertion de la condition dans la DB */
    IF (SELECT COUNT(*) FROM LabelCondition WHERE labelCondition = N_condition) = 0 THEN
        INSERT INTO LabelCondition (labelcondition) VALUES (N_condition);
    END IF;

    /* Insertion dans CIS_CPD */
    INSERT INTO CIS_CPD (codeCIS, idCondition) VALUES (N_codeCIS, (SELECT idCondition FROM LabelCondition WHERE labelCondition = N_condition));

return RETURN_CODE;

END//
