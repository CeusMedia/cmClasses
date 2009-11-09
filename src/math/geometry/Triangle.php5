<?php
/**
 *	...
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
 *	@category		cmClasses
 *	@package		math.geometry
 *	@author			Christian W�rker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
/**
 *	...
 *	@category		cmClasses
 *	@package		math.geometry
 *	@author			Christian W�rker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 *	@todo			Code Documentation
 */
class Math_Geometry_Triangle
{
	public function pythagoras( $a = false, $b = false, $c = false )
	{
		if( $a && $b )
		{
			$c = sqrt( pow( $a, 2 ) + pow( $b, 2 ) );
			return $c;
		}
		else if( $c )
		{
			if( $a )
			{
				$b = sqrt( pow( $c, 2 ) - pow( $a, 2 ) );
				return $b;
			}
			else if( $b )
			{
				$a = sqrt( pow( $c, 2 ) - pow( $b, 2 ) );
				return $a;
			}
		}
	}
}
?>