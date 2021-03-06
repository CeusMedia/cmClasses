<?php
/**
 *	Class Data Class.
 *
 *	Copyright (c) 2008-2012 Christian Würker (ceusmedia.com)
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
 *	@package		ADT.PHP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@version		$Id$
 *	@since			0.3
 */
/**
 *	Class Data Class.
 *	@category		cmClasses
 *	@package		ADT.PHP
 *	@extends		ADT_PHP_Interface
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@version		$Id$
 *	@since			0.3
 */
class ADT_PHP_Class extends ADT_PHP_Interface
{
	protected $abstract		= FALSE;
	protected $final		= FALSE;

	protected $members		= array();

	protected $implements	= array();	
	protected $uses			= array();

	public function getExtendedClass()
	{
		return $this->extends;
	}

	public function getExtendingClasses()
	{
		return $this->extendedBy;
	}
	
	public function getImplementedInterfaces()
	{
		return $this->implements;	
	}
	
	/**
	 *	Returns a member data object by its name.
	 *	@access		public
	 *	@param		string			$name		Member name
	 *	@return		ADT_PHP_Member	Member data object
	 */
	public function & getMemberByName( $name )
	{
		if( isset( $this->members[$name] ) )
			return $this->members[$name];
		throw new RuntimeException( "Member '$name' is unknown" );
	}

	/**
	 *	Returns a list of member data objects.
	 *	@access		public
	 *	@return		array			List of member data objects
	 */
	public function getMembers()
	{
		return $this->members;
	}
	
	public function getUsedClasses()
	{
		return $this->uses;	
	}

	public function merge( ADT_PHP_Interface $artefact )
	{
		if( $this->name != $artefact->getName() )
			throw new Exception( 'Not mergable' );
		if( $artefact->isAbstract() )
			$this->setAbstract( $artefact->isAbstract() );
		if( $artefact->getDefault() )
			$this->setDefault( $artefact->getDefault() );
		if( $artefact->isStatic() )
			$this->setAbstract( $artefact->isStatic() );

		foreach( $variable->getUsedClasses() as $artefact )
			$this->setUsedClass( $artefact );
		foreach( $variable->getUsedClasses() as $artefact )
			$this->setUsedClass( $artefact );

		//	@todo		members and interfaces missing
	}

	public function isAbstract()
	{
		return (bool) $this->abstract;
	}

	public function isFinal()
	{
		return (bool) $this->final;
	}

	public function isImplementingInterface( ADT_PHP_Interface $interface )
	{
		foreach( $this->implements as $interfaceName => $interfaceObject )
			if( $interface == $interfaceObject )
				return TRUE;
		return FALSE;
	}

	public function isUsingClass( ADT_PHP_Class $class )
	{
		foreach( $this->uses as $className => $class )
			if( $class == $class )
				return TRUE;
		return FALSE;
	}
	
	public function setAbstract( $isAbstract = TRUE )
	{
		$this->abstract	= (bool) $isAbstract;
	}

	public function setExtendedClass( ADT_PHP_Class $class )
	{
		$this->extends	= $class;	
	}

	public function setExtendedClassName( $class )
	{
		$this->extends	= $class;	
	}

	public function setExtendingInterface( ADT_PHP_Interface $interface )
	{
		throw new RuntimeException( 'Interface cannot extend class' );
	}

	public function setExtendingClass( ADT_PHP_Class $class )
	{
		$this->extendedBy[$class->getName()]	= $class;
	}

	public function setExtendedInterface( ADT_PHP_Interface $interface )
	{
		throw new RuntimeException( 'Class cannot extend an interface' );
	}
	
	public function setFinal( $isFinal = TRUE )
	{
		$this->final	= (bool) $isFinal;
	}
	
	public function setImplementedInterface( ADT_PHP_Interface $interface )
	{
		$this->implements[$interface->name]	= $interface;	
	}
	
	public function setImplementedInterfaceName( $interfaceName )
	{
		$this->implements[$interfaceName]	= $interfaceName;
	}

	public function setImplementingClass( ADT_PHP_Class $class )
	{
		$this->implementedBy[$class->getName()]	= $class;
	}

	public function setImplementingClassName( $className )
	{
		$this->implementedBy[$className]	= $className;
	}
	
	/**
	 *	Sets a member.
	 *	@access		public
	 *	@param		string			Member name
	 *	@param		ADT_PHP_Member	Member data object to set
	 *	@return		void
	 */
	public function setMember( ADT_PHP_Member $member )
	{
		$this->members[$member->getName()]	= $member;
	}
	
	public function setUsedClass( ADT_PHP_Class $class )
	{
		return $this->uses[$class->getName()]	= $class;	
	}
	
	public function setUsedClassName( $className )
	{
		return $this->uses[$className]	= $className;	
	}
}
?>