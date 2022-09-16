ALTER TABLE wcf1_user ADD uzWarnings INT(10) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD INDEX uzWarnings (uzWarnings);

ALTER TABLE wcf1_user ADD uzWarningPoints INT(10) NOT NULL DEFAULT 0;
ALTER TABLE wcf1_user ADD INDEX uzWarningPoints (uzWarningPoints);
