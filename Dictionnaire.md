| nom | Description | Type | Contraintes | Règle de composition |
| -------------- | ------------ |--------|----------|------------|
| VisiteMedicale | Visite entre un patient et un médecin | Object | null | null |
| VisiteMedicale_ID | Identifiant de la visite | INT | Calculer automatiquement a la création d'une visite par le médecin | Non vide , 6 chiffre , unique par visite  |
| VisiteMedicale_Motif | Motif de la visite | String | Entrez par le médecin lors de la création d'un visite | Non vide , nombre de caractère inférieur a 100 |
| VisiteMedicale_IDMedein | Identifiant du medecin concerné | INT | Clé étrangère récupérer grace a la session courante | Même contrainte que "Medecin_ID" |
| VisiteMedicale_IDPatient | identifiant du patient concerné | INT | Clé étrangère récupérer grace a la fiche courante ouverte par le médecin | Méme contrainte que "Patient_ID" |
| VisiteMedicale_DATE | Date de la visite | DATE | Récupérer automatiquement par le système | Forme : jj/mm/aaaa , Non vide |
| VisiteMedicale_Note | Note prise pendant ou a la fin de la visite par le médecin | String |  | Nombre de caractère inférieur à 1000 |
| VisiteMedicale_IDOrdonnance | Identifiant de la probable ordonnance lier a la visite | INT |  |  |
| Ordonnance | Liste des médicaments prescrit par le médecin a la fin d'une séance | Object | A la création d'une ordonannce | Table lien ID de l'ordonnance et CodeCiss du médicament |
| Ordonnance_Id | identifiant de l'ordonnance | INT | Calculer automatiquement a la création d'une ordonnance par le médecin | > 0 , < 10000 , unique par ordonnance |
| Ordonnance_IdMedicament | identifiant des médicaments de l'ordonnance | INT |  | CodeCiss du médicament |
| Patient | Patient étant venu au cabinet consulter | Object |  |  |
| Patient_ID | Identifiant du patient égal a son numéro de sécurité sociale (NSS) | INT | Entrer par le médecin | contrainte NSS , Non vide et unique |
| Patient_Nom | nom du patient | String | Entrer par le médecin | Non vide , pas de caractère spécial , pas d'espace , pas de chiffre   |
| Patient_Prenom | prenom du patient | String | Entrer par le médecin | Non vide , pas de caractère spécial , pas d'espace , pas de chiffre |
| Patient_DateNaissance | date de naissance du patient | DATE | Entrer pas le médecin | Non vide , Forme : jj/mm/aaaa |
| Patient_Adresse | Adresse du patient | String | Entrez par le médecin | Pas de caractère spécial |
| Patient_CodePostal | code postal du patient | INT |  | Non vide , 5 chiffre |
| Patient_IDMedecinRef | Identifiant du médecin référent du patient | INT | Clé étrangère récuperer grace a la session du médecin créant le patient dans la base de données | cf "Medecin_ID" |
| Patient_NumTel | Numéro de téléphone du patient | INT | Entrer par le médecin | Numéro en retirant le premier chiffre (0) , Non vide |
| Patient_Email | email du patient | String | Entrer par le médecin | Forme : %@%.% , Non vide |
| Patient_lieu_Naissance | lieu de naissance du patient | String | Forme : code postal ville | |
| Medecin | Un médecin réalisant des consultations au cabinet | Object | null | null |
| Medecin_ID | Identifiant dans la base de donnée du médecin égal au numéro RPPS | INT | Entrer par l'administrateur  | contrainte RPPS |
| Medecin_Login | Identifiant de connexion a l'application du médecin | String | A entrer lors de la connexion à l'application | Forme : nom.prenom , sans accent |
| Medecin_Password | Mot de passe de conenxion a l'application du médecin | String | A entrer lors de la connexion a l'application | 10 caractère minimum, 1 caractère spécial , lettre et chiffre , majuscule et minuscule |
| Medecin_Nom | Nom du médecin | String | Entrer par l'administrateur | Non vide , pas de caractère spécial , pas d'espace , pas de chiffre |
| Medecin_Prenom | Prenom du médecin | String | Entrer par l'administrateur | Non vide , pas de caractère spécial , pas d'espace , pas de chiffre |
| Medecin_Adresse | Adresse du médecin | String | Entrer par l'administrateur | Pas de caractère spécial |
| Medecin_dob | date de naissance | Date | Entrer par l'administrateur | Non vide , Forme : jj/mm/aaaa |
| Medecin_NumTel | Numéro de téléphone du médecin | INT | Entrer par l'administrateur | Numéro en retirant le premier chiffre (0) , Non vide |
| Medecin_Email | Email du médecin | String | Entrer par l'administrateur | De la forme %@%.% , Non vide |
| Medecin_NumRPPS | Identifiant national du médecin | INT | Entrer par l'administrateur | Contrainte RPPS |
| Medecin_DateInscription | Date à laquelle le médecin a rejoint le cabinet | DATE | Récuperer par le système a la date d'ajout dans la base de données | De la forme jj/mm/aaaa , Non vide |
| Medecin_lieuActivite | lieu d'activité du médecin | String | null | null |
| Medecin_TempExerc | Dure d'activité du médecin | INT | null | null |
| Administrateur | Administrateur du cabinet , personne qui paramètre les informations du cabinet et des médecins | Object |  |  |
| Administrateur_Login | Identifiant de connexion a l'application de l'administrateur | String |  | Forme : nom.prenom sans accent |
| Administrateur_Password | Mot de passe de connexion a l'application de l'administrateur | String |  | 10 caractère minimum, 1 caractère spécial , lettre et chiffre , majuscule et minuscule |
| Cabinet | Information cabinet où tout se passe | Object |  |  |
| Cabinet_Adresse | Adresse du cabinet | String | Entrer par l'administrateur | Pas de caractère spécial , Non vide|
| Cabinet_CodePostal | Code postal du cabinet | INT |  | Non vide , 5 chiffre |
| Cabinet_DateOuverture | Date a laquelle le cabinet a ouvert pour la première fois | DATE | Entrer par l'administrateur | De la forme jj/mm/aaaa , Non vide |
| Cabinet_Medecins | Liste des médecins du cabinet | List |  |  |
