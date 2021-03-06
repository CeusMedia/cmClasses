<?php
/**
 *	TestUnit of Median Blur.
 *	@package		Tests.ui.image
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Median Blur.
 *	@package		Tests.ui.image
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			UI_Image_MedianBlur
 *	@uses			File_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			16.02.2008
 *	@version		0.1
 *	@deprecated		user UI_Image_Filter instead
 *	@todo			to be removed in 0.7.7
 */
class Test_UI_Image_MedianBlurTest extends PHPUnit_Framework_TestCase
{
	public function __construct()
	{
		return TRUE;
		$this->path	= dirname( __FILE__ )."/";
	}
	
	public function tearDown()
	{
		return TRUE;
		@unlink( $this->path."targetMedian.gif" );
		@unlink( $this->path."targetMedian.png" );
		@unlink( $this->path."targetMedian.jpg" );
	}

	public function testBlurGif()
	{
		return TRUE;
		$assertFile	= $this->path."assertMedian.gif";
		$sourceFile	= $this->path."sourceMedian.gif";
		$targetFile	= $this->path."targetMedian.gif";
		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_MedianBlur( $sourceFile, $targetFile );
		$creator->blur();

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$file		= new File_Reader( $assertFile );
		$this->assertTrue( $file->equals( $targetFile ) );
	}

	public function testBlurJpg()
	{
		return TRUE;
		$assertFile	= $this->path."assertMedian.jpg";
		$sourceFile	= $this->path."sourceMedian.jpg";
		$targetFile	= $this->path."targetMedian.jpg";
		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_MedianBlur( $sourceFile, $targetFile );
		$creator->blur();

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$file		= new File_Reader( $assertFile );
		$this->assertTrue( $file->equals( $targetFile ) );
	}

	public function testBlurPng()
	{
		return TRUE;
		$assertFile	= $this->path."assertMedian.png";
		$sourceFile	= $this->path."sourceMedian.png";
		$targetFile	= $this->path."targetMedian.png";
		if( file_exists( $targetFile ) )
			unlink( $targetFile );
		
		$creator	= new UI_Image_MedianBlur( $sourceFile, $targetFile );
		$creator->blur();

		$assertion	= true;
		$creation	= file_exists( $targetFile );
		$this->assertEquals( $assertion, $creation );

		$file		= new File_Reader( $assertFile );
		$this->assertTrue( $file->equals( $targetFile ) );
	}

	public function testBlurExceptions()
	{
		return TRUE;
		try
		{
			$creator	= new UI_Image_MedianBlur( __FILE__, "notexisting.txt" );
			$this->fail( 'An expected Exception has not been thrown.' );
		}
		catch( Exception $e )
		{
		}
	}
}
?>