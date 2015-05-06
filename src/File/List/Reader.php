<?php
/**
 *	A Class for reading List Files.
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
 *	@package		File.List
 *	@author			Chistian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	A Class for reading List Files.
 *	@category		cmClasses
 *	@package		File.List
 *	@uses			File_Reader
 *	@author			Chistian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class File_List_Reader
{
	/**	@var		array		$list			List */	
	protected $list						= array();
	/**	@var		string		$commentPattern	RegEx Pattern of Comments */	
	protected static $commentPattern	= '/^[#:;\/*-]/';
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of List, absolute or relative URI
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->list	= $this->read( $fileName );
	}

	/**
	 *	Returns current List as String.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString()
	{
		return "{".implode( ", ", $this->list )."}";
	}
	
	/**
	 *	Returns the Index of a given Item in current List.
	 *	@access		public
	 *	@param		string		$item			Item to get Index for
	 *	@return		int
	 */
	public function getIndex( $item )
	{
		$index	= array_search( $item, $this->list );
		if( $index === FALSE )
			throw new DomainException( 'Item "'.$item.'" is not in List.' );
		return $index;
	}

	/**
	 *	Returns current List.
	 *	@access		public
	 *	@return		void
	 */
	public function getList()
	{
		return $this->list;
	}
	
	/**
	 *	Returns the Size of current List.
	 *	@access		public
	 *	@return		void
	 */
	public function getSize()
	{
		return count( $this->list );
	}

	/**
	 *	Indicates whether an Item is in current List.
	 *	@access		public
	 *	@param		string		$item			Item to check
	 *	@return		bool
	 */
	public function hasItem( $item )
	{
		return in_array( $item, $this->list );	
	}

	/**
	 *	Reads List File.
	 *	@access		public
	 *	@static
	 *	@param		string	fileName		URI of list
	 *	@return		void
	 */
	public static function read( $fileName )
	{
		$list	= array();
		if( !file_exists( $fileName ) )
			throw new RuntimeException( 'File "'.$fileName.'" is not existing' );
		$reader	= new File_Reader( $fileName );
		$lines	= $reader->readArray();
		foreach( $lines as $line )
			if( $line = trim( $line ) )
				if( !preg_match( self::$commentPattern, $line ) )
					$list[]	= $line;
		return $list;
	}
}
?>