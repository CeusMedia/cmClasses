<?php
/**
 *	Builder for Data Source Name Strings.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		Database.PDO
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
/**
 *	Builder for Data Source Name Strings.
 *	@category		cmClasses
 *	@package		Database.PDO
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
class Database_PDO_DataSourceName
{
	/**	@var		string		$driver			Database Driver */
	protected $driver;
	/**	@var		string		$database		Database Name */
	protected $database;
	/**	@var		string		$username		Database Username */
	protected $username	;
	/**	@var		string		$password		Database Password */
	protected $password;
	/**	@var		string		$host			Host Name or URI*/
	protected $host;
	/**	@var		int			$port			Host Port */
	protected $port;

	protected $drivers	= array(
		'cubrid',
		'dblib',
		'firebird',
		'informix',
		'mssql',
		'mysql',
		'oci',
		'odbc',
		'pgsql',
		'sqlite',
		'sybase',
	);

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$driver			Database Driver (cubrid,dblib|firebird|informix|mysql|mssql|oci|odbc|pgsql|sqlite|sybase)
	 *	@param		string		$database		Database Name
	 *	@return		void
	 */
	public function __construct( $driver, $database = NULL )
	{
		$this->checkDriverSupport( $driver );
		$this->driver		= strtolower( $driver );
		if( $database )
			$this->setDatabase( $database );
	}

	/**
	 *	Converts DSN Object into a String.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 *	Checks whether current Driver is installed with PHP and supported by Class.
	 *	@access		protected
	 *	@param		string		$driver			Driver Name to check (lowercase)
	 *	@return		void
	 *	@throws		RuntimeException			if PDO Driver is not supported
	 *	@throws		RuntimeException			if PDO Driver is not loaded
	 */
	protected function checkDriverSupport( $driver )
	{
		if( !in_array( $driver, $this->drivers ) )
			throw new RuntimeException( 'PDO driver "'.$driver.'" is not supported' );
		if( !in_array( $driver, PDO::getAvailableDrivers() ) )
			throw new RuntimeException( 'PDO driver "'.$driver.'" is not loaded' );
	}

	/**
	 *	Returns set PDO driver.
	 *	@access		public
	 *	@return		string		Database Driver (cubrid,dblib|firebird|informix|mysql|mssql|oci|odbc|pgsql|sqlite|sybase)
	 */ 
	public function getDriver(){
		return $this->driver;
	}

	/**
	 *	Sets Database, a String or File URI.
	 *	@access		public
	 *	@param		string		$database		Database Name
	 *	@return		void
	 */
	public function setDatabase( $database )
	{
		$this->database	= $database;
	}

	/**
	 *	Sets Host Name or URI if Database Server is using HTTP.
	 *	@access		public
	 *	@param		string		$host 			Host Name or URI
	 *	@return		void
	 */
	public function setHost( $host )
	{
		$this->host	= $host;
	}

	/**
	 *	Sets Password.
	 *	@access		public
	 *	@param		string		$password		Password
	 *	@return		void
	 */
	public function setPassword( $password )
	{
		$this->password	= $password;
	}

	/**
	 *	Sets Port if Database Server is using HTTP.
	 *	@access		public
	 *	@param		int			$port			Host Port
	 *	@return		void
	 */
	public function setPort( $port )
	{
		$this->port	= $port;
	}

	/**
	 *	Sets Username.
	 *	@access		public
	 *	@param		string		$username		Username
	 *	@return		void
	 */
	public function setUsername( $username )
	{
		$this->username	= $username;
	}

	public function render()
	{
		$prefix	= $this->driver.':';
		switch( $this->driver )
		{
			case 'firebird':
				return $prefix.$this->renderDsnForFirebird();
			case 'informix':
				return $prefix.$this->renderDsnForInformix();
			case 'oci':
				return $prefix.$this->renderDsnForOci();
			case 'odbc':
				return $prefix.$this->renderDsnForOdbc();
			case 'pgsql':
				return $prefix.$this->renderDsnForPgsql();
			case 'sqlite':
				return $prefix.$this->renderDsnForSqlite();
			default:																				//  cubrid, dblib, mssql, mysql, sybase 
				return $prefix.$this->renderDsnForDefault();
		}
	}

	protected function renderDsnForDefault()
	{
		$port	= !empty( $this->port ) ? $this->port : NULL;
		$map	= array(
			'host'		=> $this->host,
			'port'		=> $port,
			'dbname'	=> $this->database,
		);
		return $this->renderDsnParts( $map );
	}

	protected function renderDsnForFirebird()
	{
		$host	= !empty( $this->host ) ? $this->host : NULL;
		$port	= !empty( $this->port ) ? $this->port : NULL;
		$map	= array(
			'DataSource'	=> $host,
			'Port'			=> $port,
			'Database'		=> $this->database,
			'User'			=> $this->username,
			'Password'		=> $this->password
		);
		return $this->renderDsnParts( $map );
	}

	protected function renderDsnForInformix()
	{
		$delim	= '; ';
		$host	= !empty( $this->host ) ? $this->host : NULL;
		$port	= !empty( $this->port ) ? $this->port : NULL;
		$map	= array(
			'host'		=> $host,
			'service'	=> $port,
			'database'	=> $this->database
		);
		return $this->renderDsnParts( $map, $delim );
	}

	/**
	 *	@todo	implement 'charset'
	 */
	protected function renderDsnForOci()
	{
		$dbname	= $this->database;
		$port	= $this->port ? ':'.$this->port : '';
		if( $this->host )
			$dbname	= '//'.$this->host.$port.'/'.$this->database;
		return 'dbname='.$dbname;
	}
			
	/**
	 *	@todo	implement
	 */
	protected function renderDsnForOdbc()
	{
		throw new Exception( 'Not yet implemented' );
	}

	protected function renderDsnForPgsql()
	{
		$delim	= ' ';
		$host	= !empty( $this->host ) ? $this->host : NULL;
		$port	= !empty( $this->port ) ? $this->port : NULL;
		$map	= array(
			'host'		=> $host,
			'port'		=> $port,
			'dbname'	=> $this->database,
			'user'		=> $this->username,
			'password'	=> $this->password
		);
		return $this->renderDsnParts( $map, $delim );
	}
		
	protected function renderDsnForSqlite()
	{
		return $this->database;
	}

	/**
	 *	Flattens Map of DSN Parts using a Delimiter.
	 *	@access		public
	 *	@param		array		$map			DSN Parts Map
	 *	@param		string		$delimiter		Delimiter between DSN Parts
	 *	@return		string
	 */
	protected function renderDsnParts( $map, $delimiter = ';' )
	{
		$list	= array();
		foreach( $map as $key => $value )
			if( !is_null( $value ) )
				$list[]	= $key.'='.$value;
		return implode( $delimiter, $list );
	}

	/**
	 *	Returns Data Source Name String.
	 *	@access		public
	 *	@static
	 *	@param		string		$driver			Database Driver (cubrid|dblib|firebird|informix|mysql|mssql|oci|odbc|pgsql|sqlite|sybase)
	 *	@param		string		$database		Database Name
	 *	@param		string		$host			Host Name or URI
	 *	@param		int			$port			Host Port
	 *	@param		string		$username		Username
	 *	@param		string		$password		Password
	 *	@return		string
	 */
	public static function renderStatic( $driver, $database, $host = NULL, $port = NULL, $username = NULL, $password = NULL )
	{
		$dsn	= new self( $driver, $database );
		$dsn->setHost( $host );
		$dsn->setPort( $port );
		$dsn->setUsername( $username );
		$dsn->setPassword( $password );
		return $dsn->render();
	}
}
?>
