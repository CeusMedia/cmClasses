<?php
/**
 *	Vector.
 *
 *	Copyright (c) 2007-2010 Christian W�rker (ceus-media.de)
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
 *	@package		math.algebra
 *	@author			Christian W�rker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Vector.
 *	@category		cmClasses
 *	@package		math.algebra
 *	@author			Christian W�rker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class Math_Algebra_Vector implements ArrayAccess, Iterator
{
	/**	@var		int			$dimension		Dimension of the Vector */
	protected $dimension		= 0;
	/**	@var		array		$values			Value of the Vector */
	protected $values			= array();
	/**	@var		int			$position		Current Position of inner Iterator */
	protected $position			= 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$arguments = func_get_args();
		if( isset( $arguments[0] ) && is_array( $arguments[0] ) )
			$arguments = $arguments[0];
		if( !count( $arguments ) )
			throw new InvalidArgumentException( 'Vector needs at least 1 Value.' );
		foreach( $arguments as $argument )
			$this->addValue( $argument );
        $this->position = 0;
	}
	
	/**
	 *	Returns Vector as a representative string.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString()
	{
		$code = "(".implode( ", ", array_values( $this->values ) ).")";
		return $code;
	}
	
	/**
	 *	Adds a Value to Vector and increases Dimension
	 *	@access		public
	 *	@param		mixed		$value			Value to add
	 *	@return		void
	 */
	public function addValue( $value )
	{
		$value	= is_int( $value ) ? $value : (float) $value;
		$this->values[]	= $value;
		$this->dimension++;
	}
	
	/**
	 *	Returns the dimension of the Vector.
	 *	@access		public
	 *	@return		int
	 */
	public function getDimension()
	{
		return $this->dimension;
	}
	
	/**
	 *	Returns the value of a dimension.
	 *	@access		public
	 *	@param		int			$dimension		Dimension starting with 1
	 *	@return		mixed
	 */
	public function getValueFromDimension( $dimension )
	{
		return $this->getValueFromIndex( $dimension - 1 );
	}

	/**
	 *	Returns the value of a dimension starting with 0.
	 *	@access		public
	 *	@param		int			$index			Dimension starting with 0
	 *	@return		mixed
	 */
	public function getValueFromIndex( $index )
	{
		$dimension	= $this->getDimension();
		if( $index < 0 )
			throw new OutOfRangeException( 'Vector Index ('.$index.') must be greater than 0.' );
		if( $index >= $dimension )
			throw new OutOfRangeException( 'Vector Index ('.$index.') must be lower than Vector Dimension ('.$dimension.').' );
		return $this->values[$index];
	}

	public function offsetExists( $index )
	{
		if( $index < 0 )
			throw new OutOfRangeException( 'Vector Index ('.$index.') must be greater than 0.' );
		return isset( $this->values[$index] );
	}
	
	public function offsetGet( $index )
	{
		if( !$this->offsetExist( $index ) )
			throw new OutOfRangeException( 'Invalid Vector Index' );
		return $this->values[$index];
	}
	
	public function offsetSet( $index, $value )
	{
		if( !$this->offsetExist( $index ) )
			throw new OutOfRangeException( 'Invalid Vector Index' );
		$this->values[$index] = $value;
	}

	public function offsetUnset( $index )
	{
		if( !$this->offsetExist( $index ) )
			throw new OutOfRangeException( 'Invalid Vector Index' );
		$this->values[$index]	= 0;
	}

    public function current()
    {
        return $this->values[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function valid()
    {
		return isset( $this->values[$this->position] );
    }
	
	/**
	 *	Returns Vector as array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
	{
		return $this->values;
	}
}
?>