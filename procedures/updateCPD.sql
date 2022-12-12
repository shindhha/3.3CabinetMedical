DELIMITER //

CREATE OR REPLACE FUNCTION updateCPD(
                    N_codeCIS INT(11),
                    N_condition TEXT)

    RETURNS INT DETERMINISTIC
BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;

    DECLARE idCond INT;
    
    SELECT idCondition INTO idCond FROM LabelCondition WHERE labelCondition = N_condition;

    IF (idCond IS NOT NULL) THEN
        UPDATE CIS_CPD SET idCondition = idCond WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO LabelCondition (labelcondition) VALUES (N_condition);
        SET idCond = LAST_INSERT_ID();
        UPDATE CIS_CPD SET idCondition = idCond WHERE codeCIS = N_codeCIS;
    END IF;

return RETURN_CODE;

END//
