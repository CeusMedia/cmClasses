<?php
import( 'de.ceus-media.adt.tree.BinaryNode' );
/**
 *	Balanced Binary Tree.
 *	@package		adt.tree
 *	@extends		BinaryTree
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Balanced Binary Tree.
 *	@package		adt.tree
 *	@extends		BinaryTree
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class ADT_Tree_BalanceBinaryNode extends ADT_Tree_BinaryNode
{
	/**	@var		int			balance		Balance Tolerance */
	protected $balance;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int			balance		Balance Tolerance
	 *	@param		mixed		value		Value of Node
	 *	@return		void
	 */
	public function __construct( $balance, $value = false )
	{
		$this->balance	= $balance;
		parent::__construct( $value );
	}

	/**
	 *	Adds a new Node To Tree.
	 *	@access		public
	 *	@param		mixed		value		Value of new Node
	 *	@return		int
	 */
	public function add( $value )
	{
		if( !isset( $this->value ) )
			return $this->value = $value;
		if( $value == $this->value )
			return -1;
		if( $value < $this->value )
		{
			if( $this->left )
				$this->left->add( $value );
			else
				$this->left =& new ADT_Tree_BalanceBinaryNode( $this->balance, $value );
		}
		else if( $value > $tree->value )
		{
			if( $this->right )
				$this->right->add( $value );
			else
				$this->right =& new ADT_Tree_BalanceBinaryNode( $this->balance, $value );
		}
		if ($this->balance)
		{
			$bf = $this->getBalance();
			if( $bf <= -1 * $this->balance || $bf >= $this->balance )
				$this->balanceTree();
		}
	}

	/**
	 *	Returns  current Balance.
	 *	@access		public
	 *	@param		mixed		value		Value of new Node
	 *	@return		int
	 */
	public function getBalance()
	{
		$la = $this->left ? $this->left->getHeight() : 0;
		$lb = $this->right? $this->right->getHeight(): 0;
		return ( $la - $lb );
	}

	/**
	 *	Balances unbalanced Tree with Rotations.
	 *	@access		public
	 *	@return		void
	 */
	protected function balanceTree()
	{
		$bf	= $this->getBalance();
		if( $bf >= $this->balance ) 												// LR or LL rotation
		{
			$ll_height	= $this->left->left ? $this->left->left->getHeight() : 0;
			$lr_height	= $this->left->right? $this->left->right->getHeight(): 0;
			if( $ll_height < $lr_height )
				$this->left->rotateRR(); 										// LR rotation
			$this->rotateLL();
		}
		else if( $bf <= $this->balance )											// RR or RL rotation
		{
			$rr_height	= $this->right->right? $this->right->right->getHeight(): 0;
			$rl_height	= $this->right->left ? $this->right->left->getHeight() : 0;
			if( $rl_height > $rr_height )
				$this->right->rotateLL();										// RR rotation
			$this->rotateRR();
		}
	}

	/**
	 *	Rotates Tree.
	 *	@access		private
	 *	@return		void
	 */
	private function rotateRR()
	{
		$value_before		=& $this->value;
		$left_before		=& $this->left;
		$this->value		=& $this->right->value;
		$this->left			=& $this->right;
		$this->right		=& $this->right->right;
		$this->left->right	=& $this->left->left;
		$this->left->left	=& $left_before;
		$this->left->value	=& $value_before;
	}

	/**
	 *	Rotates Tree.
	 *	@access		private
	 *	@return		void
	 */
	private function rotateLL()
	{
		$value_before		=& $this->value;
		$right_before		=& $this->right;
		$this->value		=& $this->left->value;
		$this->right		=& $this->left;
		$this->left			=& $this->left->left;
		$this->right->left	=& $this->right->right;
		$this->right->right	=& $right_before;
		$this->right->value	=& $value_before;
	}

	/**
	 *	Returns Tree as HTML Table.
	 *	@access		public
	 *	@param		bool		[showBalanceFactor]		Flag: show Balance Factor
	 *	@return		void
	 */
	public function toTable( $showBalanceFactor = false )
	{
		if( $this->left )
			$la	= $this->left->getHeight();
		if( $this->right )
			$lb	= $this->right->getHeight();
		$depth	= $this->getHeight ();
		$color	= 240 - ( 3 * $depth );
		if( $showBalanceFactor )
		{
			$k = $la - $lb;
			if( $k <= -1*$this->balance || $k >= $this->balance )
				$k = "<b style='color:red'>$k</b>";
			$ins_bf = "<b class='small' style='font-weight:normal; font-size:7pt;'>".$k."</b>";
		}
		$code = "<table cellspacing='1' cellpadding='0' border='0'>\n<tr><td colspan='2' align='center' style='background:rgb($color, $color, $color); font-size: 7pt'>".$this->value.$ins_bf."</td></tr>";
		if( $this->left || $this->right )
		{
			$code .= "<tr><td align=center valign=top>";
			if( $this->left )
				$code .= $this->left->toTable( $showBalanceFactor);
			else
				$code .= "&nbsp;";
			$code .= "</td><td align=center valign=top>";
			if( $this->right )
				$code .= $this->right->toTable( $showBalanceFactor );
			else
				$code .= "&nbsp;";
			$code .= "</td></tr>\n";
		}
		$code .= "</table>\n";
		return $code;
	}
}
?>