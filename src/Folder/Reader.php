<?php
/**
 *	Reader for Folders.
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
 *	@package		Folder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Reader for Folders.
 *	@category		cmClasses
 *	@package		Folder
 *	@uses			Folder_Lister
 *	@uses			Folder_RecursiveLister
 *	@uses			Alg_UnitFormater
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 *	@todo			implement getFileTree, getFolderTree, getTree
 */
class Folder_Reader
{
	/**	@var		string		$folderName		Folder Name, relative or absolute */
	protected $folderName;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$folderName		Folder Name, relative or absolute
	 *	@return		void
	 */
	public function __construct( $folderName )
	{
		$this->folderName = $folderName;
	}

	/**
	 *	Adds a Slash at the End of a Path if missing.
	 *	@access		public
	 *	@static
	 *	@param		string		$path			Path to correct
	 *	@return		string
	 */
	public static function correctPath( $path )
	{
		return preg_replace( "@([^/])$@", "\\1/", $path );
	}

	/**
	 *	Indicates whether current Folder is existing.
	 *	@access		public
	 *	@return		bool
	 */
	public function exists()
	{
		return $this->isFolder( $this->folderName );
	}
	
	/**
	 *	Returns Number of Files and Folders within current Folder.
	 *	@access		public
	 *	@param		string		$pattern		RegEx Pattern for Name Filter
	 *	@return		int
	 */
	public function getCount( $pattern = NULL )
	{
		$count	= 0;
		$list	= $this->getList( $pattern );
		foreach( $list as $entry )
			$count++;
		return $count;
	}

	/**
	 *	Returns Number of Files within current Folder.
	 *	@access		public
	 *	@param		string		$pattern		RegEx Pattern for Name Filter
	 *	@return		int
	 */
	public function getFileCount( $pattern = NULL )
	{
		$count	= 0;
		foreach( $this->getFileList( $pattern ) as $entry )
			$count++;
		return $count;
	}

	/**
	 *	Get List of Files within current Folder.
	 *	@access		public
	 *	@param		string		$pattern		Name Filter RegEx Pattern, eg. '@xml$@' for all Files ending with 'xml'
	 *	@return		FilterIterator
	 */
	public function getFileList( $pattern = NULL )
	{
		return Folder_Lister::getFileList( $this->folderName, $pattern );
	}
	
	/**
	 *	Get List of Files with specified Extensions within current Folder.
	 *	@access		public
	 *	@param		array		$extension		List of allowed Extensions
	 *	@return		FilterIterator
	 */
	public function getFileListByExtensions( $extensions )
	{
		$lister	= new Folder_Lister( $this->folderName );
		$lister->setExtensions( $extensions );
		$lister->showFolders( FALSE );
		return $lister->getList();
	}
	
	/**
	 *	Returns Number of Folders within current Folder.
	 *	@access		public
	 *	@param		string		$pattern		RegEx Pattern for Name Filter
	 *	@return		int
	 */
	public function getFolderCount( $pattern = NULL )
	{
		$count	= 0;
		foreach( $this->getFolderList( $pattern ) as $entry )
			$count++;
		return $count;
	}

	/**
	 *	Get List of Folders within current Folder.
	 *	@access		public
	 *	@param		string		$pattern		Name Filter RegEx Pattern, eg. @^a$@ for all Folders starting with 'a'
	 *	@return		FilterIterator
	 */
	public function getFolderList( $pattern = NULL )
	{
		return Folder_Lister::getFolderList( $this->folderName, $pattern );
	}
	
	/**
	 *	Returns given Folder Name, absolute or reative.
	 *	@access		public
	 *	@return		string
	 */
	public function getFolderName()
	{
		return $this->folderName;
	}

	/**
	 *	Get List of Folders and Files within current Folder.
	 *	@access		public
	 *	@param		string		$pattern		Name Filter RegEx Pattern, eg. @xml@ for all Entries containing 'xml'
	 *	@return		FilterIterator
	 */
	public function getList( $pattern = NULL )
	{
		return Folder_Lister::getMixedList( $this->folderName, $pattern );
	}

	/**
	 *	Returns Name of Folder.
	 *	@access		public
	 *	@return		string
	 */
	public function getName()
	{
		return basename( $this->folderName );
	}

	/**
	 *	Returns Number of all nested Files and Folders within current Folder.
	 *	@access		public
	 *	@param		string		$pattern		RegEx Pattern for Name Filter
	 *	@return		int
	 */
	public function getNestedCount( $pattern = NULL )
	{
		$count	= 0;
		foreach( $this->getNestedList( $pattern ) as $entry )
			$count++;
		return $count;
	}

	/**
	 *	Returns Number of all nested Files within current Folder.
	 *	@access		public
	 *	@param		string		$pattern		RegEx Pattern for Name Filter
	 *	@return		int
	 */
	public function getNestedFileCount( $pattern = NULL )
	{
		$count	= 0;
		foreach( $this->getNestedFileList( $pattern ) as $entry )
			$count++;
		return $count;
	}

	/**
	 *	Returns List of all nested Files within current Folder.
	 *	@access		public
	 *	@param		string		$pattern		RegEx Pattern for Name Filter
	 *	@return		FilterIterator
	 */
	public function getNestedFileList( $pattern = NULL )
	{
		return Folder_RecursiveLister::getFileList( $this->folderName, $pattern );
	}

	/**
	 *	Returns Number of all nested Folders within current Folder.
	 *	@access		public
	 *	@param		string		$pattern		RegEx Pattern for Name Filter
	 *	@return		int
	 */
	public function getNestedFolderCount( $pattern = NULL )
	{
		$count	= 0;
		foreach( $this->getNestedFolderList( $pattern ) as $entry )
			$count++;
		return $count;
	}

	/**
	 *	Returns List of all nested Folders within current Folder.
	 *	@access		public
	 *	@param		string		$pattern		RegEx Pattern for Name Filter
	 *	@return		FilterIterator
	 */
	public function getNestedFolderList( $pattern = NULL )
	{
		return Folder_RecursiveLister::getFolderList( $this->folderName, $pattern );
	}

	/**
	 *	Returns List of all nested Files and Folders within current Folder.
	 *	@access		public
	 *	@param		string		$pattern		RegEx Pattern for Name Filter
	 *	@return		FilterIterator
	 */
	public function getNestedList( $pattern = NULL)
	{
		return Folder_RecursiveLister::getMixedList( $this->folderName, $pattern );
	}

	/**
	 *	Returns Size of all nested Files and Folders within current Folder.
	 *	@access		public
	 *	@param		string		$pattern		RegEx Pattern for Name Filter
	 *	@param		int			$unit			Unit (SIZE_BYTE|SIZE_KILOBYTE|SIZE_MEGABYTE|SIZE_GIGABYTE)
	 *	@param		int			$precision		Precision of rounded Size (only if unit is set)
	 *	@return		int
	 */
	public function getNestedSize( $pattern = NULL, $unit = NULL, $precision = NULL )
	{
		$size	= 0;
		foreach( $this->getNestedFileList( $pattern ) as $entry )
			$size	+= $entry->getSize();
		if( $unit )
			$size	= Alg_UnitFormater::formatNumber( $size, $unit, $precision );
		return $size;
	}

	/**
	 *	Returns Path to Folder.
	 *	@access		public
	 *	@return		string
	 */
	public function getPath()
	{
		return dirname( $this->folderName )."/";
	}

	/**
	 *	Returns absolute Path to Folder.
	 *	@access		public
	 *	@return		string
	 */
	public function getRealPath()
	{
		$path	= realpath( $this->folderName );
		if( FALSE === $path )
			throw new RuntimeException( 'Folder "'.$this->folderName.'" is not existing and therefore has no Path' );
		return dirname( $path )."/";
	}

	/**
	 *	Returns Size of Files within current Folder.
	 *	@access		public
	 *	@param		string		$pattern		RegEx Pattern for Name Filter
	 *	@param		int			$unit			Unit (SIZE_BYTE|SIZE_KILOBYTE|SIZE_MEGABYTE|SIZE_GIGABYTE)
	 *	@param		int			$precision		Precision of rounded Size (only if unit is set)
	 *	@return		int
	 */
	public function getSize( $pattern = NULL, $unit = NULL, $precision = NULL )
	{
		$size	= 0;
		foreach( $this->getFileList( $pattern ) as $entry )
			$size	+= $entry->getSize();
		if( $unit )
			$size	= Alg_UnitFormater::formatBytes( $size, $precision );
		return $size;
	}

	/**
	 *	Indicates whether a Path is an existing Folder.
	 *	@access		public
	 *	@static
	 *	@param		string		$path			Path to check
	 *	@return		bool
	 */
	public static function isFolder( $path )
	{
		$exists	= file_exists( $path );
		$isDir	= is_dir( $path );
		return $exists && $isDir;
	}
}
?>