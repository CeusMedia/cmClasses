<?php
/**
 *	Reader for short Log Files.
 *	@package		file
 *	@subpackage		log
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.12.2006
 *	@version		0.1
 */
/**
 *	Reader for short Log Files.
 *	@package		file
 *	@subpackage		log
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			27.12.2006
 *	@version		0.1
 *	@todo			Prove File for Existence
 */
class ShortLogReader
{
	/*	@var		array		$data		Array of Data in Lines */
	protected $data	= false;
	/*	@var		bool		$open		Status: Log File is read */
	protected $open	= false;
	/*	@var		string		$patterns	Pattern Array filled with Logging Information */
	protected $patterns	= array();
	/**	@var		string		$uri		URI of Log File */
	protected $uri;
	

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$uri		URI of short Log File
	 *	@return		void
	 */
	public function __construct( $uri )
	{
		$this->uri	= $uri;
		$patterns	= array(
				'timestamp',
				'remote_addr',
				'request_uri',
				'http_referer',
				'http_user_agent'
				);
		$this->setPatterns( $patterns );
	}
	
	/**
	 *	Returns parsed Log Data as Array.
	 *	@access		public
	 *	@return		array
	 *	@version	0.1
	 */
	public function getData()
	{
		if( $this->open )
			return $this->data;
		trigger_error( "Log File not read", E_USER_ERROR );
		return array();
	}
	
	/**
	 *	Indicated whether Log File is already opened and read.
	 *	@access		protected
	 *	@return		bool
	 */
	protected function isOpen()
	{
		return $this->open;
	}

	/**
	 *	Reads Log File.
	 *	@access		public
	 *	@return		bool
	 */
	public function read()
	{
		if( $fcont = @file( $this->uri ) )
		{
			$this->data = array();
			foreach( $fcont as $line )
				$this->data[] = explode( "|", trim( $line ) );
			$this->open	= true;
			return true;
		}
		return false;
	}

	/**
	 *	Sets Pattern Array filled with Logging Information.
	 *
	 *	@access		public
	 *	@param		array		$array		Array of Patterns.
	 *	@return		void
	 */
	public function setPatterns( $array )
	{
		if( is_array( $array ) )
			$this->patterns	= $array;
	}
}
?>