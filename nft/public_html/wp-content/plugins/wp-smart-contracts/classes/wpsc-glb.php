<?php

if( ! defined( 'ABSPATH' ) ) die;

add_filter( 'upload_mimes', function ($mimes) {
	$mimes['glb'] = 'model/gltf-binary';
	$mimes['gltf'] = 'model/gltf-binary';
    return $mimes;
});

function wpsc_add_exception($data, $file, $filename,$mimes,$real_mime=null){
	$f_sp = explode(".", $filename);
	$f_exp_count  = count ($f_sp);

	if($f_exp_count <= 1) {
		return $data;
	} else {
		$f_name = $f_sp[0];
		$ext  = $f_sp[$f_exp_count - 1];
	}

	if($ext == 'glb') {
		$type = 'model/gltf-binary';
		$proper_filename = '';
		return compact('ext', 'type', 'proper_filename');
	} 
    if($ext == 'gltf') {
		$type = 'model/gltf-binary';
		$proper_filename = '';
		return compact('ext', 'type', 'proper_filename');
	} else {
		return $data;
	}
}

global $wp_version;
if ( version_compare( $wp_version, '5.1') >= 0) {
	add_filter( 'wp_check_filetype_and_ext', 'wpsc_add_exception',10,5);
} else {
	add_filter( 'wp_check_filetype_and_ext', 'wpsc_add_exception',10,4);
}