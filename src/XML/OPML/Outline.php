<?php
/**
 *	XML Node for OPML Outlines.
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
 *	@package		XML.OMPL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.02.2006
 *	@version		$Id$
 */
/**
 *	XML Node for OPML Outlines.
 *	@category		cmClasses
 *	@package		XML.OMPL
 *	@extends		XML_DOM_Node
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.02.2006
 *	@version		$Id$
 */
class XML_OPML_Outline extends XML_DOM_Node
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		parent::__construct( "outline" );
	}
	
	/**
	 *	Adds an Outline Node to this Outline Node.
	 *	@access		public
	 *	@param		XML_OPML_Outline	$outline		Outline Node
	 *	@return		void
	 */
	public function addOutline( $outline )
	{
		$this->addChild( $outline );
	}
}
?>