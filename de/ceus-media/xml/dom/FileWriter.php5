<?php
import( 'de.ceus-media.file.Writer' );
import( 'de.ceus-media.xml.dom.Builder' );
/**
 *	Writes XML Files from Trees build with XML_DOM_Nodes.
 *
 *	Copyright (c) 2008 Christian W�rker (ceus-media.de)
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
 *	@package		xml.dom
 *	@uses			XML_DOM_Builder
 *	@uses			File_Writer
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
/**
 *	Writes XML Files from Trees build with XML_DOM_Nodes.
 *	@package		xml.dom
 *	@uses			XML_DOM_Builder
 *	@uses			File_Writer
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class XML_DOM_FileWriter
{
	/**	@var		string			$fileName		URI of XML File */
	protected $fileName;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$fileName		URI of XML File
	 *	@return		void
	 */
	public function __construct( $fileName )
	{
		$this->fileName	= $fileName;
	}

	/**
	 *	Writes XML Tree into XML File.
	 *	@access		public
	 *	@param		string			$fileName		URI of XML File
	 *	@param		XML_DOM_Node	$tree			XML Tree
	 *	@param		string			$encoding		Encoding Type
	 *	@return		bool
	 */
	public function write( $tree, $encoding = "utf-8" )
	{
		return self::save( $this->fileName, $tree, $encoding );
	}

	/**
	 *	Writes XML Tree into XML File.
	 *	@access		public
	 *	@param		string			$fileName		URI of XML File
	 *	@param		XML_DOM_Node	$tree			XML Tree
	 *	@param		string			$encoding		Encoding Type
	 *	@return		bool
	 */
	public static function save( $fileName, $tree, $encoding = "utf-8" )
	{
		$builder	= new XML_DOM_Builder();
		$xml		= $builder->build( $tree, $encoding );
		return File_Writer::save( $fileName, $xml );
	}
}
?>