<?php
class Go_DocCreator
{
	public function __construct( $arguments, $configFile, $config )
	{
		$path	= $config['docCreator']['pathTool'];
		if( !file_exists( $path ) )
			throw new Exception( 'Tool "DocCreator" is not installed' );
		$file	= dirname( dirname( __FILE__ ) )."/doc.xml";
		require_once( $path.'/core/ConsoleRunner.php5' );

		$creator	= new DocCreator_Core_ConsoleRunner( $file );									//  open new starter
	}
}
?>