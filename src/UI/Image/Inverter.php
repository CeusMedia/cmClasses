<?php
/**
 *	Inverting Images.
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
 *	@since			16.12.2005
 *	@version		$Id$
 */
/**
 *	Inverting Images.
 *	@category		cmClasses
 *	@package		UI.Image
 *	@extends		UI_Image_Modifier
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			16.12.2005
 *	@version		$Id$
 *	@deprecated		user UI_Image_Filter instead
 *	@todo			to be removed in 0.7.7
 */
class UI_Image_Inverter extends UI_Image_Modifier
{	
	/**
	 *	Invertes Source Image.
	 *	@access		public
	 *	@return		bool
	 */
	public function invert( $type = NULL )
	{
		return Deprecation::notify( "Please use CMM_IMG_Filter:negate() from cmModules instead" );
		if( !$this->sourceUri )
			throw new RuntimeException( 'No source image set' );

		$this->target	= imagecreatetruecolor( $this->sourceInfo[0], $this->sourceInfo[1] );
		if( function_exists( 'imageantialias' ) )
			imageantialias( $this->target, TRUE );

		for( $x=0; $x<$this->sourceInfo[0]; $x++ )
		{
			for( $y=0; $y<$this->sourceInfo[1]; $y++ )
			{
				$color	= imagecolorat( $this->source, $x, $y );
				$color	= imagecolorsforindex( $this->source, $color );
				$color['red']	= 255 - $color['red'];
				$color['green']	= 255 - $color['green'];
				$color['blue']	= 255 - $color['blue'];
				$color	= imagecolorallocate( $this->target, $color['red'], $color['green'], $color['blue'] );
				imagesetpixel( $this->target, $x, $y, $color );
			}
		}
		if( $this->targetUri )
			return $this->saveImage( $type );
		return TRUE;
	}
}
?>