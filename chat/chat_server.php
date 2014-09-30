<?php
set_time_limit(0);

$HostAddress = "127.0.0.1";
$HostPort = 2500;
$max_clients = 2;

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

if (!(socket_bind($sock, $HostAddress, $HostPort))) {
	echo socket_strerror(socket_last_error());
}

socket_listen($sock, 10);
echo "Listening for connections...\n";

$client_conn = array();

$live = true;
do {
	$read = $client_conn;
	$read[] = $sock;

	socket_select($read, $write, $except, null);
	
	if(in_array($sock, $read)) {
		if (count($client_conn)<$max_clients) {
			$client_conn[] = $new_client = socket_accept($sock);
			socket_getpeername($new_client, $address, $port);
			echo "(".count($client_conn).") Incomming connection from $address : $port \n";
			sendHandshake($new_client);
		} else {
				$dump = socket_accept($sock);
				echo "Incomming connection rejected. Too many clients\n";
				socket_write($dump, "Too many clients, try again later.\n\r");
				socket_close($dump);
		}
	}

	foreach($read as $client) {
		
		if($client != $sock) {
			socket_getpeername($client, $address, $port);
			$input = trim(socket_read($client, 1024));
			if(!$input) {
				removeClient($client);
			} else {
				echo "Receiving data from $address : $port || $input \n";
				socket_write($client, "Received...\r\n");
				if($input == "exit" || $input = "") {
					removeClient($client);
				} 
			}
		}
	}
	
} while ($live);

function removeClient($cl) {
	global $client_conn;
	socket_getpeername($cl, $address, $port);
	$i = array_search($cl, $client_conn);
	array_splice($client_conn, $i, 1);
	socket_close($cl);
	echo "Client $address : $port left.\n";
}

function sendHandshake($cl) {
	global $HostAddress;
	global $HostPort;
	
	$h = explode("\r\n", trim(socket_read($cl,2048)));
	echo "Header \n";
	$headers = array();
	foreach($h as $line) {
		if(strpos($line,": ")) {
			$l = explode(": ", $line,2);
			$headers[$l[0]] = $l[1];
		}
	}
	
	$secKey = $headers['Sec-WebSocket-Key'];
	$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
	$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
			"Upgrade: websocket\r\n" .
			"Connection: Upgrade\r\n" .
			"WebSocket-Origin: $HostAddress\r\n" .
			"WebSocket-Location: ws://$HostAddress:$HostPort\r\n".
			"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
	echo "Sending handshake...\n";
	socket_write($cl,$upgrade,strlen($upgrade));	
}

function wsDecode($frame) {
	$firstByte = substr($frame, 0,1);
	$secondByte = substr($frame, 0,1);
	
}
?>