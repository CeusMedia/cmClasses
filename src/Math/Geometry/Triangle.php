<?php
/**
 *	...
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
 *	@package		Math.Geometry
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	...
 *	@category		cmClasses
 *	@package		Math.Geometry
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 *	@todo			Code Documentation
 */
class Math_Geometry_Triangle
{
	public function pythagoras( $a = NULL, $b = NULL, $c = NULL )
	{
		if( $a && $b && $c )
			throw new InvalidArgumentException( 'Needs 2 of 3 arguments' );
		if( $a && $b )
			return sqrt( pow( $a, 2 ) + pow( $b, 2 ) );
		if( $a && $c )
			return sqrt( pow( $c, 2 ) - pow( $a, 2 ) );
		if( $b && $c )
			return sqrt( pow( $c, 2 ) - pow( $b, 2 ) );
		throw new InvalidArgumentException( 'Needs 2 of 3 arguments' );
	}
}
?>