DELIMITER //

DROP FUNCTION IF EXISTS updateCPD//
CREATE FUNCTION updateCPD(
                    N_codeCIS INT(11),
                    N_condition TEXT)

    RETURNS INT DETERMINISTIC
BEGIN

    DECLARE RETURN_CODE INT DEFAULT 0;
    DECLARE idCond INT;
    
    /* Si idCondition existe on update le lien sinon on le crée et on fait le lien */
    SELECT idCondition INTO idCond FROM LabelCondition WHERE labelCondition = N_condition LIMIT 1;
    IF (idCond IS NOT NULL) THEN
        UPDATE CIS_CPD SET idCondition = idCond WHERE codeCIS = N_codeCIS;
    ELSE
        INSERT INTO LabelCondition (labelcondition) VALUES (N_condition);
        SET idCond = LAST_INSERT_ID();
        UPDATE CIS_CPD SET idCondition = idCond WHERE codeCIS = N_codeCIS;
    END IF;

return RETURN_CODE;

END//
