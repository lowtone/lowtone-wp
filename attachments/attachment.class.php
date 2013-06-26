<?php
namespace lowtone\wp\attachments;
use lowtone\net\URL,
	lowtone\io\File,
	lowtone\wp\posts\Post,
	lowtone\types\datetime\DateTime,
	lowtone\util\options\Options;

/**
 * @author Paul van der Meijs <code@lowtone.nl>
 * @copyright Copyright (c) 2011-2012, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.0
 * @package wordpress\libs\lowtone\wp\pages
 */
class Attachment extends Post {
	
	const META_ATTACHED_FILE = "_wp_attached_file",
		META_ATTACHMENT_METADATA = "_wp_attachment_metadata";

	/**
	 * Get the path to the attached file (this is a relative path to the uploads
	 * base dir).
	 * @return string Returns the path to the attached file.
	 */
	public function getAttachedFile() {
		return get_post_meta($this->getPostId(), self::META_ATTACHED_FILE, true);
	}

	public function getAttachedFileUrl() {
		$uploadDir = wp_upload_dir();

		return $uploadDir["baseurl"] . "/" . $this->getAttachedFile();
	}

	public function getAttachmentMetadata() {
		return get_post_meta($this->getPostId(), self::META_ATTACHMENT_METADATA, true);
	}

	/**
	 * Attempt to get the size of the attached file.
	 * @return int Returns the size of the attached file in bytes on success or 
	 * FALSE on failure.
	 */
	public function getFileSize() {
		$uploadDir = wp_upload_dir();
		
		if (!($file = realpath($uploadDir["basedir"] . "/" . $this->getAttachedFile())))
			return false;

		try {
			$size = filesize($file);
		} catch (\Exception $e) {
			return false;
		}

		return $size;
	}

	// Static
	
	public static function __getTable() {
		return Post::__getTable();
	}

	public static function __getDocumentClass() {
		return "lowtone\\wp\\attachments\\out\\AttachmentDocument";
	}

	public static function fromUrl($url, $options = NULL) {
		return static::fromFile(File::get($url), $options);
	}

	/**
	 * Create and insert an attachment from a File instance.
	 *
	 * If the target option is defined it should point to a location inside the 
	 * uploads base dir and also the path to the uploads base dir should use the 
	 * exact same slashes as the value returned by wp_upload_dir or else 
	 * _wp_relative_upload_path() will fail when the attachment meta data is 
	 * generated.
	 * 
	 * @param File $file The file used to create an attachment.
	 * @param array|NULL $options Additional options used to save the file and 
	 * create the attachment.
	 * @return Attachment Returns a new attachment instance.
	 */
	public static function fromFile(File $file, $options = NULL) {
		$options = Options::defaults(array(
				"target" => function() use ($file) {
					$uploadDir = wp_upload_dir(DateTime::now()->format("Y/m"));

					return $uploadDir["path"] . "/" . basename($file->url()->{URL::PROPERTY_PATH});
				},
				"defaults" => array(),
				"post_id" => NULL,
			), $options);

		// Save file

		$file = $file->put($options["target"]);

		// Attachment properties

		$url = site_url(str_replace("\\", "/", $file->relPath()));

		$fileType = wp_check_filetype($file->url(), null);

		$attachment = array(
			self::PROPERTY_GUID => $url,
			self::PROPERTY_POST_MIME_TYPE => $fileType["type"],
			self::PROPERTY_POST_TITLE => $file->url()->pathinfo(PATHINFO_FILENAME),
			self::PROPERTY_POST_CONTENT => "",
			self::PROPERTY_POST_STATUS => self::STATUS_INHERIT
		);

		$attachment = array_merge($attachment, (array) $options["defaults"]);

		$attachment[self::PROPERTY_ID] = wp_insert_attachment($attachment, $options["target"]);

		// Attachment meta

		include_once ABSPATH . "/wp-admin/includes/image.php";

		$attachmentMeta = wp_generate_attachment_metadata($attachment[self::PROPERTY_ID], $options["target"]);
		
		wp_update_attachment_metadata($attachment[self::PROPERTY_ID], $attachmentMeta);

		return new Attachment($attachment);
	}
	
}