use monoclmain;
CREATE TABLE `questiontable`(
QuestionID INT(11) NOT NULL AUTO_INCREMENT,
QuestionText LONGTEXT NOT NULL,
Language varchar(2) NOT NULL,
Topic varchar(45) NOT NULL,
UserID INT(11) NOT NULL,
Teacher bit(1) NOT NULL,
GeneralAvailibility bit(1) NOT NULL,
PRIMARY KEY(QuestionID)
);
