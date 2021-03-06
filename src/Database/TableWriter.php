<?php
/**
 *	TableWriter.
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
 *	@package		Database
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
/**
 *	TableWriter.
 *	@category		cmClasses
 *	@package		Database
 *	@extends		TableReader
 *	@author			Database_Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 *	@todo			finish Code Documentation (@param at methods)
 */
class Database_TableWriter extends Database_TableReader
{
	/**
	 *	Inserting data into this table.
	 *	@access		public
	 *	@param		array		$data			associative array of data to store
	 *	@param		bool		$stripTags		strips HTML Tags from values
	 *	@param		int			$debug			deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		bool
	 */
	public function addData( $data = array(), $stripTags = TRUE, $debug = 1 )
	{
		if( sizeof( $this->fields ) )
		{
			$keys	= array();
			$vals	= array();
			foreach( $this->fields as $field )
			{
				if( !isset( $data[$field] ) )
					continue;
				$value	= $this->secureValue( $data[$field], $stripTags );
				$keys[$field] = '`'.$field.'`';
				$vals[$field] = '"'.$value.'"';
			}
			if( $this->isFocused() && in_array( $this->focusKey, $this->getForeignKeys() ) && !in_array( $this->focusKey, $keys ) )
			{
				$keys[$this->focusKey]	= $this->focusKey;
				$vals[$this->focusKey]	= $this->focus;
			}
			$keys	= implode( ", ", array_values( $keys ) );
			$vals	= implode( ", ", array_values( $vals ) );
			$query	= "INSERT INTO ".$this->getTableName()." (".$keys.") VALUES (".$vals.")";
			$this->dbc->Execute( $query, $debug );
			$id	= $this->dbc->getInsertId();
			$this->focusPrimary( $id );
			return $id;
		}
		return FALSE;
	}

	/**
	 *	Deletes data of focused primary key in this table.
	 *	@access		public
	 *	@param		int			$debug			deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		bool
	 */
	public function deleteData( $debug = 1 )
	{
		if( $this->isFocused() )
		{
			$conditions	= $this->getConditionQuery( array() );
			$query	= "DELETE FROM ".$this->getTableName()." WHERE ".$conditions;
			return $this->dbc->Execute( $query, $debug );
		}
		return FALSE;
	}

	/**
	 *	Deletes data of focused id in table.
	 *	@access		public
	 *	@param		array	$where		associative Array of Condition Strings
	 *	@param		int		$debug		deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		bool
	 */
	public function deleteDataWhere( $where = array(), $debug = 1 )
	{
		$conditions	= $this->getConditionQuery( $where );
		if( $conditions )
		{
			$query	= "DELETE FROM ".$this->getTableName()." WHERE ".$conditions;
			$result	= $this->dbc->Execute( $query, $debug );
			$this->defocus();
			return $result;
		}
	}

	/**
	 *	Inserting data into this table by calling table::addData().
	 *	@access		public
	 *	@param		array	$data		associative array of data to store
	 *	@param		int		$debug		deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		int
	 */
	public function insertData( $data = array(), $debug = 1 )
	{
		return $this->addData( $data, $debug );
	}

	/**
	 *	Updating data of focused primary key in this table.
	 *	@access		public
	 *	@param		array	$data		associative array of data to store
	 *	@param		bool	$stripTags	strips HTML Tags from values
	 *	@param		int		$debug		deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		bool
	 */
	public function modifyData( $data = array(), $stripTags = TRUE, $debug = 1 )
	{
		if( $this->isFocused() )
		{
			if( sizeof( $this->fields ) )
			{
				$has	= $this->getData();
				if( sizeof( $has ) )
				{
					$updates	= array();
					foreach( $this->fields as $field )
					{
						if( !isset( $data[$field] ) )
							continue;
						$value	= $this->secureValue( $data[$field], $stripTags );
						$updates[] = "`".$field."`".'="'.$value.'"';
					}
					if( sizeof( $updates ) )
					{
						$updates	= implode( ", ", $updates );
						$query	= "UPDATE ".$this->getTableName()." SET $updates WHERE ".$this->getConditionQuery( array() );
						$result	= $this->dbc->Execute( $query, $debug );
						return $result;
					}
				}
				else
					return $this->addData( $data );
			}
			else
				trigger_error( "Table '".$this->getTableName()."' is not well defined: Fields are missing.", E_USER_WARNING );
		}
		else
			trigger_error( "Table '".$this->getTableName()."' is not focused.", E_USER_WARNING );
	}

	/**
	 *	Modifies data of unfocused id in table where conditions are given.
	 *	@access		public
	 *	@param		array	$data		associative Array of Data to store
	 *	@param		array	$where		associative Array of Condition Strings
	 *	@param		int		$debug		deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		bool
	 */
	public function modifyDataWhere( $data = array(), $where = array(), $debug = 1 )
	{
		$result		= FALSE;
		$conditions	= $this->getConditionQuery( $where, FALSE, FALSE );
		foreach( $this->fields as $field )
		{
			if( array_key_exists( $field, $data ) )
			{
				$value	= $this->secureValue( $data[$field], TRUE );
				$sets[]	= $field."='".$value."'";
			}
		}
		if( sizeof( $sets ) && $conditions )
		{
			$sets	= implode( ", ", $sets );
			$query	= "UPDATE ".$this->getTableName()." SET $sets WHERE ".$conditions;
			$result	= $this->dbc->Execute( $query, $debug );
		}
		return $result;
	}

	protected function secureValue( $value, $stripTags = NULL )
	{
		if( $stripTags )
			$value	= strip_tags( $value );
#		if( $value == "on" )
#			$value = 1;
		$value	= stripslashes( $value );
#		$value	= htmlentities( $value, ENT_COMPAT );
		$value	= mysql_real_escape_string( $value, $this->dbc->getResource() );
		return $value;
	}
}
?>