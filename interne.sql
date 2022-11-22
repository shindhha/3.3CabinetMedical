CREATE TABLE LieuActivites (
	idLieuActivite INT(6) PRIMARY KEY,
	labelLieuActivites VARCHAR(25)
);
CREATE TABLE LieuxActivites (
	numRPPS INT(11) PRIMARY KEY,
	idLieuActivite INT(6) PRIMARY KEY
);
CREATE TABLE Patients (
	numSecu INT(13) PRIMARY KEY,
	idLieuNaissance INT(6),
	nom VARCHAR(25),
	prenom VARCHAR(25),
	dateNaissance DATE,
	adresse VARCHAR(50),
	codePostal INT(5),
	medecinRef INT(11),
	numTel INT(9),
	email VARCHAR(50)
);
CREATE TABLE LieuxNaissance (
	idLieuNaissance INT(6) PRIMARY KEY,
	labelLieuNaissance VARCHAR(25)
);
CREATE TABLE Medecins (
	numRPPS INT(11) PRIMARY KEY,
	motDePasse CHAR(60),
	nom VARCHAR(25),
	prenom VARCHAR(25),
	adresse VARCHAR(25),
	dateNaissance DATE,
	numTel INT(9),
	email VARCHAR(100),
	dateInscription DATE,
	dateDebutActivites DATE
);
CREATE TABLE Visites (
	idVisite INT(6) PRIMARY KEY,
	motifVisite CLOB,
	dateVisite DATE,
	note CLOB,
	idOrdonnance INT(6) PRIMARY KEY
);
CREATE TABLE ListeVisites (
	numSecu INT(13) PRIMARY KEY,
	numRPPS INT(11) PRIMARY KEY,
	idVisite INT(6) PRIMARY KEY,
);

CREATE TABLE Administrateurs (
	login VARCHAR(25),
	motDePasse CHAR(60)
);
CREATE TABLE Cabinet (
	adresse VARCHAR(25),
	codePostal INT(5),
	dateOuverture DATE
);
CREATE TABLE Ordonnances (
	idOrdonnance INT(6) PRIMARY KEY,
	cissCode INT(6) PRIMARY KEY
);
ALTER TABLE Ordonnances ADD CONSTRAINT FK_Ordonnances_Medicaments FOREIGN KEY (cissCode) REFERENCES CIS_BDMP(cissCode);
ALTER TABLE LieuxActivites ADD CONSTRAINT FK_LieuxActivites_Medecins FOREIGN KEY (numRPPS) REFERENCES Medecins(numRPPS);
ALTER TABLE LieuxActivites ADD CONSTRAINT FK_LieuxActivites_LieuActivites FOREIGN KEY (idLieuActivite) REFERENCES LieuActivites(idLieuActivite);
ALTER TABLE Patients ADD CONSTRAINT CK_Email_Patients CHECK (email LIKE %@%.%);
ALTER TABLE Patients ADD CONSTRAINT FK_Patients_LieuxNaissance FOREIGN KEY (idLieuNaissance) REFERENCES LieuxNaissance(idLieuNaissance);
ALTER TABLE ListeVisites ADD CONSTRAINT FK_ListeVisites_Patients FOREIGN KEY (numSecu) REFERENCES Patients(numSecu);
ALTER TABLE ListeVisites ADD CONSTRAINT FK_ListeVisites_Medecins FOREIGN KEY (numRPPS) REFERENCES Medecins(numRPPS);
ALTER TABLE ListeVisites ADD CONSTRAINT FK_ListeVisites_Visites FOREIGN KEY (idVisite) REFERENCES Visites(idVisite);
ALTER TABLE Visites ADD CONSTRAINT FK_Visites_Ordonnances FOREIGN KEY (idOrdonnance) REFERENCES Ordonnances(idOrdonnance);
ALTER TABLE Medecins ADD CONSTRAINT CK_Email_Medecins CHECK (email LIKE %@%.%);