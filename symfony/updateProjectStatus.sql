delimiter //
DROP PROCEDURE IF EXISTS updateProjectStatus;
CREATE PROCEDURE updateProjectStatus ()
BEGIN
	UPDATE `project`
	SET
	`project_status_id` = 2
	WHERE SYSDATE() > `idea_end_date`;

	UPDATE `project`
	SET
	`project_status_id` = 3
	WHERE SYSDATE() > `video_end_date`;
END//