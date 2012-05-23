<?php
/**
 *	Calculates artithmetic and geometric Average.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceusmedia.com)
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
 *	@package		Math
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			15.09.2006
 *	@version		$Id$
 */
/**
 *	Calculates artithmetic and geometric Average.
 *	@category		cmClasses
 *	@package		Math
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			15.09.2006
 *	@version		$Id$
 *	@todo			finish Implementation
 */
class Math_Average
{
	/**
	 *	Calculates artithmetic Average.
	 *	@access		public
	 *	@static
	 *	@param		array		$values			Array of Values.
	 *	@param		int			$accuracy		Accuracy of Result 
	 *	@return		float
	 */
	public static function arithmetic( $values, $accuracy = NULL )
	{
		$sum	= 0;
		foreach( $values as $value )
			$sum	+= $value;
		$result	= $sum / count( $values );
		if( $accuracy >= 0 )
			$result	= round( $result, $accuracy );
		return $result;
	}

	/**
	 *	Calculates geometric Average.
	 *	@access		public
	 *	@static
	 *	@param		array		$values			Array of Values
	 *	@param		int			$accuracy		Accuracy of Result 
	 *	@return		float
	 */
	public static function geometric( $values, $accuracy = NULL )
	{
		$product	= 1;
		foreach( $values as $value )
			$product	*= $value;
		$result	= pow( $product, 1 / count( $values ) );
		if( $accuracy >= 0 )
			$result	= round( $result, $accuracy );
		return $result;
	}
}
?>