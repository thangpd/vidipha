<h2 class="nav-tab-wrapper">
	<a class="nav-tab<?php if ( empty($data['tab'])) { echo ' nav-tab-active'; } ?>" href="?import=<?php echo $data['importer']; ?>" aria-selected="<?php echo empty($data['tab'])? 'true' : 'false'; ?>"><?php _e('Migration', 'fg-joomla-to-wordpress'); ?></a>
	<a class="nav-tab<?php if ( $data['tab'] == 'help') { echo ' nav-tab-active'; } ?>" href="?import=<?php echo $data['importer']; ?>&amp;tab=help" aria-selected="<?php echo ($data['tab'] == 'help')? 'true' : 'false'; ?>"><?php _e('Help', 'fg-joomla-to-wordpress'); ?></a>
	<a class="nav-tab<?php if ( $data['tab'] == 'debuginfo') { echo ' nav-tab-active'; } ?>" href="?import=<?php echo $data['importer']; ?>&amp;tab=debuginfo" aria-selected="<?php echo ($data['tab'] == 'debuginfo')? 'true' : 'false'; ?>"><?php _e('Debug Info', 'fg-joomla-to-wordpress'); ?></a>
</h2>
