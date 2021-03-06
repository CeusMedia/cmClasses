<?php
/**
 *	Client for FTP Connections.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		Net.FTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.07.2008
 *	@version		$Id$
 */
/**
 *	Client for FTP Connections.
 *	@category		cmClasses
 *	@package		Net.FTP
 *	@uses			Net_FTP_Connection
 *	@uses			Net_FTP_Reader
 *	@uses			Net_FTP_Writer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.07.2008
 *	@version		$Id$
 */
class Net_FTP_Client
{
	/**	@var		Net_FTP_Connection	$connection		FTP Connection Object */
	protected $connection;
	/**	@var		Net_FTP_Reader		$reader			FTP Reader Object */
	protected $reader;
	/**	@var		Net_FTP_Writer		$writer			FTP Writer Object */
	protected $writer;

	/**
	 *	Constructor, opens FTP Connection.
	 *	@access		public
	 *	@param		string		$host			Host Name
	 *	@param		integer		$port			Service Port
	 *	@param		string		$username		Username
	 *	@param		string		$password		Password
	 *	@return		void
	 */
	public function __construct( $host, $port = 21, $path = NULL, $username = NULL, $password = NULL )
	{
		try
		{
			$port	= $port ? $port : 21;
			$this->connection	= new Net_FTP_Connection( $host, $port );
			$this->connection->checkConnection( TRUE, FALSE );
			if( $username && $password )
				$this->connection->login( $username, $password );
			$this->connection->checkConnection();
			if( $path )
				if( !$this->connection->setPath( $path ) )
					throw new InvalidArgumentException( 'Path "'.$path.'" not existing' );
			$this->reader		= new Net_FTP_Reader( $this->connection );
			$this->writer		= new Net_FTP_Writer( $this->connection );
		}
		catch( Exception $e )
		{
			if( version_compare( PHP_VERSION, '5.3.0', '>=' ) )
				throw new RuntimeException( 'FTP connection failed ', 0, $e );						//  throw exception and transport inner exception
			throw new RuntimeException( 'FTP connection failed ', 0 );								//  throw exception
		}
	}

	/**
	 *	Destructor, closes FTP Connection.
	 *	@access		public
	 *	@return		void
	 */
	public function __destruct()
	{
		$this->connection->close( TRUE );
	}

	/**
	 *	Changes Rights of File or Folders on FTP Server.
	 *	@access		public
	 *	@param		string		$fileName		Name of File to change Rights for
	 *	@param		int			$mode			Mode of Rights (i.e. 755)	
	 *	@return		bool
	 */
	public function changeRights( $fileName, $mode )
	{
		return $this->writer->changeRights( $fileName, $mode );
	}
	
	/**
	 *	Copies a File on FTP Server.
	 *	@access		public
	 *	@param		string		$from			Path of source file
	 *	@param		string		$to				Path of target file
	 *	@return		bool
	 */
	public function copyFile( $from, $to )
	{
		return $this->writer->copyFile( $from, $to );
	}
	
	/**
	 *	Copies a Folder on FTP Server [recursive].
	 *	@access		public
	 *	@param		string		$from			Path of source file
	 *	@param		string		$to				Path of target file
	 *	@return		bool
	 */
	public function copyFolder( $from, $to )
	{
		return $this->writer->copyFolder( $from, $to );
	}
	
	/**
	 *	Creates a Folder on FTP Server.
	 *	@access		public
	 *	@param		string		$folderName		Path of folder to be created
	 *	@return		bool
	 */
	public function createFolder( $folderName )
	{
		return $this->writer->createFolder( $folderName );
	}

	/**
	 *	Transferes a File from FTP Server.
	 *	@access		public
	 *	@param		string		$globalFile		Path of remote file
	 *	@param		string		$localFile		Path of local target file
	 *	@return		bool
	 */
	public function getFile( $globalFile, $localFile = "" )
	{
		return $this->reader->getFile( $globalFile, $localFile );
	}

	/**
	 *	Returns Array of Files in Path [and nested Folders].
	 *	@access		public
	 *	@param		string		$path			Path
	 *	@param		bool		$recursive		Scan Folders recursive (default: FALSE)
	 *	@return		array
	 */
	public function getFileList( $path = "", $recursive = FALSE )
	{
		return $this->reader->getFileList( $path, $recursive );
	}
	
	/**
	 *	Returns Array of Folders in Path [and nested Folders].
	 *	@access		public
	 *	@param		string		$path			Path
	 *	@param		bool		$recursive		Scan Folders recursive (default: false)
	 *	@return		array
	 */
	public function getFolderList( $path = "", $recursive = FALSE )
	{
		return $this->reader->getFolderList( $path, $recursive );
	}
	
	/**
	 *	Returns a List of all Folders an Files of a Path on FTP Server.
	 *	@access		public
	 *	@param		string		$path			Path
	 *	@param		bool		$recursive		Scan Folders recursive (default: FALSE)
	 *	@return		array
	 */
	public function getList( $path = "", $recursive = FALSE )
	{
		return $this->reader->getList( $path, $recursive );
	}
	
	/**
	 *	Returns current Path on Server.
	 *	@access		public
	 *	@return		string
	 */
	public function getPath()
	{
		return $this->connection->getPath();
	}

	public function getPermissionsAsOctal( $permissions )
	{
		return $this->reader->getPermissionsAsOctal( $permissions );
	}

	public function getResource(){
		return $this->connection->getResource();
	}

	public function isConnected(){
		return $this->connection->checkConnection( TRUE );
	}
	/**
	 *	Copies a File on FTP Server.
	 *	@access		public
	 *	@param		string		$from			Name of Source File
	 *	@param		string		$to				Name of Target File
	 *	@return		bool
	 */
	public function moveFile( $from, $to )
	{
		return $this->writer->moveFile( $from, $to );
	}
	
	/**
	 *	Copies a Folder on FTP Server [recursive].
	 *	@access		public
	 *	@param		string		$from			Name of Source File
	 *	@param		string		$to				Name of Target File
	 *	@return		bool
	 */
	public function moveFolder( $from, $to )
	{
		return $this->writer->moveFolder( $from, $to );
	}
	
	/**
	 *	Transferes a File onto FTP Server.
	 *	@access		public
	 *	@param		string		$localFile		Name of Local File
	 *	@param		string		$globalFile		Name of Target File
	 *	@return		bool
	 */
	public function putFile( $localFile, $globalFile )
	{
		return $this->writer->putFile( $localFile, $globalFile );
	}
	
	/**
	 *	Removes a File.
	 *	@access		public
	 *	@param		string		$fileName		Name of File to be removed
	 *	@return		bool
	 */
	public function removeFile( $fileName )
	{
		return $this->writer->removeFile( $fileName );
	}
	
	/**
	 *	Removes a Folder.
	 *	@access		public
	 *	@param		string		$folderName		Name of Folder to be removed
	 *	@return		bool
	 */
	public function removeFolder( $folderName )
	{
		return $this->writer->removeFolder( $folderName );
	}

	/**
	 *	Renames a File on FTP Server.
	 *	@access		public
	 *	@param		string		$from			Name of Source File
	 *	@param		string		$to				Name of Target File
	 *	@return		bool
	 */
	public function renameFile( $from, $to )
	{
		return $this->writer->renameFile( $from, $to );
	}

	/**
	 *	Searchs for File in Path [and nested Folders] [with regular Expression].
	 *	@access		public
	 *	@param		string		$fileName			Name of File to find
	 *	@param		bool		$recursive			Scan Folders recursive (default: FALSE)
	 *	@param		bool		$regular			Search with regular Expression (default: FALSE)
	 *	@return		array
	 */
	public function searchFile( $fileName = "", $recursive = FALSE, $regular = FALSE )
	{
		return $this->reader->searchFile( $fileName, $recursive, $regular );
	}

	/**
	 *	Searchs for Folder in Path [and nested Folders] [with regular Expression].
	 *	@access		public
	 *	@param		string		$folderName			Name of Folder to find
	 *	@param		bool		$recursive			Scan Folders recursive (default: FALSE)
	 *	@param		bool		$regular			Search with regular Expression (default: FALSE)
	 *	@return		array
	 */
	public function searchFolder( $folderName = "", $recursive = FALSE, $regular = FALSE )
	{
		return $this->reader->searchFolder( $folderName, $recursive, $regular );
	}

	/**
	 *	Sets current Path on Server.
	 *	@access		public
	 *	@param		string		$path		Path to go to
	 *	@return		bool
	 */
	public function setPath( $path )
	{
		return $this->connection->setPath( $path );
	}
}
?>