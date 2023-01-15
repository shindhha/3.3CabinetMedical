/* -------------------------------------------- Creation de la table des designations (DesignationElemPharma) -------------------------------------------- */

CREATE TABLE DesignationElemPharma (
    idDesignation INT(3) AUTO_INCREMENT PRIMARY KEY,
    designation TEXT
);

/* -------------------------------------------- Creation de la table des formes pharmaceutiques (FormePharma) -------------------------------------------- */

CREATE TABLE FormePharma (
    idFormePharma INT(3),
    formePharma TEXT
);
ALTER TABLE FormePharma ADD CONSTRAINT PK_FormePharma PRIMARY KEY (idFormePharma);
ALTER TABLE FormePharma MODIFY idFormePharma INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table des statuts d'AMM (StatutAdAMM) -------------------------------------------- */

CREATE TABLE StatutAdAMM (
    idStatutAdAMM INT(3),
    statutAdAMM VARCHAR(25)
);
ALTER TABLE StatutAdAMM ADD CONSTRAINT PK_StatutAdAMM PRIMARY KEY (idStatutAdAMM);
ALTER TABLE StatutAdAMM MODIFY idStatutAdAMM INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table des types de procédure (TypeProc) -------------------------------------------- */

CREATE TABLE TypeProc (
    idTypeProc INT(3),
    typeProc TEXT
);
ALTER TABLE TypeProc ADD CONSTRAINT PK_TypeProc PRIMARY KEY (idTypeProc);
ALTER TABLE TypeProc MODIFY idTypeProc INT(3) AUTO_INCREMENT;


/* -------------------------------------------- Creation de la table des autorisations européennes (AutorEurop)  -------------------------------------------- */

CREATE TABLE AutorEurop (
    idAutoEur INT(3),
    autoEur TEXT
);
ALTER TABLE AutorEurop ADD CONSTRAINT PK_AutorEurop PRIMARY KEY (idAutoEur);
ALTER TABLE AutorEurop MODIFY idAutoEur INT(3) AUTO_INCREMENT;



/* -------------------------------------------- Creation de la table principale des medicaments (CIS_BDPM) -------------------------------------------- */

CREATE TABLE CIS_BDPM (
    codeCIS INT(6),
    idDesignation INT(3),
    idFormePharma INT(3),
    idStatutAdAMM INT(3),
    idTypeProc INT(3),
    etatCommercialisation BOOL,
    dateAMM DATE,
    statutBdm BOOL,
    idAutoEur INT(3),
    surveillanceRenforcee BOOL
);
ALTER TABLE CIS_BDPM ADD CONSTRAINT PK_CIS_BDPM PRIMARY KEY (codeCIS);
ALTER TABLE CIS_BDPM ADD CONSTRAINT FK_CIS_BDPM_DesignationElemPharma FOREIGN KEY (idDesignation) REFERENCES DesignationElemPharma(idDesignation);
ALTER TABLE CIS_BDPM ADD CONSTRAINT FK_CIS_BDPM_AutorEurop FOREIGN KEY (idAutoEur) REFERENCES AutorEurop(idAutoEur);
ALTER TABLE CIS_BDPM ADD CONSTRAINT FK_CIS_BDPM_FormePharma FOREIGN KEY (idFormePharma) REFERENCES FormePharma(idFormePharma);
ALTER TABLE CIS_BDPM ADD CONSTRAINT FK_CIS_BDPM_StatutAdAMM FOREIGN KEY (idStatutAdAMM) REFERENCES StatutAdAMM(idStatutAdAMM);
ALTER TABLE CIS_BDPM ADD CONSTRAINT FK_CIS_BDPM_TypeProc FOREIGN KEY (idTypeProc) REFERENCES TypeProc(idTypeProc);




/* -------------------------------------------- Creation de la table TauxRemboursement -------------------------------------------- */

CREATE TABLE TauxRemboursement (
    codeCIS INT(6),
    tauxRemboursement NUMERIC(6,2)
);
ALTER TABLE TauxRemboursement ADD CONSTRAINT PK_TauxRemboursement PRIMARY KEY (codeCIS);
ALTER TABLE TauxRemboursement ADD CONSTRAINT FK_TauxRemboursement_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);





/* -------------------------------------------- Creation de la table LibellePresentation -------------------------------------------- */

CREATE TABLE LibellePresentation (
    idLibellePresentation INT(3),
    libellePresentation TEXT
);
ALTER TABLE LibellePresentation ADD CONSTRAINT PK_LibellePresentation PRIMARY KEY (idLibellePresentation);
ALTER TABLE LibellePresentation MODIFY idLibellePresentation INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table EtatCommercialisation -------------------------------------------- */

CREATE TABLE EtatCommercialisation (
    idEtatCommercialisation INT(3),
    labelEtatCommercialisation TEXT
);
ALTER TABLE EtatCommercialisation ADD CONSTRAINT PK_EtatCommercialisation PRIMARY KEY (idEtatCommercialisation);
ALTER TABLE EtatCommercialisation MODIFY idEtatCommercialisation INT(3) AUTO_INCREMENT;


/* -------------------------------------------- Creation de la table CIS_CIP_BDPM -------------------------------------------- */

CREATE TABLE CIS_CIP_BDPM (
    codeCIS INT(6),
    codeCIP7 INT(7),
    idLibellePresentation INT(3),
    statutAdminiPresentation BOOL,
    idEtatCommercialisation INT(1),
    dateCommrcialisation DATE,
    codeCIP13 BIGINT(13) UNSIGNED, -- Bypass de la limite de 2.147 Md pour un int normal
    agrementCollectivites BOOL,
    prix NUMERIC(8,2),
    indicationRemboursement TEXT
);
ALTER TABLE CIS_CIP_BDPM ADD CONSTRAINT PK_CIS_CIP PRIMARY KEY (codeCIP13);
ALTER TABLE CIS_CIP_BDPM ADD CONSTRAINT FK_CIS_CIP_BDPM_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);
ALTER TABLE CIS_CIP_BDPM ADD CONSTRAINT FK_CIS_BDPM_LibellePresentation FOREIGN KEY (idLibellePresentation) REFERENCES LibellePresentation(idLibellePresentation);
ALTER TABLE CIS_CIP_BDPM ADD CONSTRAINT FK_CIS_BDPM_EtatCommercialisation FOREIGN KEY (idEtatCommercialisation) REFERENCES EtatCommercialisation(idEtatCommercialisation);





/* -------------------------------------------- Creation de la table GroupeGener -------------------------------------------- */

CREATE TABLE GroupeGener (
    idGroupeGener INT(4),
    labelGroupeGener TEXT
);
ALTER TABLE GroupeGener ADD CONSTRAINT PK_GroupeGener PRIMARY KEY (idGroupeGener);

/* -------------------------------------------- Creation de la table CIS_GENER -------------------------------------------- */

CREATE TABLE CIS_GENER (
    codeCIS INT(6),
    idGroupeGener INT(4),
    typeGenerique INT(1),
    numeroTri INT(2)
);
ALTER TABLE CIS_GENER ADD CONSTRAINT PK_CIS_GENER PRIMARY KEY (codeCIS);
ALTER TABLE CIS_GENER ADD CONSTRAINT FK_CIS_GENER_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);
ALTER TABLE CIS_GENER ADD CONSTRAINT FK_CIS_GENER_GroupeGener FOREIGN KEY (idGroupeGener) REFERENCES GroupeGener(idGroupeGener);




/* -------------------------------------------- Creation de la table DesignationElem -------------------------------------------- */
CREATE TABLE DesignationElem (
    idElem INT(3),
    labelElem VARCHAR(100)
);
ALTER TABLE DesignationElem ADD CONSTRAINT PK_DesignationElem PRIMARY KEY (idElem);
ALTER TABLE DesignationElem MODIFY idElem INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table CodeSubstance -------------------------------------------- */
CREATE TABLE CodeSubstance (
    idSubstance INT(3),
    varianceNom INT(2),
    codeSubstance TEXT
);
ALTER TABLE CodeSubstance ADD CONSTRAINT PK_CodeSubstance PRIMARY KEY (idSubstance, varianceNom);

/* -------------------------------------------- Creation de la table Dosage -------------------------------------------- */
CREATE TABLE Dosage (
    idDosage INT(3),
    labelDosage VARCHAR(100)
);
ALTER TABLE Dosage ADD CONSTRAINT PK_Dosage PRIMARY KEY (idDosage);
ALTER TABLE Dosage MODIFY idDosage INT(3) AUTO_INCREMENT;


/* -------------------------------------------- Creation de la table RefDosage -------------------------------------------- */
CREATE TABLE RefDosage (
    idRefDosage INT(3),
    labelRefDosage VARCHAR(100)
);
ALTER TABLE RefDosage ADD CONSTRAINT PK_RefDosage PRIMARY KEY (idRefDosage);
ALTER TABLE RefDosage MODIFY idRefDosage INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table CIS_COMPO -------------------------------------------- */

CREATE TABLE CIS_COMPO (
    codeCIS INT(6),
    idDesignationElemPharma INT(3),
    idCodeSubstance INT(6),
    varianceNomSubstance INT(3),
    idDosage INT(3),
    idRefDosage INT(3),
    natureCompo BOOL,
    noLiaison INT(3)
);
ALTER TABLE CIS_COMPO ADD CONSTRAINT PK_CIS_COMPO PRIMARY KEY (codeCIS);
ALTER TABLE CIS_COMPO ADD CONSTRAINT FK_CIS_COMPO_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);
ALTER TABLE CIS_COMPO ADD CONSTRAINT FK_CIS_COMPO_DesignationElem FOREIGN KEY (idDesignationElemPharma) REFERENCES DesignationElem(idElem);
ALTER TABLE CIS_COMPO ADD CONSTRAINT FK_CIS_COMPO_CodeSubstance FOREIGN KEY (idCodeSubstance, varianceNomSubstance) REFERENCES CodeSubstance(idSubstance, varianceNom);
ALTER TABLE CIS_COMPO ADD CONSTRAINT FK_CIS_COMPO_RefDosage FOREIGN KEY (idRefDosage) REFERENCES RefDosage(idRefDosage);
ALTER TABLE CIS_COMPO ADD CONSTRAINT FK_CIS_COMPO_Dosage FOREIGN KEY (idDosage) REFERENCES Dosage(idDosage);


/* -------------------------------------------- Creation de la table ID_Label_VoieAdministration -------------------------------------------- */
CREATE TABLE ID_Label_VoieAdministration (
    idVoieAdministration INT(3),
    labelVoieAdministration TEXT
);
ALTER TABLE ID_Label_VoieAdministration ADD CONSTRAINT PK_ID_Label_VoieAdministration PRIMARY KEY (idVoieAdministration);
ALTER TABLE ID_Label_VoieAdministration MODIFY idVoieAdministration INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table CIS_VoieAdministration -------------------------------------------- */
CREATE TABLE CIS_VoieAdministration (
    codeCIS INT(6),
    idVoieAdministration INT(3)
);
ALTER TABLE CIS_VoieAdministration ADD CONSTRAINT PK_CIS_VoieAdministration PRIMARY KEY (codeCIS, idVoieAdministration);
ALTER TABLE CIS_VoieAdministration ADD CONSTRAINT FK_CIS_VoieAdministration_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);
ALTER TABLE CIS_VoieAdministration ADD CONSTRAINT FK_CIS_VoieAdministration_ID_Label_VoieAdministration FOREIGN KEY (idVoieAdministration) REFERENCES ID_Label_VoieAdministration(idVoieAdministration);





/* -------------------------------------------- Creation de la table Condition -------------------------------------------- */
CREATE TABLE LabelCondition (
    idCondition INT(3),
    labelCondition TEXT
);
ALTER TABLE LabelCondition ADD CONSTRAINT PK_Condition PRIMARY KEY (idCondition);
ALTER TABLE LabelCondition MODIFY idCondition INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table CIS_CPD -------------------------------------------- */
CREATE TABLE CIS_CPD (
    codeCIS INT(6),
    idCondition INT(3)
);
ALTER TABLE CIS_CPD ADD CONSTRAINT PK_CIS_CPD PRIMARY KEY (codeCIS);
ALTER TABLE CIS_CPD ADD CONSTRAINT FK_CIS_CPD_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);
ALTER TABLE CIS_CPD ADD CONSTRAINT FK_CIS_CPD_Condition FOREIGN KEY (idCondition) REFERENCES LabelCondition(idCondition);




/* -------------------------------------------- Creation de la table Info_Texte -------------------------------------------- */
CREATE TABLE Info_Texte (
    idTexte INT(3),
    labelTexte TEXT
);
ALTER TABLE Info_Texte ADD CONSTRAINT PK_Info_Texte PRIMARY KEY (idTexte);
ALTER TABLE Info_Texte MODIFY idTexte INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table CIS_INFO -------------------------------------------- */
CREATE TABLE CIS_INFO (
    codeCIS INT(6),
    dateDebutInformation DATE,
    DateFinInformation DATE,
    idTexte INT(3)
);
ALTER TABLE CIS_INFO ADD CONSTRAINT PK_CIS_INFO PRIMARY KEY (codeCIS);
ALTER TABLE CIS_INFO ADD CONSTRAINT FK_CIS_INFO_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);
ALTER TABLE CIS_INFO ADD CONSTRAINT FK_CIS_INFO_Info_Texte FOREIGN KEY (idTexte) REFERENCES Info_Texte(idTexte);





/* -------------------------------------------- Creation de la table ID_Label_Titulaire -------------------------------------------- */
CREATE TABLE ID_Label_Titulaire (
    idTitulaire INT(3),
    labelTitulaire TEXT
);
ALTER TABLE ID_Label_Titulaire ADD CONSTRAINT PK_ID_Label_Titulaire PRIMARY KEY (idTitulaire);
ALTER TABLE ID_Label_Titulaire MODIFY idTitulaire INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table CIS_Titulaires -------------------------------------------- */
CREATE TABLE CIS_Titulaires (
    codeCIS INT(6),
    idTitulaire INT(3)
);
ALTER TABLE CIS_Titulaires ADD CONSTRAINT PK_CIS_Titulaires PRIMARY KEY (codeCIS, idTitulaire);
ALTER TABLE CIS_Titulaires ADD CONSTRAINT FK_CIS_Titulaires_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);
ALTER TABLE CIS_Titulaires ADD CONSTRAINT FK_CIS_Titulaires_ID_Label_Titulaire FOREIGN KEY (idTitulaire) REFERENCES ID_Label_Titulaire(idTitulaire);




/* -------------------------------------------- Creation de la table LibelleSmr -------------------------------------------- */
CREATE TABLE LibelleSmr (
    idLibelleSMR INT(3),
    libelleSmr TEXT
);
ALTER TABLE LibelleSmr ADD CONSTRAINT PK_LibelleSmr PRIMARY KEY (idLibelleSMR);
ALTER TABLE LibelleSmr MODIFY idLibelleSMR INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table HAS_LiensPageCT -------------------------------------------- */
CREATE TABLE HAS_LiensPageCT (
    codeHAS VARCHAR(8),
    lienPage TEXT
);
ALTER TABLE HAS_LiensPageCT ADD CONSTRAINT PK_HAS_LiensPageCT PRIMARY KEY (codeHAS);

/* -------------------------------------------- Creation de la table LibelleAsmr -------------------------------------------- */
CREATE TABLE LibelleAsmr (
    idLibelleAsmr INT(3),
    libelleAsmr TEXT
);
ALTER TABLE LibelleAsmr ADD CONSTRAINT PK_LibelleAsmr PRIMARY KEY (idLibelleAsmr);
ALTER TABLE LibelleAsmr MODIFY idLibelleAsmr INT(3) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table MotifEval -------------------------------------------- */
CREATE TABLE MotifEval ( -- INFO : pas sur le schéma car limite de forme lucid chart :/
    idMotifEval INT(3),
    libelleMotifEval VARCHAR(255)
);
ALTER TABLE MotifEval ADD CONSTRAINT PK_MotifEval PRIMARY KEY (idMotifEval);
ALTER TABLE MotifEval MODIFY idMotifEval INT(3) AUTO_INCREMENT;


/* -------------------------------------------- Creation de la table NiveauSMR -------------------------------------------- */
-- TODO Ajouter sur le schéma
CREATE TABLE NiveauSMR (
    idNiveauSMR INT(2),
    libelleNiveauSMR VARCHAR(255)
);
ALTER TABLE NiveauSMR ADD CONSTRAINT PK_NiveauSMR PRIMARY KEY (idNiveauSMR);
ALTER TABLE NiveauSMR MODIFY idNiveauSMR INT(2) AUTO_INCREMENT;

/* -------------------------------------------- Creation de la table CIS_HAS_SMR -------------------------------------------- */
CREATE TABLE CIS_HAS_SMR (
    codeCIS INT(6),
    codeHAS VARCHAR(8),
    idMotifEval INT(2),
    dateAvis DATE,
    niveauSMR INT(2),
    idLibelleSmr INT(3)
);
ALTER TABLE CIS_HAS_SMR ADD CONSTRAINT FK_CIS_HAS_SMR_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);
ALTER TABLE CIS_HAS_SMR ADD CONSTRAINT FK_CIS_HAS_SMR_HAS_LiensPageCT FOREIGN KEY (codeHAS) REFERENCES HAS_LiensPageCT(codeHAS);
ALTER TABLE CIS_HAS_SMR ADD CONSTRAINT FK_CIS_HAS_SMR_MotifEval FOREIGN KEY (idMotifEval) REFERENCES MotifEval(idMotifEval);
ALTER TABLE CIS_HAS_SMR ADD CONSTRAINT FK_CIS_HAS_SMR_LibelleSmr FOREIGN KEY (idLibelleSmr) REFERENCES LibelleSmr(idLibelleSMR);
ALTER TABLE CIS_HAS_SMR ADD CONSTRAINT FK_CIS_HAS_SMR_NiveauSMR FOREIGN KEY (niveauSMR) REFERENCES NiveauSMR(idNiveauSMR);

/* -------------------------------------------- Creation de la table CIS_HAS_ASMR -------------------------------------------- */
CREATE TABLE CIS_HAS_ASMR (
    codeCIS INT(6),
    codeHAS VARCHAR(8),
    idMotifEval INT(2),
    dateAvis DATE,
    valeurASMR TEXT,
    idLibelleAsmr INT(3)
);
ALTER TABLE CIS_HAS_ASMR ADD CONSTRAINT FK_CIS_HAS_ASMR_CIS_BDPM FOREIGN KEY (codeCIS) REFERENCES CIS_BDPM(codeCIS);
ALTER TABLE CIS_HAS_ASMR ADD CONSTRAINT FK_CIS_HAS_ASMR_HAS_LiensPageCT FOREIGN KEY (codeHAS) REFERENCES HAS_LiensPageCT(codeHAS);
ALTER TABLE CIS_HAS_ASMR ADD CONSTRAINT FK_CIS_HAS_ASMR_MotifEval FOREIGN KEY (idMotifEval) REFERENCES MotifEval(idMotifEval);
ALTER TABLE CIS_HAS_ASMR ADD CONSTRAINT FK_CIS_HAS_ASMR_LibelleAsmr FOREIGN KEY (idLibelleAsmr) REFERENCES LibelleAsmr(idLibelleAsmr);

/* -------------------------------------------- Creation de la table ErreursImportation -------------------------------------------- */
CREATE TABLE ErreursImportation (
    idErreur INT(5),
    dateErreur DATETIME DEFAULT CURRENT_TIMESTAMP,
    nomProcedure VARCHAR(255),
    messageErreur TEXT
);

ALTER TABLE ErreursImportation ADD CONSTRAINT PK_ErreursImportation PRIMARY KEY (idErreur);
ALTER TABLE ErreursImportation MODIFY idErreur INT(5) AUTO_INCREMENT;
