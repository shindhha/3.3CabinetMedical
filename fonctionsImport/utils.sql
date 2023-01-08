DELIMITER //
DROP FUNCTION IF EXISTS NB_OCCURENCES//

/* Fonction qui permet d'obtenir le nombre d'occurences d'un caractère dnas une chaine de caractères*/
CREATE FUNCTION NB_OCCURENCES(chaine TEXT, caractere TEXT) RETURNS INTEGER DETERMINISTIC
BEGIN
    RETURN LENGTH(chaine) - LENGTH(REPLACE(chaine, caractere, ''));
end //


/* Fonction qui permet d'obtenir à partir d'une chaine de plusieurs valeurs séparées par un délimiteur d'obtenir l'item de
   rang N (démarre à 1, renvoie NULL si position mauvaise)*/
DROP FUNCTION IF EXISTS SPLIT_EXPLODE//
CREATE FUNCTION SPLIT_EXPLODE(texte TEXT, delimiteur TEXT, position INT) RETURNS TEXT DETERMINISTIC
BEGIN
    DECLARE nb_cases INT;
    DECLARE resultat TEXT;

    SET nb_cases = NB_OCCURENCES(texte, delimiteur) + 1; -- Nombre de 'cases' dans le texte. Ici on veut la case position
    IF position > nb_cases OR position <= 0 THEN
        RETURN NULL;
    ELSE
        SET resultat = SUBSTRING_INDEX(SUBSTRING_INDEX(texte, delimiteur, position), delimiteur, -1);
        RETURN resultat;
    END IF;
end //


/*
 * Fonction qui permet l'insertion dans la table CodeSubstance
 * Utilisation d'une procédure pour pouvoir avoir une PK composite et un auto increment
 * Renvoie varianceNom (voir schéma table CodeSubstance
 *
 * (NB : Utilisation d'une variance pour avoir plusieurs nom pour une même substance)
 */
DROP FUNCTION IF EXISTS INSERT_CODE_SUBSTANCE//
CREATE FUNCTION INSERT_CODE_SUBSTANCE(N_idSubstance TEXT, N_libelle TEXT) RETURNS INT DETERMINISTIC
BEGIN
    DECLARE currentVariance INT;

    /* Sélection de la variance actuelle */
    SET currentVariance = (SELECT MAX(varianceNom) FROM CodeSubstance WHERE idSubstance = N_idSubstance);
    IF currentVariance IS NULL THEN
        SET currentVariance = -1;
    END IF;

    SET currentVariance = currentVariance + 1;

    /* Insertion de la nouvelle valeur */
    INSERT INTO CodeSubstance (idSubstance, varianceNom, codeSubstance) VALUES (N_idSubstance, currentVariance, N_libelle);

    RETURN currentVariance;
end //
