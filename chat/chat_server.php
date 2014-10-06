<?php
set_time_limit(0);

define("OPT_CLOSE", "^Q");
define("OPT_ACK","^ACK");
define("OPT_NEW_CHAT","^NC");
define("OPT_MSG","^MSG");

define("STATUS_ONLINE", 1);

$HostAddress = "127.0.0.1";
$HostPort = 2500;
$max_clients = 5;

$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

if (!(socket_bind($sock, $HostAddress, $HostPort))) {
	echo socket_strerror(socket_last_error());
}

socket_listen($sock, 10);
echo "Listening for connections...\n";

$client_conn = array();
$users = array();
$chats = array();

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
				sendHandshake($dump);
				echo "Incomming connection rejected. Too many clients\n";
				$discard = socket_read($dump,1024);
				socket_write($dump, wsEncode("Too many clients, try again later."));
				socket_close($dump);
		}
	}

	//Reads data from clients.
	foreach($read as $client) {
		
		if($client != $sock) {
			socket_getpeername($client, $address, $port);
			$input = trim(socket_read($client, 1024));
			if(!$input) {
				removeClient($client);
			} else {
				echo "Receiving data from $address : $port\n";
				$dec = wsDecode($input);
				if($dec==OPT_CLOSE) {
					removeClient($client);
				} else {
					$msg = json_decode($dec,true);
					switch($msg['opt']) {
						case "register":
							register($client, $msg['usr']);
							break;
						case "start":
							startChat($msg['usr'], $msg['to']);
							break;
						case OPT_MSG:
							sendMsg($msg['chid'],$msg['usr'],$msg['msg']);
							break;
					}
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
	$bytes = array();
	$bytes['opcode'] = unpack("C", substr($frame, 0,1));
	$bytes['payload'] = unpack("C", substr($frame, 1,1));
	
	$payload_lenght = $bytes['payload'][1] & 0x7f;
	
	$opcode = $bytes['opcode'][1] & 0x0f;
	
	$bytes['mask'][] = unpack("C", substr($frame, 2,1));
	$bytes['mask'][] = unpack("C", substr($frame, 3,1));
	$bytes['mask'][] = unpack("C", substr($frame, 4,1));
	$bytes['mask'][] = unpack("C", substr($frame, 5,1));

	$decode = "";
	
	if($opcode == 0x8) {
		$data = unpack("S",substr($frame, 6, 2));
		echo $data[1]."\n";
		return OPT_CLOSE;
	} else if ($opcode == 0x1) {
		for($i=6;$i<($payload_lenght+6);$i++) {
			$data = unpack("C",substr($frame, $i, 1));
			$decode .= chr($data[1] ^ $bytes['mask'][($i-6) % 4][1]);
		}
		return $decode;
	}
}

function wsEncode($msg) {
	$esc_msg = $msg;
	$payload_lenght=strlen($esc_msg);

	$encode = pack("C",129);

	if($payload_lenght <= 125) {
		$encode .= pack("C",$payload_lenght);
	} else if($payload_lenght >= 126 && $payload_lenght <= 65535) {
		$encode .= pack("C", 126);
		$encode .= pack("N", $payload_lenght);
	} else {
		$encode .= pack("C", 127);
		$left = 0xffffffff00000000;
		$right = 0x00000000ffffffff;
		
		$l = ($payload_lenght & $left) >>32;
		$r = $payload_lenght & $right;
		$encode .= pack("NN", $l, $r);
	}
	
	for($i=0;$i<$payload_lenght;$i++) {
		$encode .= pack("C", ord(substr($esc_msg,$i,1)));
	}

	return $encode;
}

function register($cl, $usr) {
	global $users;
	socket_getpeername($cl, $address, $port);
	$users[$usr]['socket'] = $cl;
	$users[$usr]['status'] = STATUS_ONLINE;
	$users[$usr]['timestamp'] = date("c");
	$msg['opt']=OPT_ACK;
	socket_write($cl, wsEncode(json_encode($msg)));
	echo "Client $address : $port registered.\n";
}

function startChat($usr, $to) {
	global $users;
	global $chats;
	socket_getpeername($users[$usr]['socket'],$address_from, $port_from);
	socket_getpeername($users[$to]['socket'],$address_to, $port_to);
	$id=uniqid("CH");
	$chats[$id]['from']=$usr;
	$chats[$id]['to']=$to;
	echo "Chat started between $address_from:$port_from and $address_to:$port_to\n";
	$msg['opt']=OPT_NEW_CHAT;
	$msg['chid']=$id;
	socket_write($users[$usr]['socket'],wsEncode(json_encode($msg)));
	socket_write($users[$to]['socket'],wsEncode(json_encode($msg)));
}

function sendMsg($chid, $usr, $msg) {
	global $users;
	global $chats;
	socket_getpeername($users[$usr]['socket'],$address_from, $port_from);
	if($chats[$chid]['from']==$usr) {
		$dest = $users[$chats[$chid]['to']]['socket'];
	} else if($chats[$chid]['to']==$usr) {
		$dest = $users[$chats[$chid]['from']]['socket'];
	}
	socket_getpeername($dest, $address_to, $port_to );
	echo "Message sent by $address_from:$port_from to $address_to:$port_to\n";
	$msg_obj['opt']=OPT_MSG;
	$msg_obj['msg']=$msg."\n";
	
	socket_write($dest,wsEncode(json_encode($msg_obj)));
}
?>