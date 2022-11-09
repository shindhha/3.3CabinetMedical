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
| Patient_ID | identifiant du patient | INT | null | null |
| Patient_Nom | nom du patient | String | null | null |
| Patient_Prenom | prenom du patient | String | null | null |
| Patient_DateNaissance | date de naissance du patient | DATE | null | null |
| Patient_Adresse | Adresse du patient | String | null | null |
| Patient_CodePostal | code postal du patient | INT | null | null |
| Patient_NuméroDeSécu | numéro de sécurité sociale du patient | INT | null | null |
| Patient_IDMedecinRef | Identifiant du médecin référent du patient | INT | null | null |
| Patient_NumTel | Numéro de téléphone du patient | INT | null | null |
| Patient_Email | email du patient | String | de la forme %@%.% | null |
| Medecin | Un médecin réalisant des consultations au cabinet | Object | null | null |
| Medecin_ID | Identifiant dans la base de donnée du médecin | INT | null | null |
| Medecin_Login | Identifiant de connexion a l'application du médecin | String | null | null |
| Medecin_Password | Mot de passe de conenxion a l'application du médecin | String | null | null |
| Medecin_Nom | Nom du médecin | String | null | null |
| Medecin_Prenom | Prenom du médecin | String | null | null |
| Medecin_NumTel | Numéro de téléphone du médecin | INT | null | null |
| Medecin_Email | Email du médecin | String | de la forme %@%.% | null |
| Medecin_NumRPPS | Identifiant national du médecin | INT | null | null |
| Administrateur | Administrateur du cabinet , personne qui paramètre les informations du cabinet et des médecins | Object | null | null |
| Administrateur_Login | Identifiant de connexion a l'application de l'administrateur | String | null | null |
| Administrateur_Password | Mot de passe de connexion a l'application de l'administrateur | String | null | null |
| Cabinet | Information cabinet où tout se passe | Object | null | null |
| Cabinet_Adresse | Adresse du cabinet | String | null | null |
| Cabinet_CodePostal | Code postal du cabinet | INT | null | null |
| Cabinet_DateOuverture | Date a laquelle le cabinet a ouvert pour la première fois | DATE | null | null |
| Cabinet_Medecins | Liste des médecins du cabinet | List | null | null |
