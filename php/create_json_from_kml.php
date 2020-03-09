<?php


class KmlToJsonConverter {

	private $_filename;
	private $_outfile;

	public function __construct( string $filename, $outfile ) {
		$this->_filename = trim( $filename );
		$this->_outfile  = $outfile;

		try {
			$this->_outFileExists();
		} catch ( Throwable $e ) {
			echo "Fatal error: " . $e->getMessage();
		}
		try {
			$this->convert();
		} catch ( Throwable $e ) {
			echo "Fatal error: " . $e->getMessage();
		}
	}

	private function _outFileExists() {
		if ( file_exists( $this->_outfile ) ) {
			throw new Exception( "Outfile exists: " . $this->_outfile );
		}
	}

	public function convert() {
		if ( ! file_exists( $this->_filename ) ) {
			throw new Exception( "File not found: " . $this->_filename );
		}
		$content  = simplexml_load_file( $this->_filename );
		$document = $content->Document;


		$data                       = new stdClass();
		$data->conversion_timestamp = time();
		$data->name                 = $document->Placemark->name->__toString();
		$data->description          = $document->Placemark->description->__toString();
		$data->coordinates          = [];
		$coords                     = explode( "\n", $document->Placemark->LineString->coordinates->__toString() );

		foreach ( $coords as $coord ) {
			$c_line    = trim( $coord );
			$c_line_ex = explode( ",", $c_line );
			if ( count( $c_line_ex ) > 2 ) {
				$latitude  = $c_line_ex[0];
				$longitude = $c_line_ex[1];
				if ( ! is_null( $latitude ) && ! is_null( $longitude ) ) {
					$o                   = new stdClass();
					$o->latitude         = $latitude;
					$o->longitude        = $longitude;
					$data->coordinates[] = $o;
				}
			}
		}

		$json = json_encode( $data );
		file_put_contents( $this->_outfile, $json );
	}


}


