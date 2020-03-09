<?php

function slugify( $text ) {
	$text = preg_replace( '~[^\pL\d]+~u', '-', $text );
	$text = iconv( 'utf-8', 'us-ascii//TRANSLIT', $text );
	$text = preg_replace( '~[^-\w]+~', '', $text );
	$text = trim( $text, '-' );
	$text = preg_replace( '~-+~', '-', $text );
	$text = strtolower( $text );

	if ( empty( $text ) ) {
		return 'n-a';
	}

	return $text;
}

require_once 'create_json_from_kml.php';
$dir   = "../original_files";
$files = scandir( $dir, 1 );
foreach ( $files as $file ) {
	if ( preg_match( "/kml/", $file ) ) {
		$slug = slugify($file);
		$outfile = $slug.".json";
		$infile = $dir."/".$file;
		//$converter = new KmlToJsonConverter( $dir."/".$file , $outfile);
		//echo "{$file} = {$slug}\n";
	}
}
