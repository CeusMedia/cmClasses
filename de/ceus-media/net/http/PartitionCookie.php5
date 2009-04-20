<?php
/**
 *	Partitioned Cookie Management.
 *
 *	Copyright (c) 2007-2009 Christian W�rker (ceus-media.de)
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
 *	@package		net.http
 *	@author			Christian W�rker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			11.08.2005
 *	@version		0.6
 */
import( 'de.ceus-media.net.http.Cookie' );
/**
 *	Partitioned Cookie Management.
 *	@package		net.http
 *	@author			Christian W�rker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			11.08.2005
 *	@version		0.6
 */
class Net_HTTP_PartitionCookie extends Net_HTTP_Cookie
{
	/**	@var	array	$data			Cookie Data in Partition */
	protected $data;
	/**	@var	string	$partition		Name of Partition in Cookie */
	protected $partition;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct ( $partition )
	{
		$this->partition = $partition;
		$pairs	= array();
		if( isset( $_COOKIE[$partition] ) )
			$pairs = explode( "@", $_COOKIE[$partition] );
		foreach( $pairs as $pair )
		{
			if( trim( $pair ) )
			{
				$parts = explode( ":", $pair );
				$this->data[$parts[0]] = $parts[1];
			}
		}
	}

	/**
	 *	Returns a Cookie by its key.
	 *	@access		public
	 *	@param		string		$key		Key of Cookie
	 *	@return		mixed
	 */
	public function get( $key )
	{
		if( isset( $this->data[$key] ) )
			return $this->data[$key];
		return NULL;
	}
	
	/**
	 *	Returns all Cookies of this PartitionCookie.
	 *	@access		public
	 *	@return		array
	 */
	public function getAll()
	{
		return $this->data;
	}

	/**
	 *	Sets a Cookie to this PartitionCookie.
	 *	@access		public
	 *	@param		string		$key		Key of Cookie
	 *	@param		string		$value		Value of Cookie
	 *	@return		void
	 */
	public function set( $key, $value )
	{
		$this->data[$key] = $value;
		$this->saveCake();
	}

	/**
	 *	Saves PartitionCookie by sending to Browser.
	 *	@access		protected
	 *	@return		void
	 */
	protected function saveCake()
	{
		$cake	= array();
		foreach( $this->data as $key => $value )
		$cake[]	= $key.":".$value;
		$cake	= implode( "@", $cake );
		setCookie( $this->partition, $cake );
	}
		
	/**
	 *	Deletes a Cookie of this PartitionCookie.
	 *	@access		public
	 *	@param		string		$key		Key of Cookie
	 *	@return		void
	 */
	public function remove ($key )
	{
		if( isset( $this->data[$key] ) )
			unset( $this->data[$key] );	
		$this->saveCake();
	}
}
?>