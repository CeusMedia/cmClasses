<?php
/**
 *	HTML Radio Input Tag with optional Label.
 *	@category		cmClasses
 *	@package		ui.html.element
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	HTML Radio Input Tag with optional Label.
 *	@category		cmClasses
 *	@package		ui.html.element
 *	@extends		UI_HTML_Element_Input_Text
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2009-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class UI_HTML_Element_Input_Radio extends UI_HTML_Element_Input_Text
{
	public function __construct( $name, $value = NULL )
	{
		$this->type	= 'radio';
		parent::__construct( $name, $value );
	}

	public function getValue()
	{
		return $this->value;
	}
	
	public function setChecked( $state = TRUE )
	{
		$this->checked = (bool) $state;
	}
}
?>