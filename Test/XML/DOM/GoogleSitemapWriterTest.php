<?php
/**
 *	TestUnit of Google Sitemap Builder.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Google Sitemap Builder.
 *	@package		Tests.xml.dom
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_DOM_GoogleSitemapWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.02.2008
 *	@version		0.1
 */
class Test_XML_DOM_GoogleSitemapWriterTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Sets up Builder.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->xmlFile	= dirname( __FILE__ ).'/sitemap.xml';
		$this->testFile	= dirname( __FILE__ ).'/test.xml';
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWrite()
	{
		$builder	= new XML_DOM_GoogleSitemapWriter();
		$builder->addLink( "test1.html" );
		$builder->addLink( "test2.html" );
		
		$builder->write( $this->testFile, "http://www.example.com/" );
		
		$assertion	= file_get_contents( $this->xmlFile );
		$creation	= file_get_contents( $this->testFile );
		$this->assertEquals( $assertion, $creation );
		@unlink( $this->testFile );
	}

	/**
	 *	Tests Method 'buildSitemap'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWriteSitemap()
	{
		$links	= array(
			"test1.html",
			"test2.html",
		);
		
		XML_DOM_GoogleSitemapWriter::writeSitemap( $links, $this->testFile, "http://www.example.com/" );
		
		$assertion	= file_get_contents( $this->xmlFile );
		$creation	= file_get_contents( $this->testFile );
		$this->assertEquals( $assertion, $creation );
		@unlink( $this->testFile );
	}
}
?>