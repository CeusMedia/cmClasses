<?php
require_once dirname( __FILE__ )."/../autoload.php";
//print( 'init loaders at '.date( 'H:i:s' )."\n" );

$loaderTest	= new CMC_Loader();																//  get new Loader Instance
$loaderTest->setExtensions( 'php,php5' );																	//  set allowed Extension
$loaderTest->setPath( dirname( __FILE__ ) );													//  set fixed Library Path
#$loaderLib->setLogFile( TRUE );
#$loaderLib->setVerbose( TRUE );
$loaderTest->setPrefix( 'Test_' );
$loaderTest->registerAutoloader();

$__config	= parse_ini_file( dirname( __FILE__ )."/../cmClasses.ini", TRUE );

Test_Case::$config = $__config;
?>
