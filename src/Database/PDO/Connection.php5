<?php
/**
 *	Enhanced PDO Connection.
 *
 *	Copyright (c) 2007-2011 Christian Würker (ceusmedia.com)
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
 *	@package		Database.PDO
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2011 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.03.2007
 *	@version		$Id$
 */
/**
 *	Enhanced PDO Connection.
 *	@category		cmClasses
 *	@package		Database.PDO
 *	@uses			Exception_SQL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2011 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.03.2007
 *	@version		$Id$
 *	@todo			Code Documentation
 */
class Database_PDO_Connection extends PDO 
{
	protected $driver				= NULL;
	public $numberExecutes			= 0;
	public $numberStatements		= 0;
	public $logFileErrors			= NULL;															//  eg. logs/db/pdo/error.log
	public $logFileStatements		= NULL;															//  eg. logs/db/pdo/query.log
	protected $openTransactions		= 0;
	protected $innerTransactionFail	= FALSE;														//  Flag: inner (nested) Transaction has failed
	public static $errorTemplate	= "{time}: PDO:{pdoCode} SQL:{sqlCode} {sqlError} ({statement})\n";
	public static $defaultOptions	= array(
		PDO::ATTR_ERRMODE	=> PDO::ERRMODE_EXCEPTION,
	);
	
	/**
	 *	Constructor, establishs Database Connection using a DSN. Set Error Handling to use Exceptions.
	 *	@access		public
	 *	@param		string		$dsn			Data Source Name
	 *	@param		string		$username		Name of Database User
	 *	@param		string		$password		Password of Database User
	 *	@param		array		$driverOptions	Array of Driver Options
	 *	@return		void
	 *	@see		http://php.net/manual/en/pdo.drivers.php
	 */
	public function __construct( $dsn, $username = NULL, $password = NULL, $driverOptions = array() )
	{
		$options	= $driverOptions + self::$defaultOptions;										//  extend given options by default options
		parent::__construct( $dsn, $username, $password, $options );
		$this->driver	= $this->getAttribute( PDO::ATTR_DRIVER_NAME );								//  note name of used driver
	}

/*	for PHP 5.3.6+
	public function __destruct(){
		if( $this->openTransactions )
			throw new Exception_Runtime( 'Transaction not closed' );
	}*/

	/**
	 *	Starts a Transaction.
	 *	@access		public
	 *	@return		bool
	 */
	public function beginTransaction()
	{
		$this->openTransactions++;													//  increase Transaction Counter
		if( $this->openTransactions == 1)											//  no Transaction is open
			parent::beginTransaction();												//  begin Transaction
		return TRUE;
	}

	/**
	 *	Commits a Transaction.
	 *	@access		public
	 *	@return		bool
	 */
	public function commit()
	{
		if( !$this->openTransactions )												//  there has been an inner RollBack or no Transaction was opened
			return FALSE;															//  ignore Commit
		if( $this->openTransactions == 1)											//  commit of an outer Transaction
		{
			if( $this->innerTransactionFail )										//  remember about failed inner Transaction
			{
				$this->rollBack();													//  rollback outer Transaction instead of committing
//				throw new RuntimeException( 'Commit failed due to a nested transaction failed' );
				return FALSE;														//  indicated that the Transaction has failed
			}
			else																	//  no failed inner Transaction
				parent::commit();													//  commit Transaction
		}
		$this->openTransactions--;													//  decrease Transaction Counter
		return TRUE;
	}

	/**
	 *	Executes a Statement and returns Number of affected Rows.
	 *	@access		public
	 *	@param		string		$statement			SQL Statement to execute
	 *	@return		int
	 */
	public function exec( $statement )
	{
		$this->logStatement( $statement );
		try
		{
			$this->numberExecutes++;
			$this->numberStatements++;
			return parent::exec( $statement );
		}
		catch( PDOException $e )
		{
			$this->logError( $e, $statement );										//  logs Error and throws SQL Exception
		}
	}

	/**
	 *	Returns PDO driver used for connection, detected only if the DSN was given as object.
	 *	@access		public
	 *	@return		string|NULL		Database Driver (dblib|firebird|informix|mysql|mssql|oci|odbc|pgsql|sqlite|sybase)
	 */ 
	public function getDriver(){
		return $this->driver;
	}

	public function getOpenTransactions(){
		return $this->openTransactions;
	}

	/**
	 *	Notes Information from PDO Exception in Error Log File and throw SQL Exception.
	 *	@access		protected
	 *	@param		PDOException	$e				PDO Exception thrown by invalid SQL Statement
	 *	@param		string			$statement		SQL Statement which originated PDO Exception
	 *	@return		void
	 */
	protected function logError( Exception $exception, $statement )
	{
		if( !$this->logFileErrors )
			return;
//			throw $exception;
		$info		= $exception->errorInfo;
		$sqlError	= $info[2];
		$sqlCode	= $info[1];
		$pdoCode	= $info[0];
		$message	= $exception->getMessage();
		$statement	= preg_replace( "@\r?\n@", " ", $statement );	
		$statement	= preg_replace( "@  +@", " ", $statement );	
		
		$note	= self::$errorTemplate;
		$note	= str_replace( "{time}", time(), $note );
		$note	= str_replace( "{sqlError}", $sqlError, $note );
		$note	= str_replace( "{sqlCode}", $sqlCode, $note );
		$note	= str_replace( "{pdoCode}", $pdoCode, $note );
		$note	= str_replace( "{message}", $message, $note );
		$note	= str_replace( "{statement}", $statement, $note );
				
		error_log( $note, 3, $this->logFileErrors );
		throw new Exception_SQL( $info[2], $info[1], $info[0] );
	}

	/**
	 *	Notes a SQL Statement in Statement Log File.
	 *	@access		protected
	 *	@param		string		$statement		SQL Statement
	 *	@return		void
	 */
	protected function logStatement( $statement )
	{
		if( !$this->logFileStatements )
			return;
		$statement	= preg_replace( "@(\r)?\n@", " ", $statement );
		$message	= time()." ".getEnv( 'REMOTE_ADDR' )." ".$statement."\n";
		error_log( $message, 3, $this->logFileStatements);
	}
	
	public function prepare( $statement, $driverOptions = array() )
	{
		$this->numberStatements++;
		$this->logStatement( $statement );
		return parent::prepare( $statement, $driverOptions );
	}

	public function query( $statement, $fetchMode = PDO::FETCH_ASSOC )
	{
		$this->logStatement( $statement );
		$this->numberStatements++;
		try
		{
			return parent::query( $statement, $fetchMode );
		}
		catch( PDOException $e )
		{
			$this->logError( $e, $statement );										//  logs Error and throws SQL Exception
		}
	}

	/**
	 *	Rolls back a Transaction.
	 *	@access		public
	 *	@return		bool
	 */
	public function rollBack()
	{
		if( !$this->openTransactions )												//  there has been an inner RollBack or no Transaction was opened
			return FALSE;															//  ignore Commit
		if( $this->openTransactions == 1 )											//  only 1 Transaction open
		{
			parent::rollBack();														//  roll back Transaction
			$this->innerTransactionFail	= FALSE;									//  forget about failed inner Transactions
		}
		else
			$this->innerTransactionFail	= TRUE;										//  note about failed inner Transactions
		$this->openTransactions--;													//  decrease Transaction Counter
		return TRUE;
	}

	/**
	 *	Sets File Name of Error Log.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Statement Error File
	 *	@return		void
	 */
	public function setErrorLogFile( $fileName )
	{
		$this->logFileErrors	= $fileName;
		if( $fileName && !file_exists( dirname( $fileName ) ) )
			mkDir( dirname( $fileName ), 0700, TRUE );
	}

	/**
	 *	Sets File Name of Statement Log.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Statement Log File
	 *	@return		void
	 */
	public function setStatementLogFile( $fileName )
	{
		$this->logFileStatements	= $fileName;
		if( $fileName && !file_exists( dirname( $fileName ) ) )
			mkDir( dirname( $fileName ), 0700, TRUE );
	}
}
?>
