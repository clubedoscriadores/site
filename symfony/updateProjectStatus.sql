delimiter //

CREATE PROCEDURE updateProjectStatus ()
BEGIN
	UPDATE `project`
	SET
	`project_status_id` = 2
	WHERE `idea_end_date` > SYSDATE();

	UPDATE `project`
	SET
	`project_status_id` = 3
	WHERE `video_end_date` > SYSDATE();
END//