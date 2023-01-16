DROP VIEW IF EXISTS listMedic;
CREATE VIEW listMedic as
select CIS_BDPM.codeCIS,formePharma,labelVoieAdministration,etatCommercialisation,tauxRemboursement,prix,libellePresentation,surveillanceRenforcee,valeurASMR,libelleNiveauSMR,designation from CIS_BDPM
LEFT JOIN CIS_CIP_BDPM
ON CIS_BDPM.codeCIS = CIS_CIP_BDPM.codeCIS
LEFT JOIN CIS_VoieAdministration
ON CIS_BDPM.codeCIS = CIS_VoieAdministration.codeCIS
LEFT JOIN CIS_HAS_SMR
ON CIS_BDPM.codeCIS = CIS_HAS_SMR.codeCIS
LEFT JOIN CIS_HAS_ASMR
ON CIS_BDPM.codeCIS = CIS_HAS_ASMR.codeCIS
LEFT JOIN FormePharma
ON CIS_BDPM.idFormePharma = FormePharma.idFormePharma
LEFT JOIN ID_Label_VoieAdministration
ON CIS_VoieAdministration.idVoieAdministration = ID_Label_VoieAdministration.idVoieAdministration
LEFT JOIN TauxRemboursement
ON CIS_BDPM.codeCIS = TauxRemboursement.codeCIS
LEFT JOIN LibellePresentation
ON LibellePresentation.idLibellePresentation = CIS_CIP_BDPM.idLibellePresentation
LEFT JOIN NiveauSMR
ON NiveauSMR.idNiveauSMR = CIS_HAS_SMR.niveauSMR
LEFT JOIN DesignationElemPharma
ON CIS_BDPM.idDesignation = DesignationElemPharma.idDesignation;