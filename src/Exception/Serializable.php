<?php
/**
 *	Base Exception which can be serialized e.G. for NetServices.
 *
 *	Copyright (c) 2011-2012 Christian Würker (ceusmedia.com)
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
 *	@package		Exception
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			15.09.2011
 *	@version		0.1
 *	@see			http://fabien.potencier.org/article/9/php-serialization-stack-traces-and-exceptions
 */
/**
 *	Base Exception which can be serialized e.G. for NetServices.
 *	@category		cmClasses
 *	@package		Exception
 *	@extends		Exception
 *	@implements		Serializable
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			15.09.2011
 *	@version		0.1
 */
class Exception_Serializable extends Exception implements Serializable
{
	/**
	 *	Returns serial of exception.
	 *	@access		public
	 *	@return		string
	 */
	public function serialize()
	{
		return serialize( array( $this->message, $this->code, $this->file, $this->line ) );
	}

	/**
	 *	Recreates an exception from its serial.
	 *	@access		public
	 *	@param		string		$serial			Serial string of an serialized exception
	 *	@return		void
	 */
	public function unserialize( $serial )
	{
		list( $this->message, $this->code, $this->file, $this->line ) = unserialize( $serial );
	}
}
?>