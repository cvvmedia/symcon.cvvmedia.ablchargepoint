<?php



// Berechnet die LRC_Checksumme für Modbus Ascii
function ModBusAscii_CalcLrc($hexstr) {
	// Umwandeln des HexStr in ByteArray
	$byteArr=array_map('hexdec', str_split($hexstr, 2));

	$lrc = 0;
	$byteArrLen = count($byteArr);
	for ($i = 0; $i < $byteArrLen; $i++) {
		$lrc = ($lrc + $byteArr[$i]) & 0xFF;
	}
	$lrc = (($lrc ^ 0xFF) + 1) & 0xFF;
	return $lrc;
}


// Erzeugt ein Befehl zum Abfragen von Modbus Registern
function ModBusAscii_CreateRequestCmd($destaddr, $start, $count)
{
	$cmd=(string)'';
	
	$cmd=$cmd.strtoupper(sprintf('%02x', $destaddr));
	$cmd=$cmd.strtoupper(sprintf('%02x', 0x03));		// function
	$cmd=$cmd.strtoupper(sprintf('%04x', $start));
	$cmd=$cmd.strtoupper(sprintf('%04x', $count));
	
	$cmd=$cmd.strtoupper(sprintf('%02x', ModBusAscii_CalcLrc($cmd)));
	$cmd=":".$cmd.chr(13).chr(10);
	return $cmd;
}

// Erzeugt ein Befehl zum Setzen eines Registers
function ModBusAscii_CreateRegisterWriteCmd($destaddr, $start, $data)
{
	$cmd=(string)'';
	
	$cmd=$cmd.strtoupper(sprintf('%02x', $destaddr));
	$cmd=$cmd.strtoupper(sprintf('%02x', 0x10));		// function
	$cmd=$cmd.strtoupper(sprintf('%04x', $start));
	$cmd=$cmd.strtoupper(sprintf('%04x', 1));			// ein register schreiben
	$cmd=$cmd.strtoupper(sprintf('%02x', 2));			// Anzahl bytes (1 Register = 2 Bytes
	$cmd=$cmd.strtoupper(sprintf('%04x', $data));		// 2 Datenbytes
		
	$cmd=$cmd.strtoupper(sprintf('%02x', ModBusAscii_CalcLrc($cmd)));
	$cmd=":".$cmd.chr(13).chr(10);
	return $cmd;
}


function ModBusAscii_DecodeRegisterRequestAnswerCmd($RxStr)
{
	$Arr = array();
    
    // Ist das ">" Zeichen (=Modbus Antwort) am Anfang vorhanden? => entfernen
    if ( (strlen($RxStr)>0) & ($RxStr[0]=='>') ) {
        $RxStr=substr($RxStr,1);
    }

    // Ist noch CRLF am Ende Vorhanden? => entfernen
    if (strpos($RxStr, chr(13).chr(10))>0) {
        $RxStr=substr($RxStr, 0, -2);
    }

	// Checksumme überprüfen
	$crc = ModBusAscii_CalcLrc( substr($RxStr,0, -2) );		// Checksumme berechnen
	$crc = (string) strtoupper(sprintf('%02x', $crc));	    // Checksumme in HEXStr umwandeln
	
	if ($crc === substr($RxStr,-2, 2))
	{
		// Checksumme ist gültig !
		
		$Arr['id'] = hexdec( substr($RxStr, 0, 2) );
		$Arr['function'] = hexdec( substr($RxStr, 2, 2) );
		$RegCount = intdiv( hexdec( substr($RxStr, 4, 2)) , 2);		// Ermittelt Anzahl der Register (Anzahl bytes / 2)
		$Arr['registercount'] = $RegCount;
		
		// Register auslesen
		for ($i = 0; $i<$RegCount; $i++)
		{
			$Arr['register'.$i] =  hexdec( substr($RxStr, 6+($i*4), 4) );
        }
        
        return $Arr;
    }
}
    
