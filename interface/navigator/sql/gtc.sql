# container for all referral and actions, cases can be owned by individuals or groups
DROP TABLE IF EXISTS `gtc_case`;
CREATE TABLE `gtc_case` (
  `case_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier for case',
  `client_id` bigint(20) NOT NULL COMMENT 'reference to patient_data pid in OpenEMR',
  `creator_id` bigint(20) NOT NULL COMMENT 'reference to user in users table, who created the case',
  `state_id` bigint(20) NOT NULL COMMENT 'current state of case, refers to gtc_state',
  PRIMARY KEY (`case_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

# owner of a case, multiple users, groups, etc can refer back to single case
DROP TABLE IF EXISTS `gtc_case_owner`;
CREATE TABLE `gtc_case_owner` (
  `case_id` bigint(20) NOT NULL COMMENT 'refers to case_id in gtc_case',
  `owner_type` bigint(20) NOT NULL COMMENT 'single user or group',
  `owner_id` bigint(20) NOT NULL COMMENT 'based on type, owner id could be a user name or group name'
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

# an action. and action can contain one or more actions of various types
DROP TABLE IF EXISTS `gtc_action`;
CREATE TABLE `gtc_action` (
  `action_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier for action',
  `parent_id` bigint(20) NOT NULL COMMENT 'actions can generate other actions. this is the parent id of action, if it was generated by another action',
  `case_id` bigint(20) NOT NULL COMMENT 'reference to case_id in gtc_case',
  `action_type_id` bigint(20) NOT NULL COMMENT 'reference to gtc_action_type',
  `state_id` bigint(20) NOT NULL COMMENT 'current state of action, refers to gtc_state',
  PRIMARY KEY (`action_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

# contains additional data and meta data about an action, "encounter_id"
DROP TABLE IF EXISTS `gtc_action_meta`;
CREATE TABLE `gtc_action_meta` ( 
  `meta_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'unique id for meta element',
  `action_id` bigint(20) NOT NULL COMMENT 'reference to unique identifier for action type',
  `meta_key` varchar(255) NOT NULL COMMENT 'meta key, like days_till_due, or form_link, order, encounter id', 
  `meta_value` longtext NOT NULL COMMENT 'meta value',
  PRIMARY KEY (`meta_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

# contains all types (taxonomy) of actions and sub-actions: referral, telephone call, visit, etc.
DROP TABLE IF EXISTS `gtc_action_type`;
CREATE TABLE `gtc_action_type` (
  `action_type_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier for action type',
  `parent_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`action_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

# contains relationships to action types, like associated forms, etc.
DROP TABLE IF EXISTS `gtc_action_type_meta`;
CREATE TABLE `gtc_action_type_meta` (
  `meta_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'unique id for meta element',
  `action_type_id` bigint(20) NOT NULL COMMENT 'unique identifier for action type',
  `meta_key` varchar(255) NOT NULL,
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`meta_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

# contains all states possible in the system (could be in list_options table)
DROP TABLE IF EXISTS `gtc_state`;
CREATE TABLE `gtc_state` (
  `state_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier for state',
  `type` varchar(255) NOT NULL COMMENT 'state type, action or case',
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  PRIMARY KEY (`state_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `gtc_transition`;
CREATE TABLE `gtc_transition` (
  `transition_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier for transition',
  `from_state_id` bigint(20) NOT NULL,
  `to_state_id` bigint(20) NOT NULL,
  PRIMARY KEY (`transition_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `gtc_transition_event`;
CREATE TABLE `gtc_transition_event` (
  `transition_event_id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'unique identifier for transition event',
  `transition_id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `options` longtext DEFAULT NULL,
  PRIMARY KEY (`transition_event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


