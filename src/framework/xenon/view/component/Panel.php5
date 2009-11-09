<?php
/**
 *	Base Class for Panels.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		framework.xenon.view.component
 *	@extends		Framework_Xenon_Core_DefinitionView
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.03.2007
 *	@version		0.1
 */
import( 'de.ceus-media.framework.xenon.core.DefinitionView' );
/**
 *	Base Class for Panels.
 *	@category		cmClasses
 *	@package		framework.xenon.view.component
 *	@abstract
 *	@extends		Framework_Xenon_Core_DefinitionView
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.03.2007
 *	@version		0.1
 */
abstract class Framework_Xenon_View_Component_Panel extends Framework_Xenon_Core_DefinitionView
{
	/**
	 *	Build and return Content of Panel.
	 *	@abstract
	 *	@access		public
	 *	@return		string
	 */
	abstract public function getContent();
}
?>
