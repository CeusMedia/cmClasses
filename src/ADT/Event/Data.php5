<?php
/**
 *	Data class for triggered events.
 *
 *	Copyright (c) 2013 Christian Würker (ceusmedia.com)
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
 *	@package		ADT.Event
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.6
 *	@version		$Id$
 */
/**
 *	Data class for triggered events.
 *	@category		cmClasses
 *	@package		ADT.Event
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2013 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.6
 *	@version		$Id$
 */
class ADT_Event_Data{

	/**	@var	mixed		$arguments		Data given by trigger */
	public $arguments;

	/**	@var	mixed		$arguments		Object which triggered bound event */
	public $caller;

	/**	@var	mixed		$arguments		Data bound on event */
	public $data;

	/**	@var	mixed		$arguments		Reference to event handler instance */
	protected $handler;

	/**	@var	mixed		$arguments		Name bound event, eg. "start.my"  */
	public $key;

	/**	@var	string		$trigger		Name of trigger, eg. "start" */
	public $trigger;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		ADT_Event_Handler	$handler		Event handler instance
	 *	@return		void
	 */
	public function __construct( ADT_Event_Handler $handler ){
		$this->handler	= $handler;
	}

	/**
	 *	Stop propagation of this event.
	 *	@access		public
	 *	@return		void
	 */
	public function stop(){
		$this->handler->stopEvent( $this->trigger );
	}
}
?>
