<?php
/**
 * Plugin Name: Allow WebP Pictures Uploads
 * Description: A simple plugin to allow .webp image uploads in WordPress.
 * Version: 1.0
 * Author: Huda Taha
 */


/**
 * Add webp to the list of allowed mime types
 *
 * @param array $mimes Existing list of mime types.
 * @return array Modified list of mime types.
 */
function allow_webp_upload_mimes($mimes) {
    // Add webp to the list of mime types
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('upload_mimes', 'allow_webp_upload_mimes');

/**
 * Add webp to the list of allowed file types in the media library
 *
 * @param array $file Existing file data.
 * @param string $filename Filename of the file being uploaded.
 * @param array $mimes Existing list of mime types.
 * @return array Modified file data.
 */
function check_webp_upload($file, $filename, $mimes) {
    // Check the file extension
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    if ($ext === 'webp') {
        // Check the mime type
        $file['type'] = 'image/webp';
        $file['ext'] = 'webp';
    }
    return $file;
}
add_filter('wp_check_filetype_and_ext', 'check_webp_upload', 10, 3);
 ?>