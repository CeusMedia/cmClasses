<?php
/**
 *	Converts a String between Encodings using ICONV.
 *
 *	Copyright (c) 2010-2012 Christian Würker (ceusmedia.com)
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
 *	@package		Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
/**
 *	Converts a String between Encodings using ICONV.
 *	@category		cmClasses
 *	@package		Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.8
 *	@version		$Id$
 */
class Alg_Text_EncodingConverter
{
	/**
	 *	@access		public
	 *	@static
	 *	@param		string		String to be converted
	 *	@param		string		$charsetIn		Charset to convert from
	 *	@param		string		$charsetOut		Charset to convert to
	 *	@return		string
	 */
	public static function convert( $string, $charsetIn, $charsetOut )
	{
		self::checkIconv();
		ob_start();
		$string	= iconv( $charsetIn, $charsetOut, $string );
		$buffer = ob_get_clean();
		if( !$buffer )
			return $string;
		throw new InvalidArgumentException( 'String cannot be converted from '.$charsetIn.' to '.$charsetOut );
	}

	/**
	 *	Checks whether PHP Module 'iconv' is installed or not.
	 *	@access		protected
	 *	@return		void
	 *	@throws 	RuntimeException			if Module is not installed.
	 */
	protected static function checkIconv()
	{
		if( !function_exists( 'iconv' ) )
			throw new RuntimeException( 'PHP module "iconv" is not installed' );
	}
}
?>