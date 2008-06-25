<?php
import( 'de.ceus-media.xml.dom.Node' );
import( 'de.ceus-media.xml.dom.Builder' );
/**
 *	Serializer for Data Object into XML.
 *	@package		xml.dom
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.12.2005
 *	@version		0.6
 */
/**
 *	Serializer for Data Object into XML.
 *	@package		xml.dom
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.12.2005
 *	@version		0.6
 */
class XML_DOM_ObjectSerializer
{
	/**
	 *	Builds XML String from an Object.
	 *	@access		public
	 *	@param		mixed		$object		Object to serialize
	 *	@param		string		$encoding	Encoding Type
	 *	@return		string
	 */
	public function serialize( $object, $encoding = "utf-8" )
	{
		$root	= new XML_DOM_Node( "object" );
		$root->setAttribute( 'class', get_class( $object ) );
		$vars	= get_object_vars( $object );
		$this->serializeVarsRec( $vars, $root );
		$builder	= new XML_DOM_Builder();
		$serial		= $builder->build( $root, $encoding );
		return $serial;
	}
	
	/**
	 *	Adds XML Nodes to a XML Tree by their Type while supporting nested Arrays.
	 *	@access		protected
	 *	@param		array			$array		Array of Vars to add
	 *	@param		XML_DOM_Node	$node		current XML Tree Node
	 *	@return		string
	 */
	protected function serializeVarsRec( $array, &$node )
	{
		foreach( $array as $key => $value)
		{
			switch( gettype( $value ) )
			{
				case 'NULL':
					$child	=& new XML_DOM_Node( "null" );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'boolean':
					$child	=& new XML_DOM_Node( "boolean", (int) $value );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'string':
					$child	=& new XML_DOM_Node( "string", $value );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'integer':
					$child	=& new XML_DOM_Node( "integer", $value );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'double':
					$child	=& new XML_DOM_Node( "double", $value );
					$child->setAttribute( "name", $key );
					$node->addChild( $child );
					break;
				case 'array':
					$child	=& new XML_DOM_Node( "array" );
					$child->setAttribute( "name", $key );
					$this->serializeVarsRec( $value, $child );
					$node->addChild( $child );
					break;
				case 'object':
					$child	=& new XML_DOM_Node( "object" );
					$child->setAttribute( "name", $key );
					$child->setAttribute( "class", get_class( $value ) );
					$vars	= get_object_vars( $value );
					$this->serializeVarsRec( $vars, $child );
					$node->addChild( $child );
					break;
			}
		}
	}
}
?>