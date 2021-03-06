<?php
/**
 *	Builds XHTML Page Frame containing Doctype, Meta Tags, Title, Title, JavaScripts, Stylesheets and additional Head and Body.
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
 *	@package		UI.HTML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2013 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Builds XHTML Page Frame containing Doctype, Meta Tags, Title, Title, JavaScripts, Stylesheets and additional Head and Body.
 *	@category		cmClasses
 *	@package		UI.HTML
 *	@uses			UI_HTML_Tag
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2013 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */ 
class UI_HTML_PageFrame
{
	protected $title		= NULL;
	protected $heading		= NULL;
	protected $styles		= array();
	protected $scripts		= array();
	protected $metaTags		= array();
	protected $baseHref		= NULL;
	protected $head			= array();
	protected $body			= array();
	protected $prefixes		= array();
	protected $profile		= NULL;
	public $indent			= "  ";
	protected $charset		= NULL;
	protected $language		= NULL;
	protected $doctype		= 'XHTML_10_STRICT';
	protected $doctypes		= array(
		'HTML_5'					=> '<!DOCTYPE html>',
		'XHTML_11'					=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">',
		'XHTML_10_STRICT'			=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">',
		'XHTML_10_TRANSITIONAL'		=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">',
		'XHTML_10_FRAMESET'			=> '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd">',
		'HTML_401_STRICT'			=> '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">',
		'HTML_401_TRANSITIONAL'		=> '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">',
		'HTML_401_FRAMESET'			=> '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">',
	);

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$docType		Document type key
	 *	@param		string		$language		Language of Page
	 *	@param		string		$charset		Default Charset Encoding
	 *	@param		string		$scriptType		Default JavaScript MIME-Type
	 *	@param		string		$styleType		Default Stylesheet MIME-Type
	 *	@return		void
	 */
	public function __construct( $docType = "XHTML_10_STRICT", $language = "en", $charset = "UTF-8", $scriptType = "text/javascript", $styleType = "text/css" )
	{
		$this->setDocType( $docType );
		$this->setLanguage( $language );
		$this->charset	= $charset;
		if( $docType == "HTML_5" ){
			$this->metaTags["charset"]	= array( 'charset' => $charset );
			$this->addMetaTag( "http-equiv", "Content-Type", "text/html" );
		}
		else{
			$this->addMetaTag( "http-equiv", "Content-Type", "text/html; charset=".strtoupper( $charset ) );
		}
		$this->addMetaTag( "http-equiv", "Content-Script-Type", $scriptType );
		$this->addMetaTag( "http-equiv", "Content-Style-Type", $styleType );
	}

	/** 
	 *	Adds further HTML to Body.
	 *	@access		public
	 *	@param		string		$string			HTML String for Head
	 *	@return		void
	 */
	public function addBody( $string )
	{
		$this->body[]	= $string;
	}

	/**
	 *	Adds a favourite Icon to the Page (supports ICO and other Formats).
	 *	@access		public
	 *	@param		string		$url			URL of Icon or Image 
	 *	@return		void
	 *	@since		0.6.7
	 */
	public function addFavouriteIcon( $url )
	{
		$styleData	= array(
			'rel'		=> "icon",
			'type'		=> "image/x-icon",
			'href'		=> $url,
		);
		$this->styles[]	= $styleData;
	}

	/** 
	 *	Adds further HTML to Head.
	 *	@access		public
	 *	@param		string		$string			HTML String for Head
	 *	@return		void
	 */
	public function addHead( $string )
	{
		$this->head[]	= $string;
	}

	/**
	 *	Adds a Java Script Link to Head.
	 *	@access		public
	 *	@param		string		$uri			URI to Script 
	 *	@param		string		$type			MIME Type of Script
	 *	@param		string		$charset		Charset of Script
	 *	@return		void
	 */
	public function addJavaScript( $uri, $type = NULL, $charset = NULL )
	{
		$typeDefault	= 'text/javascript';
		if( isset( $this->metaTags["http-equiv:content-script-type"] ) )
			$typeDefault	= $this->metaTags["http-equiv:content-script-type"]['content'];
		$scriptData	= array(
			'type'		=> $type ? $type : $typeDefault,
			'charset'	=> $charset ? $charset : NULL,
			'src'		=> $uri,
		);
		$this->scripts[]	= $scriptData;
	}

	/**
	 *	Adds a Meta Tag to Head.
	 *	@access		public
	 *	@param		string		$type			Meta Tag Key Type (name|http-equiv) 
	 *	@param		string		$key			Meta Tag Key Name
	 *	@param		string		$value			Meta Tag Value
	 *	@return		void
	 */
	public function addMetaTag( $type, $key, $value )
	{
		$metaData	= array(
			$type		=> $key,
			'content'	=> $value,
		);
		$this->metaTags[strtolower( $type.":".$key )]	= $metaData;
	}

	public function addPrefix( $prefix, $namespace )
	{
		$this->prefixes[$prefix]	= $namespace;
	}

	public function addScript( $script, $type = "text/javascript" ){
		$this->addHead( UI_HTML_Tag::create( 'script', $script, array( 'type' => $type ) ) );
	}

	/**
	 *	Adds a Stylesheet Link to Head.
	 *	@access		public
	 *	@param		string		$uri			URI to CSS File
	 *	@param		string		$media			Media Type (all|screen|print|...), default: screen
	 *	@param		string		$type			Content Type, by default 'text/css'
	 *	@return		void
	 *	@see		http://www.w3.org/TR/html4/types.html#h-6.13
	 */
	public function addStylesheet( $uri, $media = "all", $type = NULL )
	{
		$typeDefault	= 'text/css';
		if( isset( $this->metaTags["http-equiv:content-style-type"] ) )
			$typeDefault	= $this->metaTags["http-equiv:content-style-type"]['content'];
		$styleData	= array(
			'rel'		=> "stylesheet",
			'type'		=> $type ? $type : $typeDefault,
			'media'		=> $media,
			'href'		=> $uri,
		);
		$this->styles[]	= $styleData;
	}

	/**
	 *	Builds Page Frame HTML.
	 *	@access		public
	 *	@return		string
	 */
	public function build( $bodyAttributes = array(), $htmlAttributes = array() )
	{
		$tagsHead	= array();
		$tagsBody	= array();

		if( $this->baseHref )
			$tagsHead[]	= UI_HTML_Tag::create( 'base', NULL, array( 'href' => $this->baseHref ) );
		foreach( $this->metaTags as $attributes )
			$tagsHead[]	= UI_HTML_Tag::create( 'meta', NULL, $attributes );

		if( $this->title )
			$tagsHead[]	= UI_HTML_Tag::create( 'title', $this->title );

		if( $this->heading )
			$tagsBody[]	= UI_HTML_Tag::create( 'h1', $this->heading );

		foreach( $this->styles as $attributes )
			$tagsHead[]	= UI_HTML_Tag::create( "link", NULL, $attributes );

		foreach( $this->scripts as $attributes )
			$tagsHead[]	= UI_HTML_Tag::create( "script", "", $attributes );

		$headAttributes	= array(
			'profile'	=> $this->profile
		);

		$tagsHead	= implode( "\n".$this->indent.$this->indent, $tagsHead );
		$tagsHead	.= implode( "\n".$this->indent.$this->indent, $this->head );
		$tagsBody	= implode( "\n".$this->indent.$this->indent, $tagsBody );
		$tagsBody	.= implode( "\n".$this->indent.$this->indent, $this->body );
		if( $tagsBody )
			$tagsBody	= "\n".$this->indent.$this->indent.$tagsBody."\n".$this->indent;
		if( $tagsHead )
			$tagsHead	= "\n".$this->indent.$this->indent.$tagsHead."\n".$this->indent;
		$head		= UI_HTML_Tag::create( "head", $tagsHead, $headAttributes );
		$body		= UI_HTML_Tag::create( "body", $tagsBody, $bodyAttributes );
		
		$doctype	= $this->doctypes[$this->doctype];
		$attributes	= array( 'lang' => $this->language );
		if( is_int( strpos( $doctype, 'xhtml' ) )/* || $this->doctype == 'HTML_5'*/ ){
			$attributes	= array( 'xml:lang' => $this->language ) + $attributes;
			$attributes	= array( 'xmlns' => "http://www.w3.org/1999/xhtml" ) + $attributes;
		}
		if( $this->prefixes ){
			$list	= array();
			foreach( $this->prefixes as $prefix => $namespace )
				$list[]	= $prefix.": ".$namespace;
			$attributes['prefix']	= join( " ", $list );
		}
		foreach( $htmlAttributes as $key => $value ){
			if( isset( $attributes[$key] ) && $key == "prefix" )
				$attributes['prefix']	.= " ".$value;
			else
				$attributes[$key]	= $value;
		}
		$content	= "\n".$this->indent.$head."\n".$this->indent.$body."\n";
		$html		= UI_HTML_Tag::create( "html", $content, $attributes );
		return $doctype."\n".$html;
	}

	/**
	 *	Returns set page body.
	 *	@access		public
	 *	@param		string		$separator		Glue between added body blocks	
	 *	@return		string
	 */
	public function getBody( $separator = "\n" )
	{
		return join( $separator, $this->body );
	}

	public function getLanguage(){
		return $this->language;
	}

	/**
	 *	Returns set page title.
	 *	@access		public
	 *	@return		string
	 */
	public function getTitle(){
		return $this->title;
	}

	/**
	 *	Sets base URI for all referencing resources.
	 *	@access		public
	 *	@param		string		$uri			Base URI for all referencing resources
	 *	@return		void
	 */
	public function setBaseHref( $uri )
	{
		$this->baseHref	= $uri;
	}

	public function setBody( $string )
	{
		$this->body		= $string;
	}

	protected function setDocType( $doctype )
	{
		$doctypes	= array_keys( $this->doctypes );
		$key		= str_replace( array( ' ', '-' ), '_', trim( $doctype ) );
		$key		= preg_replace( "/[^A-Z0-9_]/", '', strtoupper( $key ) );
		if( !strlen( trim( $key ) ) )
			throw new InvalidArgumentException( 'No doctype given' );
		if( !array_key_exists( $key, $this->doctypes ) )
			throw new OutOfRangeException( 'Doctype "'.$doctype.'" (understood as '.$key.') is invalid' );
		$this->doctype	= $key;
	}

	/**
	 *	Sets Application Heading in Body.
	 *	@access		public
	 *	@param		string		$heading		Application Heading
	 *	@return		void
	 */
	public function setHeading( $heading )
	{
		$this->heading	= $heading;
	}

	public function setHeadProfileUrl( $url )
	{
		$this->profile	= $url;
	}

	public function setLanguage( $language ){
		$this->language	= $language;
	}

	/**
	 *	Sets Page Title, visible in Browser Title Bar.
	 *	@access		public
	 *	@param		string		$title			Page Title
	 *	@return		void
	 */
	public function setTitle( $title, $mode = 'set', $separator = ' | ' )
	{
		if( $mode == 'append' || $mode === 1 )
			$title	= $this->title.$separator.$title;
		else if( $mode == 'prepend' || $mode === -1 )
			$title	= $title.$separator.$this->title;
		$this->title	= $title;
	}
}
?>
