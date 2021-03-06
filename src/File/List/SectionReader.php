<?php
/**
 *	A Class for reading Section List Files.
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
 *	@uses			File_Reader
 *	@author			Chistian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	A Class for reading Section List Files.
 *	@category		cmClasses
 *	@package		File.List
 *	@uses			File_Reader
 *	@author			Chistian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class File_List_SectionReader
{
	protected $list	= array();
	public static $commentPattern	= '/^[#|-|*|:|;]/';
	public static $sectionPattern	= '/^\[([a-z0-9_=.,:;# ])+\]$/i';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of sectioned List
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->list	= self::load( $fileName );
	}
	
	/**
	 *	Reads the List.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		File Name of sectioned List
	 *	@return		array
	 */
	public static function load( $fileName )
	{
		if( !file_exists( $fileName ) )
			throw new Exception( 'File "'.$fileName.'" is not existing.' );

		$reader	= new File_Reader( $fileName );
		$lines	= $reader->readArray();
		
		$list	= array();
		foreach( $lines as $line )
		{
			$line = trim( $line );
			if( !$line )
				continue;
			if( preg_match( self::$commentPattern, $line ) )
				continue;
					
			if( preg_match( self::$sectionPattern, $line ) )
			{
				$section = substr( $line, 1, -1 );
				if( !isset( $list[$section] ) )
					$list[$section]	= array();
			}
			else if( $section )
				$list[$section][]	= $line;
		}
		return $list;
	}

	public function read()
	{
		return $this->list;
	}
}
?>