<?php
/**
 *	@category		cmClasses
 *	@package		Net.Socket.Stream
 */
/**
 *	@category		cmClasses
 *	@package		Net.Socket.Stream
 */
class Net_Socket_Stream_Client
{
	protected $host;
	protected $port;
	protected $socket;

	public function __construct( $host, $port = 8000 )
	{
		$this->host		= $host;
		$this->port		= $port;
		$this->address	= 'tcp://'.$host.':'.$port;
	}

	public function getHost()
	{
		return $this->host;
	}

	public function getPort()
	{
		return $this->port;
	}

	public function getResponse( Net_Socket_Stream_Package $request )
	{
		$socket	= stream_socket_client( $this->address, $errno, $errstr, 30 );
#		stream_set_blocking( $socket, 0 );
		if( !$socket )
			throw new RuntimeException( $errstr.' ('.$errno.')' );

		$buffer		= "";
		$serial		= $request->toSerial();
		fwrite( $socket, $serial );
		while( !feof( $socket ) )
			$buffer	.= fgets( $socket, 1024 );
		fclose( $socket );
		$response	= new Net_Socket_Stream_Package();
		$response->fromSerial( $buffer );
		return $response;
	}
}
?>