
-- create drop tables to test database
DROP TABLE IF EXISTS commentTeam;
DROP TABLE IF EXISTS commentEvent;
DROP TABLE IF EXISTS commentUser;
DROP TABLE IF EXISTS userTeam;
DROP TABLE IF EXISTS teamEvent;
DROP TABLE IF EXISTS userEvent;
DROP TABLE IF EXISTS teamCause;
DROP TABLE IF EXISTS userCause;
DROP TABLE IF EXISTS comment;
DROP TABLE IF EXISTS event;
DROP TABLE IF EXISTS team;
DROP TABLE IF EXISTS cause;
DROP TABLE IF EXISTS profile;
DROP TABLE IF EXISTS user;

-- create tables for helpacrossabq

CREATE TABLE user(
	userId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	userName VARCHAR(32) NOT NULL,
	email	VARCHAR(64)	NOT NULL,
	passwordHash CHAR(128) NOT NULL,
	salt CHAR(64),
	authToken CHAR(32),
	permissions TINYINT UNSIGNED NOT NULL,
	PRIMARY KEY(userId),
	INDEX(permissions),
	UNIQUE(userName),
	UNIQUE(email),
	UNIQUE(authToken)
);

CREATE TABLE profile(
	profileId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	userId INT UNSIGNED NOT NULL,
	userTitle VARCHAR(64),
	firstName VARCHAR(32) NOT NULL,
	midInit CHAR(2),
	lastName VARCHAR(32) NOT NULL,
	bio VARCHAR(1000),
	attention VARCHAR(64),
	street1 VARCHAR(64),
	street2 VARCHAR(64),
	city VARCHAR(64),
	state CHAR(2),
	zipCode VARCHAR(16) NOT NULL,
	PRIMARY KEY(profileId),
	FOREIGN KEY (userId) REFERENCES user(userId),
	INDEX(zipCode),
	UNIQUE (userId)
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
	UNIQUE (teamName),
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

CREATE TABLE comment (
	commentId INT UNSIGNED NOT NULL AUTO_INCREMENT,
	commentText VARCHAR(2048) NOT NULL,
	commentDate DATETIME NOT NULL,
	INDEX (commentDate),
	PRIMARY KEY (commentId)
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
	commentPermission TINYINT NOT NULL,
	banStatus TINYINT NOT NULL,
	INDEX (profileId),
	INDEX (eventId),
	PRIMARY KEY (eventId, profileId),
	FOREIGN KEY (profileId) REFERENCES profile(profileId),
	FOREIGN KEY (eventId) REFERENCES event (eventId)
);

CREATE TABLE teamEvent (
	teamId INT UNSIGNED NOT NULL,
	eventId INT UNSIGNED NOT NULL,
	teamStatus TINYINT NOT NULL,
	commentPermission TINYINT NOT NULL,
	banStatus TINYINT NOT NULL,
	PRIMARY KEY (teamId, eventId),
	INDEX (teamId),
	INDEX (eventId),
	FOREIGN KEY (teamId) REFERENCES team(teamId),
	FOREIGN KEY (eventId) REFERENCES event(eventId)
);

CREATE TABLE userTeam (
	profileId INT UNSIGNED NOT NULL,
	teamId INT UNSIGNED NOT NULL,
	roleInTeam TINYINT UNSIGNED NOT NULL,
	teamPermission TINYINT UNSIGNED NOT NULL,
	commentPermission TINYINT UNSIGNED NOT NULL,
	invitePermission TINYINT UNSIGNED NOT NULL,
	banStatus TINYINT UNSIGNED NOT NULL,
	INDEX(profileId),
	INDEX(teamId),
	INDEX(roleInTeam),
	INDEX(commentPermission),
	INDEX(invitePermission),
	INDEX(banStatus),
	PRIMARY KEY (profileId, teamId),
	FOREIGN KEY (profileId) REFERENCES profile (profileId),
	FOREIGN KEY (teamId) REFERENCES team (teamId)
);

CREATE TABLE commentUser(
	profileId INT UNSIGNED NOT NULL,
	commentId INT UNSIGNED NOT NULL,
	INDEX(profileId),
	INDEX(commentId),
	PRIMARY KEY (profileId, commentId),
	FOREIGN KEY (profileId) REFERENCES profile(profileId),
	FOREIGN KEY (commentId) REFERENCES comment(commentId)
);

CREATE TABLE commentEvent(
	eventId INT UNSIGNED NOT NULL,
	commentId INT UNSIGNED NOT NULL,
	INDEX(eventId),
	INDEX(commentId),
	PRIMARY KEY(eventId, commentId),
	FOREIGN KEY (eventId) REFERENCES event(eventId),
	FOREIGN KEY (commentId) REFERENCES comment(commentId)
);

CREATE TABLE commentTeam(
	teamId INT UNSIGNED NOT NULL,
	commentId INT UNSIGNED NOT NULL,
	INDEX (teamId),
	INDEX (commentId),
	PRIMARY KEY (teamId, commentId),
	FOREIGN KEY (teamId) REFERENCES team(teamId),
	FOREIGN KEY (commentId) REFERENCES comment(commentId)
);

-- Comment for test.
