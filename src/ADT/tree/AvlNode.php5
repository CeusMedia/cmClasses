<?php
/**
 *	AVL Tree.
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
 *	@package		adt.tree
 *	@extends		ADT_Tree_BalanceBinaryNode
 *	@author			Christian W�rker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
import ("de.ceus-media.adt.tree.BalanceBinaryNode");
/**
 *	AVL Tree.
 *	@category		cmClasses
 *	@package		adt.tree
 *	@extends		ADT_Tree_BalanceBinaryNode
 *	@author			Christian W�rker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class ADT_Tree_AvlNode extends ADT_Tree_BalanceBinaryNode
{
	/**
	 *	Constructor
	 *	@access		public
	 *	@param		mixed	value	Value of Root Element
	 *	@return		void
	 */
	public function __construct( $value = FALSE )
	{
		parent::__construct( 2, $value );
	}
}
?>