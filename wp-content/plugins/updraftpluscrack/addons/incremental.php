<?php
// @codingStandardsIgnoreStart
/*
UpdraftPlus Addon: incremental:Support for incremental backups
Description: Allows UpdraftPlus to schedule incremental file backups, which use much less resources
Version: 1.0
Shop: /shop/incremental/
Latest Change: 1.14.5
*/
// @codingStandardsIgnoreEnd

if (!defined('UPDRAFTPLUS_DIR')) die('No direct access allowed');

/**
 * Warning: this code is still a work in progress and is not yet complete. For this reason it is disabled and will be enabled when complete.
 */
if (!defined('UPDRAFTPLUS_INCREMENTAL_BACKUPS_ADDON')) return;

$updraftplus_addon_incremental = new UpdraftPlus_Addons_Incremental;

class UpdraftPlus_Addons_Incremental {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Priority 11 so that it loads after the filter that adds the backup label
		add_filter('updraftplus_showbackup_date', array($this, 'showbackup_date'), 11, 5);
	}

	/**
	 * This function will add to the backup label information on when the last incremental set was created, it will also add to the title the dates for all the incremental sets in this backup.
	 *
	 * @param string  $date          - the date when the backup set was first created
	 * @param array   $backup        - the backup set
	 * @param array   $jobdata       - an array of information relating to the backup job
	 * @param integer $backup_date   - the timestamp of when the backup set was first created
	 * @param boolean $simple_format - a boolean value to indicate if this should be a simple format date
	 *
	 * @return string                - returns a string that is either the original backup date or the string that contains the incremental set data
	 */
	public function showbackup_date($date, $backup, $jobdata, $backup_date, $simple_format) {

		$incremental_sets = !empty($backup['incremental_sets']) ? $backup['incremental_sets'] : array();
		
		if (!empty($incremental_sets)) {
			
			$latest_increment = key(array_slice($incremental_sets, -1, 1, true));

			if ($latest_increment > $backup_date) {
				
				$increment_times = '';

				foreach ($incremental_sets as $inc_time => $entities) {
					if ($increment_times) $increment_times .= '; ';
					$increment_times .= get_date_from_gmt(gmdate('Y-m-d H:i:s', $inc_time), 'M d, Y G:i');
				}

				if ($simple_format) {
					return $date.' '.sprintf(__('(latest increment: %s)', 'updraftplus'), get_date_from_gmt(gmdate('Y-m-d H:i:s', $latest_increment), 'M d, Y G:i'));
				} else {
					return '<span title="'.sprintf(__('Increments exist at: %s', 'updraftplus'), $increment_times).'">'.$date.'<br>'.sprintf(__('(latest increment: %s)', 'updraftplus'), get_date_from_gmt(gmdate('Y-m-d H:i:s', $latest_increment), 'M d, Y G:i')).'</span>';
				}
			}
		}
		
		return $date;
	}
}
