CREATE VIEW listMedic as
select cis_bdpm.codeCIS,formePharma,labelVoieAdministration,etatCommercialisation,tauxRemboursement,prix,libellePresentation,surveillanceRenforcee,valeurASMR,libelleNiveauSMR from cis_bdpm 
LEFT JOIN cis_cip_bdpm 
ON cis_bdpm.codeCIS = cis_cip_bdpm.codeCIS
LEFT JOIN cis_voieadministration
ON cis_bdpm.codeCIS = cis_voieadministration.codeCIS
LEFT JOIN cis_has_smr
ON cis_bdpm.codeCIS = cis_has_smr.codeCIS
LEFT JOIN cis_has_asmr
ON cis_bdpm.codeCIS = cis_has_asmr.codeCIS
LEFT JOIN formepharma
ON cis_bdpm.idFormePharma = formepharma.idFormePharma
LEFT JOIN id_label_voieadministration
ON cis_voieadministration.idVoieAdministration = id_label_voieadministration.idVoieAdministration
LEFT JOIN tauxremboursement
ON cis_bdpm.codeCIS = tauxremboursement.codeCIS
LEFT JOIN libellepresentation
ON libellepresentation.idLibellePresentation = cis_cip_bdpm.idLibellePresentation
LEFT JOIN niveausmr
ON niveausmr.idNiveauSMR = cis_has_smr.niveauSMR