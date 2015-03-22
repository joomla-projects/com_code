DROP TABLE `#__code_projects`;
ALTER TABLE `#__code_trackers` DROP `project_id`;
ALTER TABLE `#__code_trackers` DROP `jc_project_id`;
ALTER TABLE `#__code_tracker_issues` DROP `project_id`;
ALTER TABLE `#__code_tracker_issues` DROP `jc_project_id`;
