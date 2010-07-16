<?php
/**
 *	Builder for HTML Tags.
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
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			22.04.2008
 *	@version		$Id$
 */
/**
 *	Builder for HTML Tags.
 *	@category		cmClasses
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			22.04.2008
 *	@version		$Id$
 */
class UI_HTML_Tag
{
	/**	@var		array		$attributes		Attributes of Tag */
	protected $attributes		= array();
	/**	@var		string		$name			Name of Tag */
	protected $name;
	/**	@var		array		$content		Content of Tag */
	protected $content;

	public static $shortTagExcludes	= array(
		'style',
		'script',
		'div'
	);

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$name			Name of Tag
	 *	@param		string		$content		Content of Tag
	 *	@param		array		$attributes		Attributes of Tag
	 *	@return		void
	 */
	public function __construct( $name, $content = NULL, $attributes = array() )
	{
		if( !is_array( $attributes ) )
			throw new InvalidArgumentException( 'Parameter "attributes" must be an Array.' );
		$this->name		= $name;
		$this->setContent( $content );
		if( is_array( $attributes ) && count( $attributes ) )
			foreach( $attributes as $key => $value )
				$this->setAttribute( $key, $value );
	}
	
	/**
	 *	Builds HTML Tags as String.
	 *	@access		public
	 *	@return		string
	 */
	public function build()
	{
		return $this->create( $this->name, $this->content, $this->attributes );
	}

	/**
	 *	Creates Tag statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$name			Name of Tag
	 *	@param		string		$content		Content of Tag
	 *	@param		array		$attributes		Attributes of Tag
	 *	@return		void
	 */
	public static function create( $name, $content = NULL, $attributes = array() )
	{
		$name		= strtolower( $name );
		try{
			$attributes	= self::renderAttributes( $attributes );

		}
		catch( InvalidArgumentException $e ) {
			throw new RuntimeException( 'Invalid attributes', NULL, $e );
		}
		if( $content == NULL || $content == FALSE )													//  no node content defined, not even an empty string
			if( !in_array( $name, self::$shortTagExcludes ) )										//  node name is allowed to be a short tag
				return "<".$name.$attributes."/>";													//  build and return short tag
		return "<".$name.$attributes.">".$content."</".$name.">";									//  build and return full tag
	}

	protected static function renderAttributes( $attributes = array(), $allowOverride = FALSE )
	{
		if( !is_array( $attributes ) )
			throw new InvalidArgumentException( 'Parameter "attributes" must be an Array.' );
		$list	= array();
		foreach( $attributes as $key => $value )
		{
			if( empty( $key ) )																		//  no valid attribute key defined
				continue;																			//  skip this pair
			$key	= strtolower( $key );
			if( array_key_exists( $key, $list ) && !$allowOverride )								//  attribute is already defined
				throw new InvalidArgumentException( 'Attribute "'.$key.'" is already set' );		//  throw exception
			if( !is_string( $value ) )																//  attribute value has is not even a string
				continue;																			//  skip this pair
			if( !preg_match( '/^[a-z0-9:_-]+$/', $key ) )
				throw new InvalidArgumentException( 'Invalid attribute key' );
			if( preg_match( '/[^\\\]"/', $value ) )
				throw new InvalidArgumentException( 'Invalid attribute value' );
			$list[$key]	= strtolower( $key ).'="'.$value.'"';
		}
		$list	= implode( " ", $list );
		if( $list )
			$list	= " ".$list;
		return $list;
	}

	/**
	 *	Sets Attribute of Tag.
	 *	@access		public
	 *	@param		string		$key			Key of Attribute
	 *	@param		string		$value			Value of Attribute
	 *	@return		void
	 */
	public function setAttribute( $key, $value = NULL, $strict = TRUE )
	{
		if( empty( $key ) )																			//  no valid attribute key defined
			throw new InvalidArgumentException( 'Key must have content' );							//  throw exception
		$key	= strtolower( $key );
		if( array_key_exists( $key, $this->attributes ) && $strict )								//  attribute key already has a value
			throw new RuntimeException( 'Attribute "'.$key.'" is already set' );			//  throw exception
		if( !preg_match( '/^[a-z0-9:_-]+$/', $key ) )
			throw new InvalidArgumentException( 'Invalid key' );

		if( $value == NULL || $value == FALSE )														//  no value available
			unset( $this->attributes[$key] );														//  remove attribute
		else
		{
			if( preg_match( '/[^\\\]"/', $value ) )													//  detect injection
				throw new InvalidArgumentException( 'Invalid attribute value' );					//  throw exception
			$this->attributes[$key]	= $value;														//  set attribute
		}
	}
	
	/**
	 *	Sets Content of Tag.
	 *	@access		public
	 *	@param		string		$content		Content of Tag
	 *	@return		void
	 */
	public function setContent( $content = NULL )
	{
		$this->content	= $content;
	}

	/**
	 *	String Representation.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString()
	{
		return $this->create( $this->name, $this->content, $this->attributes );
	}
}
?>