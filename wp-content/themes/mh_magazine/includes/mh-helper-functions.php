<?php

/***** Sanitize a comma-separated list of IDs *****/

function mh_magazine_sanitize_id_list($id_list) {
	$ids = explode(',', $id_list);
	$ids = array_map('intval', $ids);
	return implode(', ', $ids);
}

/***** Sort ID list into IDs to include and IDs to exclude *****/

function mh_magazine_sort_id_list($id_array) {
	$sorted_ids = array();
	foreach ($id_array as $id) {
		if (intval($id) < 0) {
			$sorted_ids['exclude'][] = absint($id);
		} else {
			$sorted_ids['include'][] = absint($id);
		}
	}
	return $sorted_ids;
}

?>