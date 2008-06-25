<?php
/**
 *	Calculates Integral with Sampling Nodes within a compact Interval.
 *	@package		math.analysis
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Calculates Integral with Sampling Nodes within a compact Interval.
 *	@package		math.analysis
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class Math_Analysis_Integration
{
	/**	@var		Math_Formula			$formula		Formula to integrate */
	protected $formula;
	/**	@var		Math_CompactInterval	$interval		Interval to integrate within */
	protected $interval;
	/**	@var		int						$nodes			Amount of Sampling Nodes to use */
	protected $nodes;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Math_Formula			$formula		Formula to integrate
	 *	@param		Math_CompactInterval	$interval		Interval to integrate within
	 *	@param		int						$nodes			Amount of Sampling Nodes to use
	 *	@return		void
	 */
	public function __construct( $formula, $interval, $nodes )
	{
		$this->setFormula( $formula );
		$this->setInterval( $interval );
		$this->setNodes( $nodes );
	}

	/**
	 *	Returns set Formula.
	 *	@access		public
	 *	@return		Math_Formula
	 */
	public function getFormula()
	{
		return $this->formula;
	}
	
	/**
	 *	Returns set Interval.
	 *	@access		public
	 *	@return		Math_CompactInterval
	 */
	public function getInterval()
	{
		return $this->interval;
	}
	
	/**
	 *	Returns quantity of Sampling Nodes.
	 *	@access		public
	 *	@return		Math_Formula
	 */
	public function getNodes()
	{
		return $this->nodes;
	}
	
	/**
	 *	Returns an array of Sampling Nodes.
	 *	@access		public
	 *	@return		array
	 */
	public function getSamplingNodes()
	{
		$nodes	= array();
		$start	= $this->interval->getStart();
		$distance	= $this->getNodeDistance();
		for( $i = 0; $i<$this->getNodes(); $i++ )
		{
			$x = $start + $i * $distance;
			$nodes[] = $x;		
		}
		return $nodes;
	}
	
	/**
	 *	Calculates the distance between two Sampling Nodes.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getNodeDistance()
	{
		$distance	= $this->interval->getDiameter() / ( $this->getNodes() - 1 );
		return $distance;
	}

	/**
	 *	Calculates integrational sum of Formula within the Interval by using Sampling Nodes.
	 *	@access		public
	 *	@return		mixed
	 */
	public function integrate()
	{
		$sum	= 0;
		$nodes	= $this->getNodes()-1;
		$distance	= $this->getNodeDistance();
		$start	= $this->interval->getStart();
		for( $i=0; $i<$nodes; $i++ )
		{
			$x		= $start + $distance * ( $i + $distance / 2 );
			$y		= $this->formula->getValue( $x );
			$sum	+= $y;
		}
		return $distance * $sum;
	}

	/**
	 *	Sets Formula.
	 *	@access		public
	 *	@param		Math_Formula			$formula		Formula to integrate
	 *	@return		void
	 */
	public function setFormula( $formula )
	{
		$this->formula	= $formula;	
	}
	
	/**
	 *	Sets Interval.
	 *	@access		public
	 *	@param		Math_CompactInterval	$interval		Interval to integrate within
	 *	@return		void
	 */
	public function setInterval( $interval )
	{
		$this->interval	= $interval;	
	}
	
	/**
	 *	Sets amount of Sampling Nodes to use.
	 *	@access		public
	 *	@param		int						$nodes			Amount of Sampling Nodes to use
	 *	@return		void
	 */
	public function setNodes( $nodes )
	{
		if( $nodes < 2 )
			throw new InvalidArgumentException( 'Number of Sampling Points must be greater than 1.' );
		$this->nodes = $nodes;
	}
}
?>