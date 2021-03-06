<?php
/**
 *	Property File Editor.
 *	This Implementation keeps the File Structure of original File completely alive.
 *	All Line Feeds and Comments will be kept.
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
 *	@version		$Id$
 */
/**
 *	Property File Editor.
 *	This Implementation keeps the File Structure of original File completely alive.
 *	All Line Feeds and Comments will be kept.
 *	@category		cmClasses
 *	@package		File.INI
 *	@extends		File_INI_Reader
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 *	@todo			Code Documentation
 */
class File_INI_Editor extends File_INI_Reader
{
	/**	@var		array		$added			Added Properties */
	protected $added			= array();
	/**	@var		array		$renamed		Renamed Properties */
	protected $renamed			= array();
	/**	@var		array		$deleted		Deleted Properties */
	protected $deleted			= array();

	/**
	 *	Activates a Property.
	 *	@access		public
	 *	@param		string		$key			Key of  Property
	 *	@param		string		$value			Section of Property
	 *	@return		bool
	 */
	public function activateProperty( $key, $section = NULL )
	{
		if( $this->usesSections() )
		{
			if( !$this->hasProperty( $key, $section ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing in section "'.$section.'"' );
			if( $this->isActiveProperty( $key, $section ) )
				throw new LogicException( 'Key "'.$key.'" is already active' );
			unset( $this->disabled[$section][array_search( $key, $this->disabled[$section] )] );
			return is_int( $this->write() );
		}
		else
		{
			if( !$this->hasProperty( $key ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing' );
			if( $this->isActiveProperty( $key ) )
				throw new LogicException( 'Key "'.$key.'" is already active' );
			unset( $this->disabled[array_search( $key, $this->disabled )] );
			return is_int( $this->write() );
		}
	}

	/**
	 *	Adds a new Property with Comment.
	 *	@access		public
	 *	@param		string		$key			Key of new Property
	 *	@param		string		$value			Value of new Property
	 *	@param		string		$comment		Comment of new Property
	 *	@param		bool		$state			Activity state of new Property
	 *	@param		string		$section		Section to add Property to
	 *	@return		bool
	 */
	public function addProperty( $key, $value, $comment = '', $state = TRUE, $section = NULL )
	{
		if( $section && !in_array( $section, $this->sections ) )
			$this->addSection( $section );
		$key = ( $state ? "" : $this->signDisabled ).$key;
		$this->added[] = array(
			"key"		=> $key,
			"value"		=> $value,
			"comment"	=> $comment,
			"section"	=> $section,
		);
		return is_int( $this->write() );
	}

	/**
	 *	Adds a new Section.
	 *	@access		public
	 *	@param		string		$sectionName	Name of new Section
	 *	@return		bool
	 */
	public function addSection( $sectionName )
	{
		if( !$this->usesSections() )
			throw new RuntimeException( 'Sections are disabled' );
		$lines		= File_Reader::loadArray( $this->fileName );
		$lines[]	= "[".$sectionName."]";
		if( !in_array( $sectionName, $this->sections ) )
			$this->sections[] = $sectionName;
		$result		= File_Writer::saveArray( $this->fileName, $lines );
		$this->read();
		return is_int( $result );
	}

	/**
	 *	Returns a build Property line.
	 *	@access		private
	 *	@param		string		$key			Key of  Property
	 *	@param		string		$value			Value of Property
	 *	@param		string		$comment		Comment of Property
	 *	@return		string
	 */
	private function buildLine( $key, $value, $comment )
	{
		$content	= '"'.addslashes( $value ).'"';
		if( $this->reservedWords && is_bool( $value ) )
			$content	= $value ? "yes" : "no";
		
		$breaksKey		= 4 - floor( strlen( $key ) / 8 );
		$breaksValue	= 4 - floor( strlen( $content ) / 8 );
		if( $breaksKey < 1 )
			$breaksKey = 1;
		if( $breaksValue < 1 )
			$breaksValue = 1;
		$line	= $key.str_repeat( "\t", $breaksKey ).'= '.$content;
		if( $comment )
			$line	.= str_repeat( "\t", $breaksValue ).'; '.$comment;
		return $line;
	}

	/**
	 *	Deactivates a Property.
	 *	@access		public
	 *	@param		string		$key			Key of  Property
	 *	@param		string		$value			Section of Property
	 *	@return		bool
	 */
	public function deactivateProperty( $key, $section = NULL)
	{
		if( $this->usesSections() )
		{
			if( !$this->hasProperty( $key, $section ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing in section "'.$section.'"' );
			if( !$this->isActiveProperty( $key, $section ) )
				throw new LogicException( 'Key "'.$key.'" is already inactive' );
			$this->disabled[$section][] = $key;
			return is_int( $this->write() );
		}
		else
		{
			if( !$this->hasProperty( $key ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing' );
			if( !$this->isActiveProperty( $key ) )
				throw new LogicException( 'Key "'.$key.'" is already inactive' );
			$this->disabled[] = $key;
			return is_int( $this->write() );
		}
	}

	/**
	 *	Deletes a  Property.
	 *	Alias for removeProperty.
	 *	@access		public
	 *	@param		string		$key			Key of Property to be deleted
	 *	@return		bool
	 */
	public function deleteProperty( $key, $section = NULL )
	{
		return $this->removeProperty( $key, $section );
	}

	/**
	 *	Removes a  Property.
	 *	@access		public
	 *	@param		string		$key			Key of Property to be removed
	 *	@return		bool
	 */
	public function removeProperty( $key, $section = NULL )
	{
		if( $this->usesSections() )
		{
			if( !$this->hasProperty( $key, $section ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing in section "'.$section.'"' );
			$this->deleted[$section][] = $key;
		}
		else
		{
			if( !$this->hasProperty( $key ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing' );
			$this->deleted[] = $key;
		}
		return is_int( $this->write() );
	}

	/**
	 *	Removes a Section
	 *	@access		public
	 *	@param		string		$section		Key of Section to remove
	 *	@return		bool
	 */
	public function removeSection( $section )
	{
		if( !$this->usesSections() )
			throw new RuntimeException( 'Sections are disabled' );
		if( !$this->hasSection( $section ) )
			throw new InvalidArgumentException( 'Section "'.$section.'" is not existing' );
		$index	= array_search( $section, $this->sections);
		unset( $this->sections[$index] );
		return is_int( $this->write() );
	}

	/**
	 *	Renames a Property Key.
	 *	@access		public
	 *	@param		string		$key			Key of Property to rename
	 *	@param		string		$new			New Key of Property
	 *	@param		string		$section		Section of Property
	 *	@return		bool
	 */
	public function renameProperty( $key, $new, $section = NULL )
	{
		if( $this->usesSections() )
		{
			if( !$this->hasProperty( $key, $section ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing in section "'.$section.'"' );
			$this->properties[$section][$new]	= $this->properties[$section][$key];
			if( isset( $this->disabled[$section][$key] ) )
				$this->disabled [$section][$new]		= $this->disabled[$section][$key];
			if( isset( $this->comments[$section][$key] ) )
				$this->comments [$section][$new]	= $this->comments[$section][$key];
			$this->renamed[$section][$key] = $new;
			return is_int( $this->write() );
		}
		else
		{
			if( !$this->hasProperty( $key ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing' );
			$this->properties[$new]	= $this->properties[$key];
			if( isset( $this->disabled[$key] ) )
				$this->disabled[$new]		= $this->disabled[$key];
			if( isset( $this->comments[$key] ) )
				$this->comments[$new]	= $this->comments[$key];
			$this->renamed[$key]	= $new;
			return is_int( $this->write() );
		}
	}

	/**
	 *	Renames as Section.
	 *	@access		public
	 *	@param		string		$oldSection		Key of Section to rename
	 *	@param		string		$newSection		New Key of Section
	 *	@return		bool
	 */
	public function renameSection( $oldSection, $newSection )
	{
		if( !$this->usesSections() )
			throw new RuntimeException( 'Sections are disabled' );
		$content	= File_Reader::load( $this->fileName );
		$content	= preg_replace( "/(.*)(\[".$oldSection."\])(.*)/si", "$1[".$newSection."]$3", $content );
		$result		= File_Writer::save( $this->fileName, $content );
		$this->added	= array();
		$this->deleted	= array();
		$this->renamed	= array();
		$this->read();
		return is_int( $result );
	}

	/**
	 *	Sets the Comment of a Property.
	 *	@access		public
	 *	@param		string		$key			Key of Property
	 *	@param		string		$comment		Comment of Property to set
	 *	@param		string		$section		Key of Section
	 *	@return		bool
	 */
	public function setComment( $key, $comment, $section = NULL )
	{
		if( $this->usesSections() )
		{
			if( !$this->hasProperty( $key, $section ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing in Section "'.$section.'".' );
			$this->comments[$section][$key] = $comment;
		}
		else
		{
			if( !$this->hasProperty( $key ) )
				throw new InvalidArgumentException( 'Key "'.$key.'" is not existing' );
			$this->comments[$key] = $comment;
		}
		return is_int( $this->write() );
	}

	/**
	 *	Sets the Comment of a Property.
	 *	@access		public
	 *	@param		string		$key			Key of Property
	 *	@param		string		$value			Value of Property
	 *	@param		string		$section		Key of Section
	 *	@return		bool
	 */
	public function setProperty( $key, $value, $section = NULL )
	{
		if( $this->usesSections() )
		{
			if( $this->hasSection( $section ) && $this->hasProperty( $key, $section ) )
				$this->properties[$section][$key] = $value;
			else
				$this->addProperty( $key, $value, FALSE, TRUE, $section );
		}
		else
		{
			if( $this->hasProperty( $key ) )
				$this->properties[$key] = $value;
			else
				$this->addProperty( $key, $value, FALSE, TRUE );
		}
		return is_int( $this->write() );
	}

	/**
	 *	Writes manipulated Content to File.
	 *	@access		protected
	 *	@return		int			Number of written bytes
	 */
	protected function write()
	{
		$file		= new File_Writer( $this->fileName );
		$newLines	= array();
		$currentSection	= "";
		foreach( $this->lines as $line )
		{
			if( $this->usesSections() && preg_match( $this->patternSection, $line ) )
			{
				$lastSection = $currentSection;
#				$newAdded = array();
				if( $lastSection )
				{
					foreach( $this->added as $nr => $property )
					{
						if( $property['section'] == $lastSection )
						{
							if( !trim( $newLines[count($newLines)-1] ) )
								array_pop( $newLines );
							$newLines[]	= $this->buildLine( $property['key'], $property['value'], $property['comment'] );
							$newLines[]	= "";
							unset( $this->added[$nr] );
						}
#						else $newAdded[] = $property;
					}
				}
				$currentSection =  substr( trim( $line ), 1, -1 );
				if( !in_array( $currentSection, $this->sections ) )
					continue;
			}
			else if( preg_match( $this->patternProperty, $line ) )
			{
				$pos		= strpos( $line, "=" );
				$key		= trim( substr( $line, 0, $pos ) );
				$pureKey	= preg_replace( $this->patternDisabled, "", $key );
				$parts		= explode(  "//", trim( substr( $line, $pos+1 ) ) );
				if( count( $parts ) > 1 )
					$comment = trim( $parts[1] );
				if( $this->usesSections() )
				{
					if( in_array( $currentSection, $this->sections ) )
					{
						if( isset( $this->deleted[$currentSection] ) && in_array( $pureKey, $this->deleted[$currentSection] ) )
							unset( $line );
						else if( isset( $this->renamed[$currentSection] ) && in_array( $pureKey, array_keys( $this->renamed[$currentSection] ) ) )
						{
							$newKey	= $key	= $this->renamed[$currentSection][$pureKey];
							if( !$this->isActiveProperty( $newKey, $currentSection) )
								$key = $this->signDisabled.$key;
							$comment	= isset( $this->comments[$currentSection][$newKey] ) ? $this->comments[$currentSection][$newKey] : "";
							$line = $this->buildLine( $key, $this->properties[$currentSection][$newKey], $comment );
						}
						else
						{
							if( $this->isActiveProperty( $pureKey, $currentSection ) && preg_match( $this->patternDisabled, $key ) )
								$key = substr( $key, 1 );
							else if( !$this->isActiveProperty( $pureKey, $currentSection ) && !preg_match( $this->patternDisabled, $key ) )
								$key = $this->signDisabled.$key;
							$comment	= isset( $this->comments[$currentSection][$pureKey] ) ? $this->comments[$currentSection][$pureKey] : "";
							$line = $this->buildLine( $key, $this->properties[$currentSection][$pureKey], $comment );
						}
					}
					else
						unset( $line );
				}
				else
				{
					if( in_array( $pureKey, $this->deleted ) )
						unset( $line);
					else if( in_array( $pureKey, array_keys( $this->renamed ) ) )
					{
						$newKey	= $key	= $this->renamed[$pureKey];
						if( !$this->isActiveProperty( $newKey ) )
							$key = $this->signDisabled.$key;
						$line = $this->buildLine( $newKey, $this->properties[$newKey], $this->comments[$newKey] );
					}
					else
					{
						if( $this->isActiveProperty( $pureKey ) && preg_match( $this->patternDisabled, $key ) )
							$key = substr( $key, 1 );
						else if( !$this->isActiveProperty( $pureKey) && !preg_match( $this->patternDisabled, $key ) )
							$key = $this->signDisabled.$key;
						$line = $this->buildLine( $key, $this->properties[$pureKey], $this->getComment( $pureKey ) );
					}
				}
			}
			if( isset( $line ) )
				$newLines[] = $line;
		}
		foreach( $this->added as $property )
		{
			$newLine	= $this->buildLine( $property['key'], $property['value'], $property['comment'] );
			$newLines[]	= $newLine;
		}
		$result			= $file->writeArray( $newLines );
		$this->added	= array();
		$this->deleted	= array();
		$this->renamed	= array();
		$this->read();
		return $result;
	}
}
?>