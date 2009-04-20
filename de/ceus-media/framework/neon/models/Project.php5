<?php
/**
 *	Data Model of Projects.
 *
 *	Copyright (c) 2007-2009 Christian W�rker (ceus-media.de)
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
 *	@package		framework.neon.models
 *	@extends		Framework_Neon_Model
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2007-2009 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			26.03.2007
 *	@version		0.2
 */
import( 'de.ceus-media.framework.neon.Model' );
/**
 *	Data Model of Projects.
 *	@package		framework.neon.models
 *	@extends		Framework_Neon_Model
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2007-2009 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			26.03.2007
 *	@version		0.2
 */
class Framework_Neon_Models_Project extends Framework_Neon_Model
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int		$projectId		ID of Project
	 *	@return		void
	 *	@author		Christian W�rker <Christian.Wuerker@CeuS-Media.de>
	 *	@since		26.03.2007
	 */
	public function __construct( $projectId = false )
	{
		$tableName	= "projects";
		$fieldList	= array (
			"projectId",
			"title",
			"created",
			"modified",
			);
		$primaryKey	= "projectId";
		$foreignKeys	= array( "title" );
		parent::__construct( $tableName, $fieldList, $primaryKey, $$primaryKey );
		$this->setForeignKeys ( $foreignKeys );
	}
}
?>