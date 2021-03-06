<?php
/**
 *	Writing comma separatad values (CSV) data with or without column headers to File. 
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
 *	@package		File.CSV
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Writing comma separatad values (CSV) data with or without column headers to File. 
 *	@category		cmClasses
 *	@package		File.CSV
 *	@uses			File_Writer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class File_CSV_Writer
{
	/**	@var		string		$fileName		Flag: use ColumnHeaders in first line */
	protected $fileName;
	/**	@var		string		$separator		Separator Sign */
	protected $separator		= ",";
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File name of CSV File
	 *	@param		string		$separator		Separator sign
	 *	@return		void
	 */
	public function __construct( $fileName, $separator = NULL )
	{
		$this->fileName	= $fileName;
		if( $separator )
			$this->setSeparator( $separator );
	}

	/**
	 *	Sets separating Sign.
	 *	@access		public
	 *	@param		string		$separator		Separator sign
	 *	@return		void
	 */
	public function setSeparator( $separator )
	{
		$this->separator	= $separator;
	}

	/**
	 *	Saves an 2 dimensional array with or without column headers.
	 *	@access		public
	 *	@param		array		$data			2 dimensional array of data
	 *	@param		array		$headers		List of Column Headers
	 *	@return		bool
	 */
	public function write( $data, $headers = array() )
	{
		$output = array();
		if( $headers )
		{
			$output[] = implode( $this->separator, $headers );
		}
		foreach( $data as $line )
		{
			foreach( $line as $nr => $value )														//  iterate line values
				if( substr_count( $value, $this->separator ) > 0 )									//  separator found in value
					if( substr( $value, 0, 1 ).substr( $value, -1 ) != '""' )						//  value is not masked
						$line[$nr]	= '"'.addslashes( $value ).'"';									//  mask value
			$line = implode( $this->separator, $line );
			$output[] = $line;
		}
		$file	= new File_Writer( $this->fileName );
		return $file->writeArray( $output );
	}
}
?>