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
	teamId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	teamName VARCHAR(64)	NOT NULL,
	teamCause VARCHAR(64) NOT NULL,
	INDEX (teamName),
	INDEX (teamCause),
	PRIMARY KEY (teamId)
);

CREATE TABLE event (
	eventId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	eventTitle VARCHAR(64) NOT NULL,
	eventDate DATETIME NOT NULL,
	eventLocation VARCHAR(512),
	PRIMARY KEY (eventId)
);

CREATE TABLE userCause (
	profileId INT UNSIGNED NOT NULL,
	causeId INT UNSIGNED NOT NULL,
	INDEX (profileId),
	INDEX (causeId),
	PRIMARY KEY (profileId,causeId),
	FOREIGN KEY (profileId) REFERENCES profile(profileId),
	FOREIGN KEY (causeId) REFERENCES cause (causeId)
	);

CREATE TABLE  teamCause (
		teamId INT UNSIGNED NOT NULL,
		causeId INT UNSIGNED NOT NULL,
		INDEX (teamId),
		INDEX	(causeId),
		PRIMARY KEY (teamId, causeId),
		FOREIGN KEY (teamId) REFERENCES team(teamId),
		FOREIGN KEY (causeId) REFERENCES cause(causeId)
);

CREATE TABLE userEvent (
	profileId INT UNSIGNED NOT NULL,
	eventId	INT UNSIGNED NOT NULL,
	userEventRole TINYINT NOT NULL,
	postingPermissions TINYINT NOT NULL,
	banStatus TINYINT NOT NULL,
	INDEX (profileId),
	INDEX (eventId),
	PRIMARY KEY (eventId, profileId),
	FOREIGN KEY (profileId) REFERENCES profile(profileId),
	FOREIGN KEY (eventId) REFERENCES event (eventId)
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
