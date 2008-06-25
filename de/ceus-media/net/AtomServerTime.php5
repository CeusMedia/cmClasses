<?php
import ("de.ceus-media.net.AtomTime");
import ("de.ceus-media.file.Reader");
import ("de.ceus-media.file.Writer");
/**
 *	Calculates real Time by Server time and synchronised Atom time.
 *	@package		net
 *	@uses			Net_AtomTime
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.07.2005
 *	@version		0.6
 */
/**
 *	Calculates real Time by Server time and synchronised Atom time.
 *	@package		net
 *	@uses			Net_AtomTime
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.07.2005
 *	@version		0.6
 */
class Net_AtomServerTime
{
	/**	@var		string		$syncFile		URI of File with synchronized atom time */
	protected $syncFile	= "";
	/**	@var		string		$syncTime		Timestamp of last synchronisation */
	protected $syncTime	= "";
	/**	@var		int			$syncDiff		Time difference between server time and atom time */
	protected $syncDiff	= 0;
	/**	@var		int			$refreshTime		Time distance in seconds for synchronisation update */
	protected $refreshTime	= 86400;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of File with synchronized atom time
	 *	@param		int			$refreshTime	Time distance in seconds for synchronisation update
	 *	@return		void
	 */
	public function __construct( $fileName = "AtomServerTime.diff", $refreshTime = 0)
	{
		$this->syncFile = $fileName;
		if( $refreshTime )
			$this->refreshTime = $refreshTime;
		$this->synchronize();
	}

	/**
	 *	Reads File with synchronized atom time difference.
	 *	@access		protected
	 *	@return		void
	 */
	protected function readSyncFile()
	{
		if( !file_exists( $this->syncFile ) )
			$this->synchronize();
		$ir = new File_INI_Reader ($this->syncFile, false);
		$data = $ir->getProperties (true);
		$this->syncTime	= $data['time'];
		$this->syncDiff	= $data['diff'];
	}

	/**
	 *	Synchronizes server time with atom time by saving time difference.
	 *	@access		protected
	 *	@return		void
	 */
	protected function synchronize()
	{
		if( file_exists( $this->syncFile ) )
		{
			$time	= filemtime( $this->syncFile );
			if( ( time() - $time ) < $this->refreshTime )
			{
				$this->syncTime	= $time;
				$this->syncDiff	= File_Reader::load( $this->syncFile );
				return;
			}
		}
		$this->syncTime	= time();
		$this->syncDiff	= $this->syncTime - Net_AtomTime::getTimestamp();
		File_Writer::save( $this->syncFile, $this->syncDiff );
		touch( $this->syncFile );
	}
	
	/**
	 *	Returns timestamp of last synchronisation.
	 *	@access		public
	 *	@return		int
	 */
	public function getSyncTime()
	{
		return $this->syncTime;
	}
	
	/**
	 *	Returns date of last synchronisation as formatted string.
	 *	@access		public
	 *	@param		string		$format			Date format
	 *	@return		string
	 */
	public function getSyncDate( $format = "d.m.Y - H:i:s" )
	{
		return date( $format, $this->syncTime );
	}
	
	/**
	 *	Returns time difference between server time and atom time.
	 *	@access		public
	 *	@return		int
	 */
	public function getSyncDiff()
	{
		return $this->syncDiff;
	}
	
	/**
	 *	Returns timestamp.
	 *	@access		public
	 *	@return		int
	 *	@link		http://www.php.net/time
	 */
	public function getTimestamp()
	{
		$time = time() + $this->syncDiff;
		return $time;
	}
	
	/**
	 *	Returns date as formatted string.
	 *	@access		public
	 *	@param		string		$format			Date format
	 *	@return		string
	 *	@link		http://www.php.net/date
	 */
	public function getDate ($format = "d.m.Y - H:i:s")
	{
		$time = time() + $this->syncDiff;
		return date( $format, $time );
	}
}
?>