<?php
/**
 *	Reader for RSS 2.0 Feeds.
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
 *	@package		XML.RSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.02.2008
 *	@version		$Id$
 */
/**
 *	Reader for RSS 2.0 Feeds.
 *	@category		cmClasses
 *	@package		XML.RSS
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.02.2008
 *	@version		$Id$
 */
class XML_RSS_SimpleReader
{
	/**
	 *	Reads RSS from File.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName	File Name to XML RSS File
	 *	@return		array
	 */
	public static function readFile( $fileName )
	{
		$xml	= File_Reader::load( $fileName );
		return XML_RSS_SimpleParser::parse( $xml );
	}
	
	/**
	 *	Reads RSS from URL.
	 *	@access		public
	 *	@static
	 *	@param		string		$url		URL to read RSS from
	 *	@return		array
	 */
	public static function readUrl( $url )
	{
		$xml	= Net_Reader::readUrl( $url );
		return XML_RSS_SimpleParser::parse( $xml );
	}

	/**
	 *	Reads RSS from XML.
	 *	@access		public
	 *	@static
	 *	@param		string		$xml		XML String to read
	 *	@return		array
	 */
	public static function readXml( $xml )
	{
		return XML_RSS_SimpleParser::parse( $xml );
	}
}
?>