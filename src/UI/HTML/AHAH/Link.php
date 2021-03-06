<?php
/**
 *	Builder for Link Elements using AHAH.
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
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Builder for Link Elements using AHAH.
 *	@category		cmClasses
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class UI_HTML_AHAH_Link
{
	/**
	 *	Returns rendered Link Element.
	 *	@access		public
	 *	@param		string		$url		URL of Page to load
	 *	@param		string		$label		Link Label
	 *	@param		string		$targetId	ID of Fragment in Page
	 *	@param		string		$class		Class Attribute
	 *	@return		string
	 */
	public static function render( $url, $label, $targetId, $class = NULL )
	{
		$attributes	= array(
			'href'		=> "#".$targetId,
			'class'		=> $class,
			'onclick'	=> "ahah('".$url."','".$targetId."');",
		);
		return UI_HTML_Tag::create( 'a', $label, $attributes );
	}
}
?>