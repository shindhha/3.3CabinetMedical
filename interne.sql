DROP TABLE IF EXISTS ListeVisites;
DROP TABLE IF EXISTS Patients;
DROP TABLE IF EXISTS Medecins;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS Ordonnances;
DROP TABLE IF EXISTS Visites;
DROP TABLE IF EXISTS Cabinet;



CREATE TABLE Patients (
	idPatient INT AUTO_INCREMENT PRIMARY KEY,
	numSecu CHAR(13) UNIQUE,
	LieuNaissance VARCHAR(200),
	nom VARCHAR(25),
	prenom VARCHAR(25),
	dateNaissance DATE,
	adresse VARCHAR(50),
	codePostal INT,
	ville VARCHAR(255),
	medecinRef CHAR(11),
	numTel INT(9),
	email VARCHAR(50),
	notes TEXT,
	sexe BOOLEAN
);

CREATE TABLE Medecins (
    idUser INT,
	idMedecin INT AUTO_INCREMENT ,
	numRPPS CHAR(11) UNIQUE,
	nom VARCHAR(25),
	prenom VARCHAR(25),
	adresse VARCHAR(25),
	numTel INT,
	email VARCHAR(100),
	dateInscription DATE,
	dateDebutActivites DATE,
	activite VARCHAR(100),
	codePostal INT,
	ville VARCHAR(100),
	lieuAct VARCHAR(100),
	PRIMARY KEY (idMedecin)
);
CREATE TABLE Visites (
	idVisite INT AUTO_INCREMENT,
	motifVisite TEXT,
	dateVisite DATE,
	Description TEXT,
	Conclusion TEXT,
	PRIMARY KEY (idVisite)
);

CREATE TABLE ListeVisites (
	idMedecin INT,
	idPatient INT,
	idVisite INT,
	PRIMARY KEY (idVisite,idPatient)
);

CREATE TABLE Cabinet (
	adresse VARCHAR(25),
	codePostal INT,
	dateOuverture DATE
);
CREATE TABLE Ordonnances (
	idVisite INT,
	codeCIP7 INT,
	instruction TEXT,
	PRIMARY KEY (idVisite,codeCIP7)
);
CREATE TABLE users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	login VARCHAR(11) UNIQUE,
	`password` CHAR(32)

);
ALTER TABLE Medecins ADD CONSTRAINT FK_Medecins_Users FOREIGN KEY (idUser) REFERENCES users(id);
ALTER TABLE Patients ADD CONSTRAINT CK_Email_Patients CHECK (email LIKE '%@%.%');
ALTER TABLE ListeVisites ADD CONSTRAINT FK_ListeVisites_Medecins FOREIGN KEY (idMedecin) REFERENCES Medecins(idMedecin);
ALTER TABLE ListeVisites ADD CONSTRAINT FK_ListeVisites_Visites FOREIGN KEY (idVisite) REFERENCES Visites(idVisite);
ALTER TABLE ListeVisites ADD CONSTRAINT FK_ListeVisites_Patients FOREIGN KEY (idPatient) REFERENCES Patients(idPatient);
ALTER TABLE Ordonnances ADD CONSTRAINT FK_Visites_Ordonnances FOREIGN KEY (idVisite) REFERENCES Visites(idVisite);
ALTER TABLE Medecins ADD CONSTRAINT CK_Email_Medecins CHECK (email LIKE '%@%.%');