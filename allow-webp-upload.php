<?php
/**
 * Plugin Name: Allow WebP Pictures Uploads
 * Description: A simple plugin to allow .webp image uploads in WordPress.
 * Version: 1.0
 * Author: Huda Taha
 */


 
 
 // If this file is called directly, abort.
 if (!defined('ABSPATH')) {
     exit;
 }
 
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
  * @param array $data Existing file data.
  * @param string $file Full path to the file.
  * @param string $filename The name of the file (may differ from $file due to upload directory).
  * @param array $mimes Array of mime types keyed by their file extension regex.
  * @return array Modified file data.
  */
 function check_webp_upload($data, $file, $filename, $mimes) {
     // Check the file extension
     $ext = pathinfo($filename, PATHINFO_EXTENSION);
     if ($ext === 'webp') {
         // Check the mime type
         $data['ext'] = 'webp';
         $data['type'] = 'image/webp';
         $data['proper_filename'] = $data['proper_filename'] ?? $filename;
     }
     return $data;
 }
 add_filter('wp_check_filetype_and_ext', 'check_webp_upload', 10, 4);
 
 /**
  * Skip image processing for WebP images.
  *
  * @param array $metadata Image metadata.
  * @param int $attachment_id Attachment ID.
  * @return array Modified image metadata.
  */
 function skip_webp_image_processing($metadata, $attachment_id) {
     $mime = get_post_mime_type($attachment_id);
     if ($mime === 'image/webp') {
         // Return minimal metadata for WebP images
         return [
             'width' => 0,
             'height' => 0,
             'file' => get_attached_file($attachment_id),
             'sizes' => [],
             'image_meta' => []
         ];
     }
     return $metadata;
 }
 add_filter('wp_generate_attachment_metadata', 'skip_webp_image_processing', 10, 2);
 
 /**
  * Filter to fix WebP uploads not being allowed.
  */
 function fix_webp_upload_error($wp_handle_upload) {
     $filetype = wp_check_filetype_and_ext($wp_handle_upload['file'], $wp_handle_upload['name']);
     if ($filetype['type'] === 'image/webp') {
         $wp_handle_upload['type'] = 'image/webp';
         $wp_handle_upload['ext'] = 'webp';
     }
     return $wp_handle_upload;
 }
 add_filter('wp_handle_upload_prefilter', 'fix_webp_upload_error');
 add_filter('wp_handle_upload', 'fix_webp_upload_error');
?> 