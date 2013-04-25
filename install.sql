DROP TABLE IF EXISTS linklist1_link;
CREATE TABLE linklist1_link (
linkID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
categoryID INT(10),
userID	INT(10),
username	VARCHAR(255),
subject	VARCHAR(255) NOT NULL,
message	TEXT,
url	VARCHAR(255),
time INT(10) NOT NULL,
languageID INT(10),
isActive	TINYINT(1) NOT NULL DEFAULT 0,
isDeleted	TINYINT(1) NOT NULL DEFAULT 0,
deleteTime INT(10) NULL,
lastChangeTime	INT(10),
enableSmilies TINYINT(1) NOT NULL DEFAULT 1,
enableHtml TINYINT(1) NOT NULL DEFAULT 0,
enableBBCodes	TINYINT(1) NOT NULL DEFAULT 1
ipAddress VARCHAR(39) NOT NULL DEFAULT ''
);


ALTER TABLE linklist1_link ADD FOREIGN KEY (categoryID) REFERENCES wcf1_category (categoryID) ON DELETE CASCADE;
ALTER TABLE linklist1_link ADD FOREIGN KEY (userID) REFERENCES wcf1_user (userID) ON DELETE SET NULL;
ALTER TABLE linklist1_link ADD FOREIGN KEY (languageID) REFERENCES wcf1_language (languageID) ON DELETE SET NULL;