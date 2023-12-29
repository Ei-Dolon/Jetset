<?php

namespace Blocksy\Database;

class Utils {
	static public function wp_get_table_names() {
		global $wpdb;

		$tables_sql = 'SHOW TABLES';

		// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared -- Query is safe, see above.
		$tables = $wpdb->get_col($tables_sql, 0);

		return $tables;
	}
}

