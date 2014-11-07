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

);

CREATE TABLE team (

);

CREATE TABLE event (

);

CREATE TABLE userCause (

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