<?php
/**
 *	Creates and displays Error Image with Message.
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
 *	@package		UI.Image
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.06.2008
 *	@version		$Id$
 */
/**
 *	Creates and displays Error Image with Message.
 *	@category		cmClasses
 *	@package		UI.Image
 *	@uses			UI_Image_Creator
 *	@uses			UI_Image_Drawer
 *	@uses			UI_Image_Printer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.06.2008
 *	@version		$Id$
 */
class UI_Image_Error
{
	/**	 @var		int			$borderWidth	Width of Border around Image */
	static public $borderWidth	= 0;
	/**	 @var		bool		$sendHeader		Send Header with Image MIME Type */
	static public $sendHeader	= TRUE;

	/**
	 *	Constructor, display Error Image.
	 *	@access		public
	 *	@param		string		$message		Error Message
	 *	@param		int			$width			Image Width
	 *	@param		int			$height			Image Height
	 *	@param		int			$posX			X Position of Message
	 *	@param		int			$posY			Y Position of Message
	 *	@return		void
	 */
	public function __construct( $message, $width = 200, $height = 20, $posX = 5, $posY = 3 )
	{
		$image	= new UI_Image_Creator();
		$image->create( $width, $height );
		$image	= new UI_Image_Drawer( $image->getResource() );
		$color	= $image->getColor( 255, 0, 0 );
		$image->drawBorder( $color, self::$borderWidth );
		$image->drawString( $posX, $posY, $message, 3, $color ); 
		UI_Image_Printer::showImage( $image->getImage(), IMAGETYPE_PNG, 100, self::$sendHeader );
	}
}
?>