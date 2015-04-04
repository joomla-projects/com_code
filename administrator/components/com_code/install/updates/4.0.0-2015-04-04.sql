ALTER TABLE `#__code_tracker_status` DROP `instructions`;
ALTER TABLE `#__code_tracker_status` DROP `jc_tracker_id`;

ALTER TABLE `#__code_tracker_issues` DROP `jc_tracker_id`;

ALTER TABLE `#__code_tracker_issue_responses` DROP `jc_tracker_id`;

ALTER TABLE `#__code_tracker_issue_changes` DROP `jc_tracker_id`;
