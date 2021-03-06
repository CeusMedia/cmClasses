<?php
/**
 *	...
 *
 *	Copyright (c) 2008-2012 Christian Würker (ceusmedia.com)
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
 *	@package		ADT.PHP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@version		$Id$
 *	@since			0.3
 */
/**
 *	...
 *	@category		cmClasses
 *	@package		ADT.PHP
 *	@extends		ADT_PHP_Category
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@version		$Id$
 *	@since			0.3
 */
class ADT_PHP_Package extends ADT_PHP_Category
{
	protected $files	= array();

	/**
	 *	@deprecated	seems to be unused
	 */
	public function & getFileByName( $name )
	{
		if( isset( $this->files[$name] ) )
			return $this->files[$name];
		throw new RuntimeException( "File '$name' is unknown" );
	}
	
	public function getFiles()
	{
		return $this->files;
	}
	
	public function hasFiles()
	{
		return count( $this->files ) > 1;
	}
	
	public function setFile( $name, $file )
	{
		$this->files[$name]	= $file;
	}
}
?>