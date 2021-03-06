<?php
/**
 *	Reader for Property Files or typical .ini Files with Key, Values and optional Sections and Comments.
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
 *	@package		File.INI
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.01.2001
 *	@version		$Id$
 */
/**
 *	Reader for Property Files or typical .ini Files with Key, Values and optional Sections and Comments.
 *	@category		cmClasses
 *	@package		File.INI
 *	@uses			File_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			01.01.2001
 *	@version		$Id$
 */
class File_INI_Reader extends File_Reader
{
	/**	@var		string			$fileName				URI of Ini File */
	protected $fileName				= array();
	/**	@var		array			$comments				List of collected Comments */
	protected $comments				= array();
	/**	@var		array			$lines					List of collected Lines */
	protected $lines				= array();
	/**	@var		array			$properties				List of collected Properties */
	protected $properties			= array();
	/**	@var		array			$sections				List of collected Sections */
	protected $sections				= array();
	/**	@var		array			$disabled				List of disabled Properties */
	protected $disabled				= array();
	/**	@var		bool			$usesSections			Flag: use Sections */
	protected $usesSections			= FALSE;
	/**	@var		boolean			$reservedWords			Flag: use reserved words */
	protected $reservedWords		= TRUE;
	/**	@var		string			$signDisabled			Sign( string) of disabled Properties */
	protected $signDisabled			= ';';
	/**	@var		string			$patternDisabled		Pattern( regex) of disabled Properties */
	protected $patternDisabled 		= '/^;/';
	/**	@var		string			$patternProperty		Pattern( regex) of Properties */
	protected $patternProperty		= '/^(;|[a-z0-9-])+([a-z0-9#.:@\/\\|_-]*[ |\t]*=)/i';
	/**	@var		string			$patternDescription		Pattern( regex) of Descriptions */
	protected $patternDescription	= '/^[;|#|:|\/|=]{1,2}/';
	/**	@var		string			$patternSection			Pattern( regex) of Sections */
	protected $patternSection		= '/^\s*\[([a-z0-9_=.,:;#@-]+)\]\s*$/i';
	/**	@var		string			$patternLineComment		Pattern( regex) of Line Comments */
	protected $patternLineComment	= '/([\t| ]+([\/]{2}|[;])+[\t| ]*)/';

	/**
	 *	Constructor, reads Property File.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Property File, absolute or relative URI
	 *	@param		bool		$usesSections	Flag: Property File contains Sections
	 *	@param		bool		$reservedWords	Flag: interprete reserved Words like yes,no,true,false,null
	 *	@return		void
	 */
	public function __construct( $fileName, $usesSections = FALSE, $reservedWords = TRUE )
	{
		parent::__construct( $fileName );
		$this->usesSections			= $usesSections;
		$this->reservedWords		= $reservedWords;
		$this->read();
	}

	/**
	 *	Returns the Comment of a Property.
	 *	@access		public
	 *	@param		string		$key			Key of Property
	 *	@param		string		$section		Section of Property
	 *	@return		string
	 */
	public function getComment( $key, $section = NULL )
	{
		if( $this->usesSections() )
		{
			if( empty( $section ) )
				throw new InvalidArgumentException( 'No section given' );
			if( !$this->hasSection( $section ) )
				throw new InvalidArgumentException( 'Section "'.$section.'" is not existing' );
			if( !empty( $this->comments[$section][$key] ) )
				return $this->comments[$section][$key];
		}
		else if( !empty( $this->comments[$key] ) )
			return $this->comments[$key];
		return '';
	}

	/**
	 *	Returns a List of Property Arrays with Key, Value, Comment and Activity of every Property.
	 *	@access		public
	 *	@param		bool		$activeOnly		Flag: return only active Properties
	 *	@return		array
	 */
	public function getCommentedProperties( $activeOnly = TRUE )
	{
		$list = array();
		if( $this->usesSections() )
		{
			foreach( $this->sections as $section )
			{
				foreach( $this->properties[$section] as $key => $value )
				{
					if( $activeOnly && !$this->isActiveProperty( $key, $section ) )
						continue;
					$property = array(
						"key"		=>	$key,
						"value"		=>	$value,
						"comment"	=>	$this->getComment( $key, $section ),
						"active"	=> 	(bool) $this->isActiveProperty( $key, $section )
						);
					$list[$section][] = $property;
				}
			}
		}
		else
		{
			foreach( $this->properties as $key => $value )
			{
				if( $activeOnly && !$this->isActiveProperty( $key ) )
					continue;
				$property = Array(
					"key"		=>	$key,
					"value"		=>	$value,
					"comment"	=>	$this->getComment( $key ),
					"active"	=> 	(bool)$this->isActiveProperty( $key )
					);
				$list[] = $property;
			}
		}
		return $list;
	}

	/**
	 *	Returns all Comments or all Comments of a Section.
	 *	@access		public
	 *	@param		string		$section		Key of Section
	 *	@return		array
	 */
	public function getComments( $section = NULL )
	{
		if( $this->usesSections() && $section )
		{
			if( $this->hasSection( $section ) )
				return $this->comments[$section];
			throw new InvalidArgumentException( 'Section "'.$section.'" is not existing' );
		}
		return $this->comments;
	}

	/**
	 *	Returns an Array with all or active only Properties.
	 *	@access		public
	 *	@param		bool		$activeOnly		Flag: return only active Properties
	 *	@param		string		$section		Only Section with given Section Name
	 *	@return		array
	 */
	public function getProperties( $activeOnly = TRUE, $section = NULL )
	{
		$properties = array();
		if( $this->usesSections() )
		{
			if( $section )
			{
				if( !$this->hasSection( $section ) )
					throw new InvalidArgumentException( 'Section "'.$section.'" is not existing.' );
				foreach( $this->properties[$section]  as $key => $value )
				{
					if( $activeOnly && !$this->isActiveProperty( $key, $section ) )
						continue;
					$properties[$key] = $value;
				}
			}
			else
			{
				foreach( $this->sections as $section)
				{
					$properties[$section]	= array();
					foreach( $this->properties[$section] as $key => $value )
					{
						if( $activeOnly && !$this->isActiveProperty( $key, $section ) )
							continue;
						$properties[$section][$key] = $value;
					}
				}
			}
		}
		else
		{
			foreach( $this->properties as $key => $value )
			{
				if( $activeOnly && !$this->isActiveProperty( $key ) )
					continue;
				$properties[$key] = $value;
			}
		}
		return $properties;
	}

	/**
	 *	Returns the Value of a Property by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Property
	 *	@param		string		$sections		Key of Section
	 *	@param		bool		$activeOnly		Flag: return only active Properties
	 *	@return		string
	 */
	public function getProperty( $key, $section = NULL, $activeOnly = TRUE )
	{
		if( $this->usesSections() )
		{
			if( !$section )
				throw new InvalidArgumentException( 'No section given' );
			if( $activeOnly && !$this->isActiveProperty( $key, $section ) )
				throw new InvalidArgumentException( 'Property "'.$key.'" is not set or inactive' );
			return $this->properties[$section][$key];
		}
		else
		{
			if( $activeOnly && !$this->isActiveProperty( $key ) )
				throw new InvalidArgumentException( 'Property "'.$key.'" is not set or inactive' );
			return $this->properties[$key];
		}
	}

	/**
	 *	Returns an Array with all or active only Properties.
	 *	@access		public
	 *	@param		bool		$activeOnly		Flag: return only active Properties
	 *	@return		array
	 */
	public function getPropertyList( $activeOnly = TRUE )
	{
		$properties = array();
		if( $this->usesSections() )
		{
			foreach( $this->sections as $sectionName )
			{
				foreach( $this->properties[$sectionName]  as $key => $value )
				{
					if( $activeOnly && !$this->isActiveProperty( $key, $sectionName ) )
						continue;
					$properties[$sectionName][] = $key;
				}
			}
		}
		else
		{
			foreach( $this->properties as $key => $value )
			{
				if( $activeOnly && !$this->isActiveProperty( $key ) )
					continue;
				$properties[] = $key;
			}
		}
		return $properties;
	}

	/**
	 *	Returns an array of all Section Keys.
	 *	@access		public
	 *	@return		array
	 */
	public function getSections()
	{
		if( !$this->usesSections() )
			throw new RuntimeException( 'Sections are disabled' );
		return $this->sections;
	}

	/**
	 *	Indicates wheter a Property is existing.
	 *	@access		public
	 *	@param		string		$key		Key of Property
	 *	@param		string		$section	Key of Section
	 *	@return		bool
	 */
	public function hasProperty( $key, $section = NULL )
	{
		if( $this->usesSections() )
		{
			if( empty( $section ) )
				throw new InvalidArgumentException( 'No section given' );
			if( !$this->hasSection( $section ) )
				throw new InvalidArgumentException( 'Section "'.$section.'" is not existing' );
			return isset( $this->properties[$section][$key] );
		}
		else
			return isset( $this->properties[$key] );
	}

	/**
	 *	Indicates wheter a Property is existing.
	 *	@access		public
	 *	@param		string		$section	Key of Section
	 *	@return		bool
	 */
	public function hasSection( $section )
	{
		if( !$this->usesSections() )
			throw new RuntimeException( 'Sections are disabled' );
		return in_array( $section, $this->sections );
	}


	/**
	 *	Indicates wheter a Property is active.
	 *	@access		public
	 *	@param		string		$key		Key of Property
	 *	@param		string		$sections	Key of Section
	 *	@return		bool
	 */
	public function isActiveProperty( $key, $section = NULL )
	{
		if( $this->usesSections() )
		{
			if( empty( $section ) )
				throw new InvalidArgumentException( 'No Section given' );
			if( isset( $this->disabled[$section] ) )
				if( is_array( $this->disabled[$section] ) )
					if( in_array( $key, $this->disabled[$section] ) )
						return FALSE;
			return $this->hasProperty( $key, $section );
		}
		else if( in_array( $key, $this->disabled ) )
			return FALSE;
		return $this->hasProperty( $key );
	}

	/**
	 *	Loads an INI File and returns an Array statically.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Property File, absolute or relative URI
	 *	@param		bool		$usesSections	Flag: Property File containts Sections
	 *	@param		bool		$activeOnly		Flag: return only active Properties
	 *	@return		array
	 */
	public static function load( $fileName, $usesSections = FALSE, $activeOnly = TRUE )
	{
		$reader	= new self( $fileName, $usesSections );
		return $reader->toArray( $activeOnly );
	}

	/**
	 *	Loads an INI File and returns an Array Object statically.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Property File, absolute or relative URI
	 *	@param		bool		$usesSections	Flag: Property File containts Sections
	 *	@param		bool		$activeOnly		Flag: return only active Properties
	 *	@return		ArrayObject
	 */
	public static function loadAsArrayObject( $fileName, $usesSections = FALSE, $activeOnly = TRUE )
	{
		return new ArrayObject( self::load( $fileName, $usesSections, $activeOnly ) );
	}

	/**
	 *	Reads the entire Property File and divides Properties and Comments.
	 *	@access		protected
	 *	@return		void
	 */
	protected function read()
	{
		$this->comments		= array();
		$this->disabled		= array();
		$this->lines		= array();
		$this->properties	= array();
		$this->sections		= array();
		$this->lines		= array();
		$this->comments		= array();
		$commentOpen		= 0;
		$lines				= $this->readArray();
		foreach( $lines as $line )
		{
			$line			= trim( $line );
			$this->lines[]	= $line;

			$commentOpen	+= preg_match( "@^/\*@", trim( $line ) );
			$commentOpen	-= preg_match( "@\*/$@", trim( $line ) );

			if( $commentOpen )
				continue;

			if( $this->usesSections() && preg_match( $this->patternSection, $line ) )
			{
#				$currentSection		= substr( trim( $line ), 1, -1 );								//  @todo remove this line in 0.8.0
				$currentSection		= preg_replace( $this->patternSection, '\\1', $line );
				$this->sections[]	= $currentSection;
				$this->disabled[$currentSection]	= array();
				$this->properties[$currentSection]	= array();
				$this->comments[$currentSection]	= array();
			}
			else if( preg_match( $this->patternProperty, $line ) )
			{
				if( !count( $this->sections ) )
					$this->usesSections	= false;
				$pos	= strpos( $line, "=" );
				$key	= trim( substr( $line, 0, $pos ) );
				$value	= trim( substr( $line, ++$pos ) );

				if( preg_match( $this->patternDisabled, $key ) )
				{
					$key = preg_replace( $this->patternDisabled, "", $key );
					if( $this->usesSections() )
						$this->disabled[$currentSection][] = $key;
					$this->disabled[] = $key;
				}

				//  --  EXTRACT COMMENT  --  //
				if( preg_match( $this->patternLineComment, $value ) )
				{
					$newValue		= preg_split( $this->patternLineComment, $value, 2 );
					$value			= trim( $newValue[0] );
					$inlineComment	= trim( $newValue[1] );
					if( $this->usesSections() )
						$this->comments[$currentSection][$key] = $inlineComment;
					else
						$this->comments[$key] = $inlineComment;
				}

				//  --  CONVERT PROTECTED VALUES  --  //
				if( $this->reservedWords )
				{
					if( in_array( strtolower( $value ), array( 'yes', 'true' ) ) )
						$value	= TRUE;
					else if( in_array( strtolower( $value ), array( 'no', 'false' ) ) )
						$value	= FALSE;
					else if( strtolower( $value ) === "null" )
						$value	= NULL;
				}
				if( preg_match( '@^".*"$@', $value ) )
					$value	= substr( stripslashes( $value ), 1, -1 );			
				if( $this->usesSections() )
					$this->properties[$currentSection][$key] = $value;
				else
					$this->properties[$key] = $value;
			}
		}
	}

	/**
	 *	Returns an array of all Properties.
	 *	@access		public
	 *	@param		bool			$activeOnly	Switch to return only active Properties
	 *	@return		array
	 */
	public function toArray( $activeOnly = TRUE )
	{
		return $this->getProperties( $activeOnly );
	}

	/**
	 *	Indicates whether Sections are used and sets this Switch.
	 *	@access		public
	 *	@return		array
	 */
	public function usesSections()
	{
		return $this->usesSections;
	}
}
?>