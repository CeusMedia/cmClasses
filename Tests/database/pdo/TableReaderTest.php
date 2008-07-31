<?php
/**
 *	TestUnit of Database_PDO_TableReader.
 *	@package		Tests.database.pdo
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_PDO_Connection
 *	@uses			Database_PDO_TableReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.07.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
require_once( 'Tests/TestObject.php5' );
import( 'de.ceus-media.database.pdo.Connection' );
import( 'de.ceus-media.database.pdo.TableReader' );
/**
 *	TestUnit of Database_PDO_TableReader.
 *	@package		Tests.database.pdo
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_PDO_Connection
 *	@uses			Database_PDO_TableReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.07.2008
 *	@version		0.1
 */
class Tests_Database_PDO_TableReaderTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->host		= "localhost";
		$this->port		= 3306;
		$this->username	= "ceus";
		$this->password	= "ceus";
		$this->database	= "test";
		$this->path		= dirname( __FILE__ )."/";
		$this->errorLog	= $this->path."errors.log";
		$this->queryLog	= $this->path."queries.log";

		$this->dsn = "mysql:host=".$this->host.";dbname=".$this->database;

		$this->connection	= new Database_PDO_Connection( $this->dsn, $this->username, $this->password, $options );
		$this->connection->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );
		$this->connection->setAttribute( PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE );
		$this->connection->setErrorLogFile( $this->errorLog );
		$this->connection->setStatementLogFile( $this->queryLog );
		
		$this->tableName	= "transactions";
		$this->columns	= array(
			'id',
			'topic',
			'label',
			'timestamp',
		);
		$this->primaryKey	= $this->columns[0];
		$this->indices	= array(
			'topic',
			'label'
		);
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->mysql	= mysql_connect( $this->host, $this->username, $this->password ) or die( mysql_error() );
		mysql_select_db( $this->database );
		$sql	= file_get_contents( $this->path."createTable.sql" );
		foreach( explode( ";", $sql ) as $part )
			if( trim( $part ) )
				mysql_query( $part ) or die( mysql_error() );

		$this->reader	= new Database_PDO_TableReader( $this->connection, $this->tableName, $this->columns, $this->primaryKey );
		$this->reader->setIndices( $this->indices );
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		@unlink( $this->errorLog );
		@unlink( $this->queryLog );
		mysql_query( "DROP TABLE transactions" );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct1()
	{
		$reader		= new Database_PDO_TableReader( $this->connection, "table", array( 'col1', 'col2' ), 'col2', 1 );

		$assertion	= 'table';
		$creation	= $reader->getTableName();
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'col1', 'col2' );
		$creation	= $reader->getColumns();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'col2';
		$creation	= $reader->getPrimaryKey();
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'col2' => 1 );
		$creation	= $reader->getFocus();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct2()
	{
		$reader		= new Database_PDO_TableReader( $this->connection, $this->tableName, $this->columns, $this->primaryKey, 1 );
	
		$assertion	= array( 'id' => 1 );
		$creation	= array_slice( $reader->get(), 0, 1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'count'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCount()
	{
		$assertion	= 1;
		$creation	= $this->reader->count();
		$this->assertEquals( $assertion, $creation );

		$this->connection->query( "INSERT INTO transactions (label) VALUES ('countTest');" );

		$assertion	= 2;
		$creation	= $this->reader->count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $this->reader->count( array( 'label' => 'countTest' ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= $this->reader->count( array( 'label' => 'not_existing' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'defocus'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDefocus()
	{
		$this->reader->focusPrimary( 2 );
		$this->reader->focusIndex( 'topic', 'test' );
		$this->reader->defocus( TRUE );
		
		$assertion	= array( 'topic' => 'test' );
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->defocus();

		$assertion	= array();
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFind1()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$result		= $this->reader->find();

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFind2()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$result		= $this->reader->find( array( "*" ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFind3()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$result		= $this->reader->find( "*" );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFind4()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$result		= $this->reader->find( array( "id" ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'id' );
		$creation	= array_keys( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFind5()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$result		= $this->reader->find( "id" );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'id' );
		$creation	= array_keys( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWithOrder()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$result		= $this->reader->find( array( 'id' ), array(), array( 'id' => 'ASC' ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			array( 'id' => 1 ),
			array( 'id' => 2 ),
		);
		$creation	= $result;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWithLimit()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$result		= $this->reader->find( array( 'id' ), array(), array( 'id' => 'DESC' ), array( 0, 1 ) );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( array( 'id' => 2 ) );
		$creation	= $result;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWithFocus1()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );
		$this->reader->focusIndex( 'topic', 'start' );							//  will be ignored
		$result		= $this->reader->find( array( 'id' ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWithFocus2()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );
		$this->reader->focusPrimary( 1 );										//  will be ignored
		$result		= $this->reader->find( array( 'id' ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWithFocus3()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$this->reader->focusIndex( 'topic', 'test' );							//  will be ignored
		$this->reader->focusPrimary( 1, FALSE );								//  will be ignored
		$result		= $this->reader->find( array( 'id' ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'findWhereIn'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereIn()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findWhereInTest');" );

		$result		= $this->reader->findWhereIn( array( 'id' ), "topic", array( 'start', 'test' ), array( 'id' => 'ASC' ) ); 

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->findWhereIn( array( 'id' ), "topic", array( 'test' ) ); 

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'findWhereIn'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereInWithLimit()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findWhereInTest');" );

		$result		= $this->reader->findWhereIn( array( 'id' ), "topic", array( 'start', 'test' ), array( 'id' => "DESC" ), array( 0, 1 ) ); 

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'findWhereInAnd'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereInException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->findWhereIn( array( 'not_valid' ), "id", 1 );
	}

	/**
	 *	Tests Exception of Method 'findWhereInAnd'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereInException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->findWhereIn( "*", "not_valid", 1 );
	}

	/**
	 *	Tests Method 'findWhereInAnd'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereInAnd()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findWhereInAndTest');" );
		$result		= $this->reader->findWhereInAnd( array( 'id' ), "topic", array( 'test' ), array( "label" => "findWhereInAndTest" ) ); 

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->findWhereInAnd( array( 'id' ), "topic", array( 'start' ), array( "label" => "findWhereInAndTest" ) ); 

		$assertion	= 0;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'findWhereIn'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereInAndWithFocus()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findWhereInAndTest');" );

		$this->reader->focusIndex( 'topic', 'test' );								//  will be ignored
		$result		= $this->reader->findWhereInAnd( array( 'id' ), "topic", array( 'start', 'test' ), array( "label" => "findWhereInAndTest" ), array( 'id' => 'ASC' ) ); 

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->findWhereInAnd( array( 'id' ), "topic", array( 'start', 'test' ) ); 

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->findWhereInAnd( array( 'id' ), "topic", array( 'start', 'test' ), array( "label" => "findWhereInAndTest" ), array( 'id' => 'ASC' ) ); 

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->findWhereInAnd( array( 'id' ), "topic", array( 'test' ), array( "label" => "findWhereInAndTest" ), array( 'id' => 'ASC' ) ); 

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->findWhereInAnd( array( 'id' ), "topic", array( 'start' ), array( "label" => "findWhereInAndTest" ), array( 'id' => 'ASC' ) ); 

		$assertion	= 0;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'focusIndex'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFocusIndex()
	{
		$this->reader->focusIndex( 'topic', 'test' );
		$assertion	= array(
			'topic' => 'test'
			);
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusIndex( 'label', 'text' );
		$assertion	= array(
			'topic' => 'test',
			'label'	=> 'text'
		);
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusIndex( 'id', 1 );
		$assertion	= array(
			'topic' => 'test',
			'label'	=> 'text',
			'id'	=> 1
		);
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'focusIndex'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFocusIndexException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->focusIndex( 'not_an_index', 'not_relevant' );
	}

	/**
	 *	Tests Method 'focusPrimary'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFocusPrimary()
	{
		$this->reader->focusPrimary( 2 );
		$assertion	= array( 'id' => 2 );
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusPrimary( 1 );
		$assertion	= array( 'id' => 1 );
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithPrimary1()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findWhereInAndTest');" );
		$this->reader->focusPrimary( 1 );
		$result		= $this->reader->get( FALSE );
				
		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0]['id'] );
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusPrimary( 2 );
		$result		= $this->reader->get();
		
		$assertion	= 4;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result['id'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithPrimary2()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findWhereInAndTest');" );
		$this->reader->focusIndex( $this->primaryKey, 1 );
		$result		= $this->reader->get( FALSE );
				
		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0]['id'] );
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusPrimary( 2 );
		$result		= $this->reader->get();
		
		$assertion	= 4;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result['id'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithIndex()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('start','getWithIndexTest');" );
		$this->reader->focusIndex( 'topic', 'start' );
		$result		= $this->reader->get();

		$assertion	= 4;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->get( FALSE );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusIndex( 'label', 'getWithIndexTest' );
		$result		= $this->reader->get( FALSE );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithOrders()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('start','getWithOrderTest');" );
		$this->reader->focusIndex( 'topic', 'start' );
		$result		= $this->reader->get( FALSE, array( 'id' => "ASC" ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[1]['id'];
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->get( FALSE, array( 'id' => "DESC" ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result[1]['id'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithLimit()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('start','getWithLimitTest');" );
		$this->reader->focusIndex( 'topic', 'start' );
		$result		= $this->reader->get( FALSE, array( 'id' => "ASC" ), array( 0, 1 ) );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->get( FALSE, array( 'id' => "ASC" ), array( 1, 1 ) );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithNoFocusException()
	{
		$this->setExpectedException( 'RuntimeException' );
		$this->reader->get();
	}

	/**
	 *	Tests Method 'getColumns'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetColumns()
	{
		$assertion	= $this->columns;
		$creation	= $this->reader->getColumns();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getDBConnection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDBConnection()
	{
		$assertion	= $this->connection;
		$creation	= $this->reader->getDBConnection();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFocus'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFocus()
	{
		$this->reader->focusPrimary( 1 );
		$assertion	= array(
			'id' => 1
		);
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusIndex( 'topic', 'start' );
		$assertion	= array(
			'id'	=> 1,
			'topic' => 'start'
		);
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusPrimary( 2, FALSE );
		$assertion	= array(
			'topic' => 'start',
			'id' => 2
		);
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusPrimary( 2, TRUE );
		$assertion	= array(
			'id' => 2
		);
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getIndices'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIndices()
	{
		$indices	= array( 'topic', 'timestamp' );
		$this->reader->setIndices( $indices );

		$assertion	= $indices;
		$creation	= $this->reader->getIndices();
		$this->assertEquals( $assertion, $creation );

		$indices	= array( 'topic' );
		$this->reader->setIndices( $indices );

		$assertion	= $indices;
		$creation	= $this->reader->getIndices();
		$this->assertEquals( $assertion, $creation );

		$indices	= array();
		$this->reader->setIndices( $indices );

		$assertion	= $indices;
		$creation	= $this->reader->getIndices();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPrimaryKey'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPrimaryKey()
	{
		$assertion	= 'id';
		$creation	= $this->reader->getPrimaryKey();
		$this->assertEquals( $assertion, $creation );

		$this->reader->setPrimaryKey( 'timestamp' );
		$assertion	= 'timestamp';
		$creation	= $this->reader->getPrimaryKey();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTableName'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTableName()
	{
		$assertion	= "transactions";
		$creation	= $this->reader->getTableName();
		$this->assertEquals( $assertion, $creation );

		$this->reader->setTableName( "other_table" );

		$assertion	= "other_table";
		$creation	= $this->reader->getTableName();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'isFocused'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsFocused()
	{
		$assertion	= FALSE;
		$creation	= $this->reader->isFocused();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusPrimary( 2 );
		$assertion	= TRUE;
		$creation	= $this->reader->isFocused();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusIndex( 'topic', 'start' );
		$assertion	= TRUE;
		$creation	= $this->reader->isFocused();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusPrimary( 1, FALSE );
		$assertion	= TRUE;
		$creation	= $this->reader->isFocused();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusPrimary( 1 );
		$assertion	= TRUE;
		$creation	= $this->reader->isFocused();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setColumns'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetColumns()
	{
		$columns	= array( 'col1', 'col2', 'col3' );

		$this->reader->setColumns( $columns );
		
		$assertion	= $columns;
		$creation	= $this->reader->getColumns();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setColumns'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetColumnsException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->setColumns( "string" );
	}

	/**
	 *	Tests Exception of Method 'setColumns'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetColumnsException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->setColumns( array() );
	}

	/**
	 *	Tests Method 'setDBConnection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetDBConnection()
	{
		$dbc		= new PDO( $this->dsn, $this->username, $this->password );
		$this->reader->setDBConnection( $dbc );

		$assertion	= $dbc;
		$creation	= $this->reader->getDBConnection();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setDBConnection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetDBConnection1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->setDBConnection( "string" );
	}

	/**
	 *	Tests Exception of Method 'setDBConnection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetDBConnection2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->setDBConnection( new TestObject );
	}

	/**
	 *	Tests Method 'setIndices'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetIndices()
	{
		$indices	= array( 'topic', 'timestamp' );
		$this->reader->setIndices( $indices );

		$assertion	= $indices;
		$creation	= $this->reader->getIndices();
		$this->assertEquals( $assertion, $creation );

		$indices	= array( 'topic' );
		$this->reader->setIndices( $indices );

		$assertion	= $indices;
		$creation	= $this->reader->getIndices();
		$this->assertEquals( $assertion, $creation );

		$indices	= array();
		$this->reader->setIndices( $indices );

		$assertion	= $indices;
		$creation	= $this->reader->getIndices();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setIndices'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetIndicesException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->setIndices( array( 'not_existing' ) );
	}

	/**
	 *	Tests Exception of Method 'setIndices'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetIndicesException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->setIndices( array( 'id' ) );
	}

	/**
	 *	Tests Method 'setPrimaryKey'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPrimaryKey()
	{
		$this->reader->setPrimaryKey( 'topic' );
		
		$assertion	= 'topic';
		$creation	= $this->reader->getPrimaryKey();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setPrimaryKey'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPrimaryKeyException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->setPrimaryKey( 'not_existing' );
	}

	/**
	 *	Tests Method 'setTableName'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetTableName()
	{
		$tableName	= "other_table";
		$this->reader->setTableName( $tableName );
		
			
		$assertion	= $tableName;
		$creation	= $this->reader->getTableName();
		$this->assertEquals( $assertion, $creation );
	}
}
?>