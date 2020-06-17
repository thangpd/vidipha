<?php
// @codingStandardsIgnoreStart
/*
UpdraftPlus Addon: wp-cli:WP CLI
Description: Adds WP-CLI commands
Version: 1.1
Shop: /shop/wp-cli/
RequiresPHP: 5.3.3
Latest Change: 1.14.6
*/
// @codingStandardsIgnoreEnd

if (!defined('UPDRAFTPLUS_DIR')) die('No direct access allowed');

if (!defined('WP_CLI') || !WP_CLI || !class_exists('WP_CLI_Command')) return;

/**
 * Implements Updraftplus CLI all commands
 */
class UpdraftPlus_CLI_Command extends WP_CLI_Command {
	
	/**
	 * Constructor
	 */
	public function __construct() {
		add_filter('updraft_user_can_manage', '__return_true');
		add_filter('updraftplus_backupnow_start_message', array($this, 'backupnow_start_message'), 10, 2);
	}
	
	/**
	 * Take backup. If any option is not given to command, the default option will be taken to proceed
	 *
	 * ## OPTIONS
	 *
	 * [--exclude-db]
	 * : Exclude database from backup
	 *
	 * [--include-files=<include-files>]
	 * : File entities which will be backed up. Multiple file entities names should separate by comma (,).
	 *
	 * [--include-tables=<include-tables>]
	 * : Tables which will be backed up.  You should backup all tables unless you are an expert in the internals of the WordPress database. Multiple table names seperated by comma (,). If include-tables is not added in command, All tables will be backed up
	 * ---
	 * default: all
	 * ---
	 *
	 * [--send-to-cloud]
	 * : Whether or not send backup to remote cloud storage
	 *
	 * [--label=<label>]
	 * : Backup label
	 *
	 * ## EXAMPLES
	 *
	 * wp updraftplus backup --exclude-db --include-files="plugins,themes" --send-to-cloud --label="UpdraftplusCLI Backup"
	 *
	 * @when after_wp_load
	 *
	 * @param Array $args       A indexed array of command line arguments
	 * @param Array $assoc_args Key value pair of command line arguments
	 */
	public function backup($args, $assoc_args) {
		global $wpdb, $updraftplus;
		if (isset($assoc_args['exclude-db']) && filter_var($assoc_args['exclude-db'], FILTER_VALIDATE_BOOLEAN)) {
			$backupnow_db = false;
		} else {
			$backupnow_db = true;
		}
		if (isset($assoc_args['send-to-cloud'])) {
			$backupnow_cloud = filter_var($assoc_args['send-to-cloud'], FILTER_VALIDATE_BOOLEAN) ? true : false;
		} else {
			$backupnow_cloud = $this->get_default_send_to_cloud();
		}
		$only_these_file_entities = isset($assoc_args['include-files']) ? str_replace(' ', '', $assoc_args['include-files']) : $this->get_backup_default_include_files();
		if (isset($assoc_args['include-files'])) {
			$only_these_file_entities_array = explode(',', $only_these_file_entities);
			$backupable_entities = $updraftplus->get_backupable_file_entities(true, true);
			foreach ($only_these_file_entities_array as $include_backup_entity) {
				if (!isset($backupable_entities[$include_backup_entity])) {
					WP_CLI::error(sprintf(__("The given value for the '%s' option is not valid", 'updraftplus'), 'include-files'), true);
				}
			}
		} else {
			$only_these_file_entities = $this->get_backup_default_include_files();
		}
		$backupnow_files = empty($only_these_file_entities) ? false : true;
		$only_these_table_entities = !empty($assoc_args['include-tables']) ? str_replace(' ', '', $assoc_args['include-tables']) : '';
		if (isset($assoc_args['include-tables']) && '' == $assoc_args['include-tables'] && false == $backupnow_nodb) {
			WP_CLI::error(__('You have chosen to backup a database, but no tables have been selected', 'updraftplus'), $exit = true);
		}
		if (true === $only_these_table_entities || 'all' === $only_these_table_entities) {
			$only_these_table_entities = '';
		}
		if (!$backupnow_db && !$backupnow_files) {
			WP_CLI::error(__('If you exclude both the database and the files, then you have excluded everything!', 'updraftplus'), $exit = true);
		}
		$params = array(
			'backupnow_nodb'    => !$backupnow_db,
			'backupnow_nofiles' => !$backupnow_files,
			'backupnow_nocloud' => !$backupnow_cloud,
			'backupnow_label'   => empty($assoc_args['label']) ? '' : $assoc_args['label'],
			'extradata'         => '',
		);
		if ('' != $only_these_file_entities) {
			$params['onlythisfileentity'] = $only_these_file_entities;
		}
		if ('' != $only_these_table_entities) {
			$temp_onlythesetableentities = explode(',',  $only_these_table_entities);
			foreach ($temp_onlythesetableentities as $onlythesetableentity) {
				if (0 === stripos($onlythesetableentity, $wpdb->prefix)) {
					$query = $wpdb->prepare("SHOW TABLES LIKE %s", $wpdb->esc_like($onlythesetableentity));
					// There is possible that table name like wp_wp_custom_table
					if ($onlythesetableentity == $wpdb->get_var($query)) {
						$new_onlythesetableentity = $onlythesetableentity;
					} else {
						$new_onlythesetableentity = $wpdb->prefix.$onlythesetableentity;
					}
				} else {
					$new_onlythesetableentity = $wpdb->prefix.$onlythesetableentity;
				}
				$params['onlythesetableentities'][] = array(
					'name'  => 'updraft_include_tables_wp',
					'value' => $new_onlythesetableentity
				);
			}
		}
		$params['background_operation_started_method_name'] = '_backup_background_operation_started';
		$this->set_commands_object();
		$this->commands->backupnow($params);
	}
	
	/**
	 * When backup started, It displays success message
	 *
	 * @return string $default_include_files default backup include files
	 */
	private function get_backup_default_include_files() {
		global $updraftplus;
		$default_include_files_array = array();
		$backupable_entities = $updraftplus->get_backupable_file_entities(true, true);
		// The true (default value if non-existent) here has the effect of forcing a default of on.
		$include_more_paths = UpdraftPlus_Options::get_updraft_option('updraft_include_more_path');
		foreach ($backupable_entities as $key => $info) {
			if (UpdraftPlus_Options::get_updraft_option("updraft_include_$key", apply_filters("updraftplus_defaultoption_include_".$key, true))) {
				$default_include_files_array[] = $key;
			}
		}
		$default_include_files = implode(',', $default_include_files_array);
		return $default_include_files;
	}
	
	/**
	 * Get default send to cloud options
	 *
	 * @return boolean $default_send_to_cloud default
	 */
	private function get_default_send_to_cloud() {
		global $updraftplus;
		$service = $updraftplus->just_one(UpdraftPlus_Options::get_updraft_option('updraft_service'));
		if (is_string($service)) $service = array($service);
		if (!is_array($service)) $service = array();
		$default_send_to_cloud = (empty($service) || array('none') === $service || 'none' === $service || array('') === $service) ? false : true;
		return $default_send_to_cloud;
	}
	
	/**
	 * When backup started, It displays success message
	 *
	 * @param array $msg_arr Message data
	 */
	public function _backup_background_operation_started($msg_arr) {
		WP_CLI::success($msg_arr['m']);
		WP_CLI::success(sprintf(__('Recently started backup job id: %s', 'updraftplus'), $msg_arr['nonce']));
	}
	
	/**
	 * Filter updraftplus_backupnow_start_message, backupnow_start_message message changed
	 *
	 * @param string $message backup start message
	 * @param string $job_id  backup job identifier
	 */
	public function backupnow_start_message($message, $job_id) {
		return sprintf(__('Backup has been started successfully. You can see the last log message by running the following command: "%s"', 'updraftplus'), 'wp updraftplus backup_progress '.$job_id);
	}
	
	/**
	 * Set commands variable as object of UpdraftPlus_Commands
	 */
	private function set_commands_object() {
		if (!isset($this->commands)) {
			if (!class_exists('UpdraftPlus_Commands')) include_once(UPDRAFTPLUS_DIR.'/includes/class-commands.php');
			$this->commands = new UpdraftPlus_Commands($this);
		}
	}
	
	/**
	 * See backup_progress
	 *
	 * ## OPTIONS
	 *
	 * <job_id>
	 * : The backup job identifier
	 *
	 * ## EXAMPLES
	 *
	 * wp updraftplus backup_progress b290ee083e9e
	 *
	 * @when after_wp_load
	 *
	 * @param Array $args A indexed array of command line arguments
	 */
	public function backup_progress($args) {
		$params['job_id'] = $args[0];
		$this->set_commands_object();
		$data = $this->commands->backup_progress($params);
		WP_CLI::success($data['l']);
	}
	
	/**
	 * See log
	 *
	 * ## OPTIONS
	 *
	 * <job_id>
	 * : The backup job identifier
	 *
	 * ## EXAMPLES
	 *
	 * wp updraftplus get_log b290ee083e9e
	 *
	 * @when after_wp_load
	 *
	 * @param  array $args A indexed array of command line arguments
	 */
	public function get_log($args) {
		$job_id = $args[0];
		$this->set_commands_object();
		$log_data = $this->commands->get_log($job_id);
		if (is_wp_error($log_data)) {
			if (isset($log_data->errors['updraftplus_permission_invalid_jobid'])) {
				WP_CLI::error(__("Invalid Job Id", 'updraftplus'));
					
			} else {
				WP_CLI::error(print_r($log_data, true));
			}
		}
		WP_CLI::log($log_data['log']);
	}
	
	/**
	 * See get_most_recently_modified_log
	 *
	 * ## EXAMPLES
	 *
	 *		wp updraftplus get_most_recently_modified_log
	 *
	 * @when after_wp_load
	 */
	public function get_most_recently_modified_log() {
		if (false === ($updraftplus = $this->_load_ud())) return new WP_Error('no_updraftplus');
		list($mod_time, $log_file, $job_id) = $updraftplus->last_modified_log();
		$this->set_commands_object();
		$log_data = $this->commands->get_log($job_id);
		WP_CLI::log($log_data['log']);
	}
	
	/**
	 * Gives global $updraftplus variable
	 *
	 * @return object - global object of UpdraftPlus class
	 */
	private function _load_ud() {
		global $updraftplus;
		return is_a($updraftplus, 'UpdraftPlus') ? $updraftplus : false;
	}
	
	/**
	 * Gives global $updraftplus_admin variable
	 *
	 * @return object - global object of UpdraftPlus_Admin class
	 */
	private function _load_ud_admin() {
		if (!defined('UPDRAFTPLUS_DIR') || !is_file(UPDRAFTPLUS_DIR.'/admin.php')) return false;
		include_once(UPDRAFTPLUS_DIR.'/admin.php');
		global $updraftplus_admin;
		return $updraftplus_admin;
	}
		
	/**
	 * Delete active_job
	 *
	 * ## OPTIONS
	 *
	 * <job_id>
	 * : The backup job identifier
	 *
	 * ## EXAMPLES
	 *
	 * wp updraftplus activejobs_delete b290ee083e9e
	 *
	 * @when after_wp_load
	 *
	 * @param  array $args A indexed array of command line arguments
	 */
	public function activejobs_delete($args) {
		$job_id = $args[0];
		$this->set_commands_object();
		$delete_data = $this->commands->activejobs_delete($job_id);
		WP_CLI::log($delete_data['m']);
	}
	
	/**
	 * List existing_backups
	 *
	 * ## EXAMPLES
	 *
	 * wp updraftplus existing_backups
	 *
	 * @when after_wp_load
	 */
	public function existing_backups() {
		if (false === ($updraftplus = $this->_load_ud())) return new WP_Error('no_updraftplus');
		if (false === ($updraftplus_admin = $this->_load_ud_admin())) return new WP_Error('no_updraftplus');
		
		$accept = apply_filters('updraftplus_accept_archivename', array());
if (!is_array($accept)) $accept = array();
		
		$backup_history = UpdraftPlus_Backup_History::get_history();
		if (empty($backup_history)) {
			$backup_history = UpdraftPlus_Backup_History::get_history();
		}
		// Reverse date sort - i.e. most recent first
		krsort($backup_history);
		$items = array();
		foreach ($backup_history as $key => $backup) {
			$remote_sent = (!empty($backup['service']) && ((is_array($backup['service']) && in_array('remotesend', $backup['service'])) || 'remotesend' === $backup['service'])) ? true : false;
			$pretty_date = get_date_from_gmt(gmdate('Y-m-d H:i:s', (int) $key), 'M d, Y G:i');
			$esc_pretty_date = esc_attr($pretty_date);
			$nonce = $backup['nonce'];
			$jobdata = $updraftplus->jobdata_getarray($nonce);
			$date_label = $updraftplus_admin->date_label($pretty_date, $key, $backup, $jobdata, $nonce, true);
			// Remote backups with no log result in useless empty rows. However, not showing anything messes up the "Existing Backups (14)" display, until we tweak that code to count differently
			if ($remote_sent) {
				$backup_entities = __('Backup sent to remote site - not available for download.', 'updraftplus');
				if (!empty($backup['remotesend_url'])) {
					$backup_entities .= ' '.__('Site', 'updraftplus').': '.htmlspecialchars($backup['remotesend_url']);
				}
			} else {
				$row_backup_entities = array();
				$backup_entities_row = $this->get_backup_entities_row($backup, $accept);
				$backup_entities = implode(', ', $backup_entities_row);
			}
			
			$job_identifier = strip_tags($date_label).' ['.$nonce.']';
			if (!empty($backup['service'])) {
				 $job_identifier = ' ('.implode(',', $backup['service']).')';
			}
			$items[] = array(
				'job_identifier'  => $job_identifier,
				'nonce'           => $nonce,
				'backup_entities' => $backup_entities,
			);
		}
		// @codingStandardsIgnoreLine
		WP_CLI\Utils\format_items('table', $items, array('job_identifier', 'nonce', 'backup_entities'));
	}
	
	/**
	 * Get backup entities for existing backup table
	 *
	 * @param array $backup - backup entity row
	 * @param array $accept
	 *
	 * @return array $backup_entities_row - backup entities for rxisting_backup table
	 */
	private function get_backup_entities_row($backup, $accept) {
		$backup_entities_row = array();
		if (false === ($updraftplus = $this->_load_ud())) return new WP_Error('no_updraftplus');
		if (empty($backup['meta_foreign']) || !empty($accept[$backup['meta_foreign']]['separatedb'])) {
			if (isset($backup['db'])) {
				// Set a flag according to whether or not $backup['db'] ends in .crypt, then pick this up in the display of the decrypt field.
				$db = is_array($backup['db']) ? $backup['db'][0] : $backup['db'];
				if (!empty($backup['meta_foreign']) && isset($accept[$backup['meta_foreign']])) {
					$desc_source = $accept[$backup['meta_foreign']]['desc'];
				} else {
					$desc_source = __('unknown source', 'updraftplus');
				}
				$backup_entities_row[] = empty($backup['meta_foreign']) ? esc_attr(__('Database', 'updraftplus')) : (sprintf(__('Database (created by %s)', 'updraftplus'), $desc_source));
			}

			// External databases
			foreach ($backup as $bkey => $binfo) {
				if ('db' == $bkey || 'db' != substr($bkey, 0, 2) || '-size' == substr($bkey, -5, 5)) continue;
				$backup_entities_row[] = __('External database', 'updraftplus').' ('.substr($bkey, 2).')';
			}
		}
		$backupable_entities = $updraftplus->get_backupable_file_entities(true, true);
		foreach ($backupable_entities as $type => $info) {
			if (!empty($backup['meta_foreign']) && 'wpcore' != $type) continue;
			if ('wpcore' == $type) $wpcore_restore_descrip = $info['description'];
			if (empty($backup['meta_foreign'])) {
				$sdescrip = preg_replace('/ \(.*\)$/', '', $info['description']);
				if (strlen($sdescrip) > 20 && isset($info['shortdescription'])) $sdescrip = $info['shortdescription'];
			} else {
				$info['description'] = 'WordPress';
				$sdescrip = (empty($accept[$backup['meta_foreign']]['separatedb'])) ? sprintf(__('Files and database WordPress backup (created by %s)', 'updraftplus'), $desc_source) : sprintf(__('Files backup (created by %s)', 'updraftplus'), $desc_source);
				if ('wpcore' == $type) $wpcore_restore_descrip = $sdescrip;
			}
			if (isset($backup[$type])) {
				if (!is_array($backup[$type])) $backup[$type] = array($backup[$type]);
				$howmanyinset = count($backup[$type]);
				$expected_index = 0;
				$index_missing = false;
				$set_contents = '';
				if (!isset($entities)) $entities = '';
				$entities .= "/$type=";
				$whatfiles = $backup[$type];
				ksort($whatfiles);
				foreach ($whatfiles as $findex => $bfile) {
					$set_contents .= ('' == $set_contents) ? $findex : ",$findex";
					if ($findex != $expected_index) $index_missing = true;
					$expected_index++;
				}
				$entities .= $set_contents.'/';
				if (!empty($backup['meta_foreign'])) {
					$entities .= '/plugins=0//themes=0//uploads=0//others=0/';
				}
				$printing_first = true;
				foreach ($whatfiles as $findex => $bfile) {
					$pdescrip = ($findex > 0) ? $sdescrip.' ('.($findex+1).')' : $sdescrip;
					$backup_entities_row[] = $pdescrip;
				}
			}
		}
		return $backup_entities_row;
	}
	
	/**
	 * Rescan storage either local or remote
	 *
	 * ## OPTIONS
	 *
	 * <type>
	 * : Type of rescan storage. Its value should be either local or remote
		---
		default: remote
		options:
		  - remote
		  - local
		---
	 *
	 * ## EXAMPLES
	 *
	 * wp updraftplus rescan-storage local
	 * wp updraftplus rescan-storage remote
	 *
	 * @subcommand rescan-storage
	 * @alias rescan_storage
	 * @when after_wp_load
	 *
	 * @param Array $args A indexed array of command line arguments
	 */
	public function rescan_storage($args) {
		$args_what_rescan = empty($args[0]) ? 'remote' : $args[0];
		if (!in_array($args_what_rescan, array('remote', 'local'))) {
			WP_CLI::error(sprintf(__("The given value for the '%s' option is not valid", 'updraftplus'), 'scan type'), true);
		}
		$what_rescan = ('remote' == $args_what_rescan) ? 'remotescan' : 'rescan';
		$this->set_commands_object();
		$history_statuses = $this->commands->rescan($what_rescan);
		if (!empty($history_statuses['n'])) {
			WP_CLI::success(__('Success', 'updraftplus'));
			WP_CLI::runcommand('updraftplus existing_backups');
		} else {
			WP_CLI::error(__('Error', 'updraftplus'));
		}
	}
}

WP_CLI::add_command('updraftplus', 'UpdraftPlus_CLI_Command');
