DROP TABLE IF EXISTS  userTeam;
DROP TABLE IF EXISTS  teamEvent;
DROP TABLE IF EXISTS  userEvent;


-- create tables for helpacrossabq

CREATE TABLE user(
	userId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	userName VARCHAR(32) NOT NULL,
	email	VARCHAR(72)	NOT NULL,
	passwordHash CHAR(120) NOT NULL,
	salt CHAR(64),
	authToken CHAR(32),
	permissions TINYINT UNSIGNED NOT NULL,
	PRIMARY KEY(userId),
	INDEX(permissions),
	UNIQUE(userName),
	UNIQUE(email)
);

CREATE TABLE profile(
	profileId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	userId INT UNSIGNED NOT NULL,
	userTitle VARCHAR(64),
	firstName VARCHAR(32) NOT NULL,
	middleInitial CHAR(1),
	lastName VARCHAR(32) NOT NULL,
	biography VARCHAR(1000),
	attention VARCHAR(64),
	street1 VARCHAR(64),
	street2 VARCHAR(64),
	city VARCHAR(64),
	state CHAR(2),
	zipCode VARCHAR(10) NOT NULL,
	PRIMARY KEY(profileId),
	FOREIGN KEY (userId) REFERENCES user(userId),
	INDEX(zipCode),
	INDEX(userId)
);

CREATE TABLE cause(
	causeId INT UNSIGNED NOT NULL  AUTO_INCREMENT,
	causeName VARCHAR(64) NOT NULL,
	causeDescription VARCHAR(256),
	INDEX (causeName),
	PRIMARY KEY (causeId)
);

CREATE TABLE team (

);

CREATE TABLE event (

);

CREATE TABLE userCause (
	profileId INT UNSIGNED NOT NULL
);

CREATE TABLE  teamCause (

);

CREATE TABLE userEvent (

);

CREATE TABLE teamEvent (

);

CREATE TABLE userTeam (
	profileId INT UNSIGNED NOT NULL,
	teamId INT UNSIGNED NOT NULL,
	roleInTeam TINYINT UNSIGNED NOT NULL,
	teamPermission TINYINT UNSIGNED NOT NULL
);

-- Comment for test.