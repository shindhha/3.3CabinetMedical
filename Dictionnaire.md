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
| Patient_CodePostal | code postal du patient | INT |  | Non vide , < 100000 |
| Patient_NuméroDeSécu | numéro de sécurité sociale du patient | INT | null | null |
| Patient_IDMedecinRef | Identifiant du médecin référent du patient | INT | null | null |
| Patient_NumTel | Numéro de téléphone du patient | INT |  | Numéro en retirant le premier chiffre (0) , Non vide |
| Patient_Email | email du patient | String | | Forme : %@%.%  |
| Medecin | Un médecin réalisant des consultations au cabinet | Object | null | null |
| Medecin_ID | Identifiant dans la base de donnée du médecin | INT | null | null |
| Medecin_Login | Identifiant de connexion a l'application du médecin | String | A entrer lors de la connexion à l'application| Forme : nom.prenom , sans accent |
| Medecin_Password | Mot de passe de conenxion a l'application du médecin | String | A entrer lors de la connexion a l'application | 10 caractère minimum, 1 caractère spécial , lettre et chiffre , majuscule et minuscule |
| Medecin_Nom | Nom du médecin | String |  | Sans accent , Non vide |
| Medecin_Prenom | Prenom du médecin | String |  | Sans accent , Non vide |
| Medecin_Adresse | Adresse du médecin | String | | |
| Medecin_dob | date de naissance | Date | | De la forme jj/mm/aaaa , Non vide |
| Medecin_lieu_Naissance | lieu de naissance | String | | |
| Medecin_NumTel | Numéro de téléphone du médecin | INT |  | Numéro en retirant le premier chiffre (0) , Non vide |
| Medecin_Email | Email du médecin | String |  | De la forme %@%.% , Non vide |
| Medecin_NumRPPS | Identifiant national du médecin | INT | null | null |
| Medecin_lieu_act | lieu dans lequel il travail | List | | composé d'adresse |
| Medecin_categorie | categorie dans laquelle il exerce | String | | |
| Medecin_temps_exercement | nombre d'années depuis qu'il exerce | int | | <-
| Medecin_DateInscription | Date à laquelle le médecin a rejoint le cabinet | DATE |  | De la forme jj/mm/aaaa , Non vide |
| Administrateur | Administrateur du cabinet , personne qui paramètre les informations du cabinet et des médecins | Object |  |  |
| Administrateur_Login | Identifiant de connexion a l'application de l'administrateur | String |  | Forme : nom.prenom sans accent |
| Administrateur_Password | Mot de passe de connexion a l'application de l'administrateur | String |  | 10 caractère minimum, 1 caractère spécial , lettre et chiffre , majuscule et minuscule |
| Cabinet | Information cabinet où tout se passe | Object |  |  |
| Cabinet_Adresse | Adresse du cabinet | String |  | Non vide |
| Cabinet_CodePostal | Code postal du cabinet | INT |  | Non vide , < 100000 |
| Cabinet_DateOuverture | Date a laquelle le cabinet a ouvert pour la première fois | DATE |  | De la forme jj/mm/aaaa , Non vide |
| Cabinet_Medecins | Liste des médecins du cabinet | List |  |  |
