| nom | Description | Type | Contraintes | Règle de composition |
| -------------- | ------------ |--------|----------|------------|
| VisiteMedicale | Visite entre un patient et un médecin | Object | null | null |
| VisiteMedicale_ID | identifiant de la visite | INT | null | null |
| VisiteMedicale_Motif | motif de la visite | String | null | null |
| VisiteMedicale_IDMedein | identifiant du medecin concerné | INT | null | null|
| VisiteMedicale_IDPatient | identifiant du patient concerné | INT | null | null |
| VisiteMedicale_DATE | date de la visite | DATE | null | null |
| VisiteMedicale_Note | note prise pendant ou a la fin de la visite par le médecin | String | null | null |
| VisiteMedicale_IDOrdonnance | identifiant de la probable ordonnance lier a la visite | INT | null | null |
| Ordonnance | Liste des médicaments prescrit par le médecin a la fin d'une séance | Object | null | null |
| Ordonnance_Id | identifiant de l'ordonnance | INT | null | null |
| Ordonnance_IdMedicament | identifiant des médicaments de l'ordonnance | INT | null | On peut avoir plusieur médicaments par ordonnance |
| Patient | Patient étant venu au cabinet consulter | Object | null | null |
| Patient_Nom | nom du patient | String | null | null |
| Patient_Prenom | prenom du patient | String | null | null |
| Patient_DateNaissance | date de naissance du patient | DATE | null | null |
| Patient_Adresse | Adresse du patient | String | null | null |
| Patient_CodePostal | code postal du patient | INT | null | null |
| Patient_NuméroDeSécu | numéro de sécurité sociale du patient | INT | null | null |
| Patient_
