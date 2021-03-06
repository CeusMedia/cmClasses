<?php
/**
 *	Connects Server to request Atom Time.
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
 *	@package		Net
 *	@uses			Net_CURL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			13.07.2005
 *	@version		$Id$
 */
/**
 *	Connects Server to request Atom Time.
 *	@category		cmClasses
 *	@package		Net
 *	@uses			Net_CURL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			13.07.2005
 *	@version		$Id$
 */
class Net_AtomTime
{
	/**	@var		string		$url			URL for Server Request */
	protected static $url		= "http://www.uni-leipzig.de/cgi-bin/date/index.htm";

	/**
	 *	Returns timestamp.
	 *	@access		public
	 *	@static
	 *	@return		int
	 *	@link		http://www.php.net/time
	 */
	public static function getTimestamp()
	{
		$curl	= new Net_CURL( self::$url );
		$result	= $curl->exec();
		$status	= $curl->getStatus();
		if( $status['http_code'] != 200 )
			throw new Exception( "Service URL is not reachable." );
		$parts	= explode( "\n", $result );
		$date	= trim( $parts[2] );
		$time	= strtotime( $date );
		return $time;
	}
	
	/**
	 *	Returns date as formatted string.
	 *	@access		public
	 *	@static
	 *	@param		string		$format			Date format
	 *	@return		string
	 *	@link		http://www.php.net/date
	 */
	public static function getDate( $format = "d.m.Y - H:i:s" )
	{
		return date( $format, self::getTimestamp() );
	}
}
?>