<?php
/**
 *	Binary Tree.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		ADT.Tree
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Binary Tree.
 *	@category		cmClasses
 *	@package		ADT.Tree
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class ADT_Tree_BinaryNode
{
	/**	@var 	mixed		$value		Value of the Root Element of this Tree */
	protected $value		= NULL;
	/**	@var	ADT_Tree_BinaryNode		$left		Left Child Tree */
	protected $left			= NULL;
	/**	@var	ADT_Tree_BinaryNode		$right		Right Child Tree */
	protected $right		= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		mixed		$value		Value to be added to the Tree
	 *	@return		void
	 */
	public function __construct( $value = NULL )
	{
		if( $value )
			$this->add( $value );
	}

	/**
	 *	Adds a Node to the Tree.
	 *	@access		public
	 *	@param		mixed		$value		Value to be added to the Tree
	 *	@return		void
	 */
	public function add( $value )
	{
		if( !isset( $this->value ) )
			return $this->value = $value;
		if( $value == $this->value)
			return -1;
		if( $value < $this->value )
		{
			if( $this->left )
				$this->left->add( $value );
			else
				$this->left = new ADT_Tree_BinaryNode( $value );
		}
		else if( $value > $this->value )
		{
			if( $this->right )
				$this->right->add( $value );
			else
				$this->right = new ADT_Tree_BinaryNode( $value );
		}
	}
	
	/**
	 *	Returns the amount of Nodes in the Tree.
	 *	@access		public
	 *	@return		int
	 */
	public function countNodes()
	{
		$nodes = 1;
		if( $this->left || $this->right )
		{
			if( $this->left )
				$nodes += $this->left->countNodes();
			if( $this->right )
				$nodes += $this->right->countNodes();
		}
		return $nodes;
	}

	/**
	 *	Returns value of the Tree.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 *	Returns Left Child Tree.
	 *	@access		public
	 *	@return		ADT_Tree_BinaryNode
	 */
	public function getLeft()
	{
		if( $this->left === NULL )
			throw new Exception( 'No left Node available.' );
		return $this->left;
	}
	
	/**
	 *	Returns right Child Tree.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getRight()
	{
		if( $this->right === NULL )
			throw new Exception( 'No right Node available.' );
		return $this->right;
	}
	
	/**
	 *	Returns the height of the Tree.
	 *	@access		public
	 *	@return		int
	 */
	public function getHeight()
	{
		$left_height	= $this->left  ? $this->left->getHeight()  : 0;		//  Rekursiver Aufruf des linken Teilbaumes
		$right_height	= $this->right ? $this->right->getHeight() : 0;		//  Rekursiver Aufruf des rechten Teilbaumes
		$height = max( $left_height, $right_height ); 						//  Vergleichen welcher der beiden Teilbäume höher ist
		$height++;															//  Höhe hochzählen
		return $height;
	}

	/**
	 *	Indicates wheter a Value can be found in the Tree.
	 *	@access		public
	 *	@param		mixed		$value		Value to be found in the Tree
	 *	@return		void
	 */
	public function search( $value )
	{
		if( $value == $this->value )
			return $this;
		else if( $value < $this->value )
		{
			if( $this->left )
				return $this->left->search( $value );
		}
		else if( $value > $this->value )
		{
			if( $this->right )
				return $this->right->search( $value );
		}
		return NULL;
	}

	/**
	 *	Returns the Tree as HTML-Table.
	 *	@access		public
	 *	@return		string
	 */
	public function toTable()
	{
		$code = "<table cellspacing=1 cellpadding=0>\n<tr><td colspan=2 align=center><hr>".$this->value."</td></tr>";
		if( $this->left || $this->right )
		{
			$code .= "<tr><td align=center valign=top>";
			if( $this->left )
				$code .= $this->left->toTable();
			else
				$code .= "&nbsp;";
			$code .= "</td><td align=center valign=top>";
			if( $this->right )
				$code .= $this->right->toTable();
			else
				$code .= "&nbsp;";
			$code .= "</td></tr>\n";
		}
		$code .= "</table>\n";
		return $code;
	}

	/**
	 *	Runs through the Tree in any Directions and returns the Tree as List.
	 *	@access		public
	 *	@param		string		$dir		Direction to run through the Tree (lwr|rwl|wlr|wrl)
	 *	@return		array
	 */
	public function toList( $dir = NULL )
	{
		$array	= array();
		if( !$dir || $dir == "lwr" )
		{
			if( $this->left )
				$array = array_merge( $array, $this->left->toList( $dir ) );
			$array = array_merge( $array, array( $this->value ) );
			if( $this->right )
				$array = array_merge( $array, $this->right->toList( $dir ) );
		}
		else if( $dir == "rwl" )
		{
			if( $this->right )
				$array = array_merge( $array, $this->right->toList( $dir ) );
			$array = array_merge( $array, array ($this->value));
			if( $this->left)
				$array = array_merge( $array, $this->left->toList( $dir ) );
		}
		else if( $dir == "wlr" )
		{
			$array = array_merge( $array, array ($this->value));
			if( $this->left )
				$array = array_merge( $array, $this->left->toList( $dir ) );
			if( $this->right )
				$array = array_merge( $array, $this->right->toList( $dir ) );
		}
		else if( $dir == "wrl" )
		{
			$array = array_merge( $array, array ($this->value));
			if( $this->right )
				$array = array_merge( $array, $this->right->toList( $dir ) );
			if( $this->left )
				$array = array_merge( $array, $this->left->toList( $dir ) );
		}
		return $array;
	}
}
?>