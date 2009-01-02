<?php
/**
 *	View Component for generated Lists.
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
 *	@package		mv2.view.component
 *	@uses			Logic_List
 *	@uses			Core_View
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			23.02.2007
 *	@version		0.1
 */
import( 'de.ceus-media.framework.krypton.core.View' );
import( 'de.ceus-media.framework.krypton.logic.List' );
/**
 *	View Component for generated Lists.
 *	@package		mv2.view.component
 *	@uses			Logic_List
 *	@uses			Core_View
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			23.02.2007
 *	@version		0.1
 */
class View_Component_List
{
	/**	@var	string				$actionForm		Name of Form for Action Select */
	private $actionForm	= "";
	/**	@var	array				$actions		Associative Array of List Actions */
	private $actions		= array();
	/**	@var	string				$caption		Label of Table Caption */
	private $caption		= "";
	/**	@var	string				$colgroup		Column Widths comma separated */
	private $colgroup		= "";
	/**	@var	array				$heads			Array of Column Heads */
	private $heads			= array();
	/**	@var	string				$limitKey		Session Key of Limit */
	private $limitKey		= "";
	/**	@var	Logic_List			$logic			List Logic for Data Retrival */
	private $logic			= null;
	/**	@var	string				$offsetKey		Session Key of Offset */
	private $offsetKey		= "";
	/**	@var	array				$pagingOptions	Array with Options for Paging System */
	private $pagingOptions	= array();
	/**	@var	string				$templates		List of Templates for List and List Items */
	private $templates		= array();
	/**	@var	array				$transformator	Array of Transformator Object and Method */
	private $transformator	= array();
	/**	@var	Core_View			$view			Basic View Object */
	private $view			= null;
	/** @var	string				$link			Link Name of List Items */
	private $link			= "";
	/**
	 *	Constructor.
	 * 	@access		public
	 *	@param		string		$collection		Class Name of Statement Collection to use
	 *	@return		void
	 */
	function __construct( $collection )
	{
		$this->registry	= Framework_Krypton_Core_Registry::getInstance();
		$config			= $this->registry->get( 'config' );
		$this->view		= new Core_View();
		$this->logic	= new Logic_List( $collection, $config['config.table_prefix'] );
		$this->setTransformator( $this, 'transformItem' );
	}
	
	/**
	 *	Adds an Actions by Name and Label.
	 * 	@access		public
	 *	@param		array		$actions		Associative Array of Action Names and Labels
	 *	@return		void
	 */
	public function addAction( $name, $label )
	{
		$this->actions[$name]	= $label;
	}
	
	/**
	 *	Adds a Component by Name and Arguments.
	 * 	@access		public
	 *	@param		string		$name			Name of Component
	 *	@param		array		$arguments		Component Arguments
	 *	@return		void
	 */
	public function addComponent( $name, $arguments = false )
	{
		$this->logic->addComponent( $name, $arguments );
	}

	/**
	 *	Builds Select Box of Actions.
	 * 	@access		protected
	 *	@return		string
	 */
	protected function buildActions()
	{
		$select	= "";
		if( count( $this->actions ) )
		{
			$select = $this->view->html->Select( 'action', $this->actions, 'sel', false, $this->actionForm );
		}
		return $select;
	}
	
	/**
	 *	Builds Paging of Session Keys are set, Rowcount is larger than Limit and Paging Templates is set.
	 * 	@access		protected
	 *	@param		int			$count			Rowcount of Query Result
	 *	@return		string
	 */
	protected function buildPaging( $count )
	{
		$session	= $this->registry->get( 'session' );
		$paging		= "";
		if( $this->limitKey && $this->offsetKey )
		{
			if( $count <= $session->get( $this->offsetKey ) )
			{
				$session->set( $this->offsetKey, 0 );
			}
			$limit	= $session->get( $this->limitKey );
			$offset	= $session->get( $this->offsetKey );
			$this->logic->addComponent( 'Limit', array( $offset, $limit ) );
			if( $count > $limit && isset( $this->templates[2] ))
			{
				$pages	= $this->view->buildPaging( $count, $limit, $offset, $this->pagingOptions );
				$paging	= $this->view->loadTemplate( $this->templates[2], array( "pages" => $pages ) );
			}
		}
		return $paging;
	}
	
	/**
	 *	Builds List..
	 * 	@access		public
	 *	@param		bool		$verbose		Show Query Information
	 *	@return		string
	 */
	public function getList( $verbose = false )
	{
		$dbc	= $this->registry->get( 'dbc' );
		$list	= "";
		if( count( $this->templates ) < 2 )
		{
			$this->messenger->noteFailure( "No Templates defined." );
			return $list;
		}
		$count	= $this->logic->getCount( $dbc, $verbose );
		if( $count )
		{
			$ui['paging']	= $this->buildPaging( $count );
			$i = 0;
			$items	= array();
			$transformator	= $this->transformator[0];
			$method	= $this->transformator[1];
			$list	= $this->logic->getList( $dbc, $verbose );
			foreach( $list as $entry )
			{
				$item = $transformator->$method( $entry, $this->link );
				$item['style']	= ++$i % 2 ? 'list1' : 'list2';
				$items[]	= $this->view->loadTemplate( $this->templates[1], $item );
			}
			$ui['list']	= implode( "\n", $items );
			$ui['actions']	= $this->buildActions();
			$ui['caption']	= $this->view->html->TableCaption( $this->caption, 'list' );
			$ui['heads']	= $this->view->html->TableHeads( $this->heads );
			$ui['colgroup']	= $this->view->html->ColumnGroup( $this->colgroup );
			$list	= $this->view->loadTemplate( $this->templates[0], $ui );
		}
		return $list;
	}
	
	/**
	 *	Sets Name for Form executed by Action Selection.
	 * 	@access		public
	 *	@param		string		$name		Name of Form for Action Select
	 *	@return		void
	 */
	public function setActionForm( $name )
	{
		$this->actionForm	= $name;
	}

	/**
	 *	Sets Actions by associative Array.
	 * 	@access		public
	 *	@param		array		$actions		Associative Array of Action Names and Labels
	 *	@return		void
	 */
	public function setActions( $actions )
	{
		foreach( $actions as $name => $label )
			$this->addAction( $name, $label );
	}

	/**
	 *	Sets Table Caption.
	 *	@access		public
	 *	@param		array		$caption		Label of Table Caption
	 *	@return		void
	 */
	public function setCaption( $caption )
	{
		$this->caption	= $caption;
	}
	
	/**
	 *	Sets Column Widths.
	 *	@access		public
	 *	@param		array		$colgroup		Column Widths comma separated
	 *	@return		void
	 */
	public function setColumnGroup( $colgroup )
	{
		$this->colgroup	= $colgroup;
	}

	/**
	 *	Sets Column Headings.
	 *	@access		public
	 *	@param		array		$heads			Array of Column Heads
	 *	@return		void
	 */
	public function setHeads( $heads )
	{
		$this->heads	= $heads;
	}

	/**
	 *	Sets Link of List Items.
	 *	@access		public
	 *	@param		string		$link			Link Name of List Items
	 *	@return		void 
	 */
	public function setLink( $link )
	{
		$this->link	= $link;
	}
	
	/**
	 *	Sets Session Key of Limit.
	 *	@access		public
	 *	@param		string		$key			Session Key of Limit
	 *	@return		void
	 */
	public function setLimitKey( $key )
	{
		$this->limitKey	= $key;
	}

	/**
	 *	Sets Session Key of Offset.
	 *	@access		public
	 *	@param		string		$key		Session Key of Offset
	 *	@return		void
	 */
	public function setOffsetKey( $key )
	{
		$this->offsetKey	= $key;
	}
	
	/**
	 *	Sets Options for Paging System.
	 *	@access		public
	 *	@param		array		$options		Array with Options for Paging System
	 *	@return		void
	 */
	public function setPagingOptions( $options )
	{
		$this->pagingOptions	= $options;
	}
	
	/**
	 *	Sets Templates for List, List Items and Paging System.
	 *	@access		public
	 *	@param		string		$listTemplate		Template of List
	 *	@param		string		$itemTemplate		Template of List Item
	 *	@param		string		$pagingTemplate		Template of Paging System
	 *	@return		void
	 */
	public function setTemplates( $listTemplate, $itemTemplate, $pagingTemplate = false )
	{
		$this->templates	= array( $listTemplate, $itemTemplate, $pagingTemplate );
	}
	
	/**
	 *	Sets Callback for Transformation of Item Values.
	 *	@access		public
	 *	@param		Object		$object				Transformator Object
	 *	@param		string		$method				Transformator Method
	 *	@return		void
	 */
	public function setTransformator( $object, $method )
	{
		$this->transformator	= array( $object, $method );	
	}
	
	/**
	 *	Dummy Callback for Transformation.
	 *	@access		public
	 *	@param		array		$item				Array of Values
	 *	@return		array
	 */
	protected function transformItem( $item )
	{
		return $item;
	}
}
?>
