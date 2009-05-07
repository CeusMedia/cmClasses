<?php
/**
 *	Reads for several "Gantt Project" XML Files and extracts Project Information and Meeting Dates.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		file.gantt
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			12.03.2008
 *	@version		0.1
 */
import( 'de.ceus-media.file.gantt.MeetingReader' );
/**
 *	Reads for several "Gantt Project" XML Files and extracts Project Information and Meeting Dates.
 *	@package		file.gantt
 *	@uses			File_Gantt_MeetingReader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@since			12.03.2008
 *	@version		0.1
 */
class File_Gantt_MeetingCollector
{
	/**	@var		array		$files			Array of found Gantt Project XML Files */
	protected $files			= array();
	/**	@var		array		$meetigns		Array of extracted Meeting Dates */
	protected $meetings			= array();
	/**	@var		array		$projects		Array of extracted Project Dates */
	protected $projects			= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path			Path to Gantt Project XML Files
	 *	@return		void
	 */
	public function __construct( $path )
	{
		$this->files	= self::listProjectFiles( $path );
		$this->projects	= self::readProjectFiles( $this->files );
	}

	/**
	 *	Returns extracted Meeting Dates.
	 *	@access		public
	 *	@param		string		$projectName	Name of Project (optional)
	 *	@return		array
	 */
	public function getMeetingDates( $projectName = "" )
	{
		$dates	= array();
		foreach( $this->projects as $project )
		{
			if( $projectName && $projectName != $project['name'] )
				continue;
			foreach( $project['meetings'] as $meeting )
			{
				$dates[]	= array(
					'project'	=> $project['name'],
					'name'		=> $meeting['name'],
					'start'		=> $meeting['start'],
					'end'		=> $meeting['end'],
				);
			}
		}
		return $dates;
	}

	/**
	 *	Returns extracted Project Dates.
	 *	@access		public
	 *	@param		string		$projectName	Name of Project (optional)
	 *	@return		array
	 */
	public function getProjectDates( $projectName = "" )
	{
		$dates	= array();
		foreach( $this->projects as $project )
		{
			if( $projectName && $projectName != $project['name'] )
				continue;
			$dates[]	= array(
				'name'	=> $project['name'],
				'start'	=> $project['start'],
				'end'	=> $project['end'],
			);
		}
		return $dates;
	}

	/**
	 *	Lists all Gantt Project XML Files in a specified Path.
	 *	@access		protected
	 *	@static
	 *	@param		array		$path			Path to Gantt Project XML Files
	 *	@return		array
	 */
	protected static function listProjectFiles( $path )
	{
		$list	= array();
		$dir	= new DirectoryIterator( $path );
		foreach( $dir as $entry )
		{
			if( $entry->isDot() )
				continue;
			if( !preg_match( "@\.gan$@", $entry->getFilename() ) )
				continue;
			$list[]	= $entry->getPathname();
		}
		return $list;
	}

	/**
	 *	Reads Gantt Project XML Files and extracts Project and Meeting Dates.
	 *	@access		protected
	 *	@static
	 *	@param		array		$fileList		List of Gantt Project XML Files
	 *	@return		array
	 */
	protected static function readProjectFiles( $fileList )
	{
		$projects	= array();
		foreach( $fileList as $fileName )
		{
			$reader		= new File_Gantt_MeetingReader( $fileName );
			$projects[]	= $reader->getProjectData();
		}
		return $projects;
	}
}
?>