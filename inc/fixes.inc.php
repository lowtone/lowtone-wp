<?php
namespace lowtone\wp\fixes;

/*
 * Forward slashes in the file path would be removed by update_metadata().
 *
 * @todo Review updated_attached_file filter. Attached file path should be 
 * relative to the uploads folder and it isn't when using this filter.
 */

add_filter("update_attached_file", "lowtone\\wp\\fixes\\fixSlashes", 9999);

add_filter("wp_generate_attachment_metadata", "lowtone\\wp\\fixes\\fixAttachmentMetaData", 9999);

add_filter("wp_update_attachment_metadata", "lowtone\\wp\\fixes\\fixAttachmentMetaData", 9999);

function fixSlashes($path) {
	return str_replace("\\", "/", $path);
}

function fixAttachmentMetaData($data) {
	if (!isset($data["file"]))
		return $data;
	
	$data["file"] = fixSlashes($data["file"]);

	return $data;
}