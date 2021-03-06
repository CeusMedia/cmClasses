<?php
/**
 *	Builder for HTML Form Elements.
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
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Builder for HTML Form Elements.
 *	@category		cmClasses
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Form extends UI_HTML_Abstract
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$action		Form Action URI
	 *	@param		string		$content	Form Content
	 *	@param		string		$attributes Map of more Attributes
	 *	@return		void
	 */
	public function __construct( $action, $content = NULL, $attributes = NULL )
	{
		if( !is_null( $action ) )
			$this->setAction( $action );
		if( !is_null( $content ) )
			$this->setContent( $content );
		if( !is_null( $attributes ) )
			$this->setAttributes( $attributes );
	}
	
	/**
	 *	Returns rendered Form Element.
	 *	@access		public
	 *	@return		string
	 */
	public function render()
	{
		$attributes	= $this->getAttributes();
		if( is_array( $attributes['action'] ) )
			$attributes['action']	= ADT_URL_Inference::buildStatic( $attributes['action'] );
		$content	= $this->renderInner( $this->content );
		return UI_HTML_Tag::create( "form", $content, $attributes );
	}

	/**
	 *	Sets Form Action URI.
	 *	@access		public
	 *	@param		string		$url		Form Action URL
	 *	@return		void
	 */
	public function setAction( $url )
	{
		$this->attributes['action']	= $url;	
	}
}
?>