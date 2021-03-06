<?php
/**
 *	Cache to store Data in Files statically.
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
 *	@package		File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			13.04.2009
 *	@version		$Id$
 */
/**
 *	Cache to store Data in Files statically.
 *	@category		cmClasses
 *	@package		File
 *	@extends		ADT_Cache_StaticStore
 *	@implements		Countable
 *	@uses			File_Cache
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			13.04.2009
 *	@version		$Id$
 */
class File_StaticCache extends ADT_Cache_StaticStore
{
	/**	@var		File_Cache	$store			Instance of File Cache */
	protected static $store			= NULL;

	/**
	 *	Removes all expired Cache Files.
	 *	@access		public
	 *	@static
	 *	@param		int			$expires		Cache File Lifetime in Seconds
	 *	@return		bool
	 */
	public static function cleanUp( $expires = 0 )
	{
		return self::$store->cleanUp( $expires );
	}

	/**
	 *	Counts all Elements in Cache.
	 *	@access		public
	 *	@static
	 *	@return		int
	 */
	public static function count()
	{
		return self::$store->count();
	}

	/**
	 *	Removes all Cache Files.
	 *	@access		public
	 *	@static
	 *	@return		bool
	 */
	public function flush()
	{
		return self::$store->flush();
	}

	/**
	 *	Returns a Value from Cache by its Key.
	 *	@access		public
	 *	@static
	 *	@param		string		$key			Key of Cache File
	 *	@return		mixed
	 */
	public static function get( $key )
	{
		return self::$store->get( $key );
	}

	/**
	 *	Indicates wheter a Value is in Cache by its Key.
	 *	@access		public
	 *	@static
	 *	@param		string		$key			Key of Cache File
	 *	@return		void
	 */
	public static function has( $key )
	{
		return self::$store->has( $key );
	}

	/**
	 *	Sets Path to Cache Files and time until Pairs will expire.
	 *	@access		public
	 *	@static
	 *	@param		string		$path			Path to Cache Files
	 *	@param		int			$expires		Seconds until Pairs will be expired
	 *	@return		void
	 */
	public static function init( $path, $expires = 0 )
	{
		self::$store	= new File_Cache( $path, $expires );
	}

	/**
	 *	Removes a Value from Cache by its Key.
	 *	@access		public
	 *	@static
	 *	@param		string		$key			Key of Cache File
	 *	@return		void
	 */
	public static function remove( $key )
	{
		return self::$store->remove( $key );
	}

	/**
	 *	Stores a Value in Cache by its Key.
	 *	@access		public
	 *	@static
	 *	@param		string		$key			Key of Cache File
	 *	@param		mixed		$value			Value to store
	 *	@return		void
	 */
	public static function set( $key, $value )
	{
		return self::$store->set( $key, $value );
	}
}
?>