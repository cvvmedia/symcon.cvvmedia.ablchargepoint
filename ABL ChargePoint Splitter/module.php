<?php
	// externe Libraries aus dem Libs-Verzeichnis einbinden
	require_once __DIR__ . '/../libs/ModbusAsciiLib.php';

	

	// Klasse der Instanz. Der name muss gleich sein, wie unter "name" in der module.json angegeben, nur ohne Leerzeichen
	class ABLChargePointSplitter extends IPSModule {


		// Systemfunktion, wird aufgerufen, wenn die Instanz erstellt wird
		public function Create()
		{
			parent::Create();		// wichtig!
			
			// Wenn das Modul zum ersten mal erstellt wird, wird automatisch auch eine eine Parent-Instanz erstellt (hier Client Socket)
			$this->RequireParent("{3CFF0FD9-E306-41DB-9B5A-9D06D38576C3}");		// GUID vom Client Socket

			// RxData Buffer erstellen
			$this->SetBuffer('RxRingbuffer', "");
		}



		// Systemfunktion, wird aufgerufen, wenn die Instanz beendet wird
		public function Destroy()
		{
			parent::Destroy();		// wichtig!
		}



		// Systemfunktion, wird aufgerufen, wenn sich Properties ändern
		// Dies kann beim Übernehmen der Einstellungen auf der Instanz-Konfigurationsseite
		// oder beim Start der Instanz (wenn diese erstmalig geladen wird) erfolgen
		public function ApplyChanges()
		{
			parent::ApplyChanges();		// wichtig!
		}



		// Systemfunktion, wird aufgerufen um Daten von einer Children-Instanz zu verarbeiten
		public function ForwardData($JSONString)
		{
			//Empfangenes JSON dekodieren
			$RxArr = json_decode($JSONString);

			// Zur Kontrolle Ausgabe der Daten im Log
			//IPS_LogMessage("Splitter empfängt von Device", utf8_decode($RxArr->DeviceID).' , '.utf8_decode($RxArr->Cmd) );


			// Soll die SerienNr/Type vom Ladepunkt abgefragt werden ?
			if ($this->HasActiveParent() && ($RxArr->Cmd=='GetDeviceIdent'))
			{
				// Modbus Befehl erzeugen
				$TxStr = ModBusAscii_CreateRequestCmd($RxArr->DeviceID, 0x50, 8);		

				// Daten an den Parent Senden (Client Socket)
				$TxArr = Array(	"DataID" 	=> "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}",	// TX GUID, Typ Simpel
								"Buffer" 	=> $TxStr
								);

				// Daten an IO senden
				$this->SendDataToParent( json_encode($TxArr) );

				// DebugLog ausgeben
				$this->SendDebug('Modbus Tx', $TxStr, 0);
			}
			

			// Soll der Status vom Ladepunkt abgefragt werden ?
			if ($this->HasActiveParent() && ($RxArr->Cmd=='GetState'))
			{
				// Modbus Befehl erzeugen
				$TxStr = ModBusAscii_CreateRequestCmd($RxArr->DeviceID, 0x2E, 5);		

				// Daten an den Parent Senden (Client Socket)
				$TxArr = Array(	"DataID" 	=> "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}",	// TX GUID, Typ Simpel
								"Buffer" 	=> $TxStr
								);

				// Daten an IO senden
				$this->SendDataToParent( json_encode($TxArr) );

				// DebugLog ausgeben
				$this->SendDebug('Modbus Tx', $TxStr, 0);
			}
			

			// Soll der Ladepunkt gesperrt werden?
			if ($this->HasActiveParent() && ($RxArr->Cmd=='SetLockOutlet'))
			{
				// Modbus Befehl erzeugen
				if ($RxArr->Value == 'true')
					{ $TxStr = ModBusAscii_CreateRegisterWriteCmd($RxArr->DeviceID, 0x05, 0xE0E0); }
					else { $TxStr = ModBusAscii_CreateRegisterWriteCmd($RxArr->DeviceID, 0x05, 0xA1A1); }		

				// Daten an den Parent Senden (Client Socket)
				$TxArr = Array(	"DataID" 	=> "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}",	// TX GUID, Typ Simpel
								"Buffer" 	=> $TxStr
								);

				usleep(5 * 1000);	// 5ms warten
				$this->SendDataToParent( json_encode($TxArr) );
				usleep(20 * 1000);	// 20ms warten
				$this->SendDataToParent( json_encode($TxArr) );

				// DebugLog ausgeben
				$this->SendDebug('Modbus Tx', $TxStr, 0);
			}


			// Soll die Ladeleistung gesetzt werden
			if ($this->HasActiveParent() && ($RxArr->Cmd=='SetMaxCurrent'))
			{
				// Strom umrechnen in PWM
				$DutyCycle = round($RxArr->Value) * (26.7 / 16);	// weil 26,7% = 16A laut IEC 62196 Typ 2

				// Modbus Befehl erzeugen
				$TxStr = ModBusAscii_CreateRegisterWriteCmd($RxArr->DeviceID, 0x14, ($DutyCycle)*10);		

				// Daten an den Parent Senden (Client Socket)
				$TxArr = Array(	"DataID" 	=> "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}",	// TX GUID, Typ Simpel
								"Buffer" 	=> $TxStr
								);

				usleep(5 * 1000);	// 5ms warten
				$this->SendDataToParent( json_encode($TxArr) );
				usleep(20 * 1000);	// 20ms warten
				$this->SendDataToParent( json_encode($TxArr) );
												
				// DebugLog ausgeben
				$this->SendDebug('Modbus Tx', $TxStr, 0);
			}


			// Soll der Ladepunkt resettet (neustart) werden?
			if ($this->HasActiveParent() && ($RxArr->Cmd=='ResetDevice'))
			{
				// Modbus Befehl erzeugen
				$TxStr = ModBusAscii_CreateRegisterWriteCmd($RxArr->DeviceID, 0x05, 0x5A5A);	

				// Daten an den Parent Senden (Client Socket)
				$TxArr = Array(	"DataID" 	=> "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}",	// TX GUID, Typ Simpel
								"Buffer" 	=> $TxStr
								);

				usleep(5 * 1000);	// 5ms warten
				$this->SendDataToParent( json_encode($TxArr) );
				usleep(20 * 1000);	// 20ms warten
				$this->SendDataToParent( json_encode($TxArr) );
								
				// DebugLog ausgeben
				$this->SendDebug('Modbus Tx', $TxStr, 0);
			}
			

			// Soll die DeviceID gesetzt werden?
			if ($this->HasActiveParent() && ($RxArr->Cmd=='SetDeviceID'))
			{
				// DeviceID kann nur gesetzt werden, wenn die Box im E2-Zustand ist. Muss vorher gesetzt werden.	

				// Gerät in E2-Zustand versetzen
				$TxStr = ModBusAscii_CreateRegisterWriteCmd(0, 0x05, 0xE2E2);	
				$TxArr = Array(	"DataID" 	=> "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}",	// TX GUID, Typ Simpel
								"Buffer" 	=> $TxStr
								);
				$this->SendDataToParent( json_encode($TxArr) );
				usleep(500 * 1000);	// 500ms warten bis Vorgang verarbeitet wurde

				$this->SendDebug('Modbus Tx', $TxStr, 0);	// DebugLog ausgeben				

				
				// DeviceID setzen
				$TxStr = ModBusAscii_CreateRegisterWriteCmd(0, 0x2C, (0xF000 | $RxArr->Value));		
				$TxArr = Array(	"DataID" 	=> "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}",	// TX GUID, Typ Simpel
								"Buffer" 	=> $TxStr
								);

				$this->SendDataToParent( json_encode($TxArr) );
				usleep(500 * 1000);	// 500ms warten bis Vorgang verarbeitet wurde

				$this->SendDebug('Modbus Tx', $TxStr, 0);	// DebugLog ausgeben				


				// Gerät neu starten
				$TxStr = ModBusAscii_CreateRegisterWriteCmd(0, 0x05, 0x5A5A);	
				$TxArr = Array(	"DataID" 	=> "{79827379-F36E-4ADA-8A95-5F8D1DC92FA9}",	// TX GUID, Typ Simpel
								"Buffer" 	=> $TxStr
								);

				$this->SendDataToParent( json_encode($TxArr) );

				$this->SendDebug('Modbus Tx', $TxStr, 0);	// DebugLog ausgeben				
			}


			return "";	// keine Ahnung, wofür die Rückgabe verwendet wird
		}



		// Systemfunktion, wird aufgerufen, wenn von einer Parent-Instanz Daten vorhanden sind (hier Client Socket)
		public function ReceiveData($JSONString)
		{
			//Empfangenes JSON dekodieren
			$RxArr = json_decode($JSONString);

			// DebugLog ausgeben
			$this->SendDebug('Modbus Rx', utf8_decode($RxArr->Buffer), 0);


			// RxRingbuffer holen und empfangene Daten anhängen
			$RxRingbuffer = $this->GetBuffer('RxRingbuffer') . utf8_decode($RxArr->Buffer);

			// Startzeichen ":" suchen. Eventuellen Müll davor entfernen
			$RxRingbuffer = substr($RxRingbuffer, strpos($RxRingbuffer, '>'));

			// Prüfen, ob ein vollständiges Modbus-Paket im RingBuffer vorhanden ist
			while ((strpos($RxRingbuffer, '>')!==false) && (strpos($RxRingbuffer, chr(13).chr(10))!==false))
			{
				// Es ist ein vollständiges Paket vorhanden

				$RxData = substr($RxRingbuffer, 0, strpos($RxRingbuffer, chr(13).chr(10))+2 );			// Paket aus RxRingbuffer extrahieren
				$RxRingbuffer = substr($RxRingbuffer, strpos($RxRingbuffer, chr(13).chr(10))+2 );	// Und aus dem Ringbuffer entfernen

				// Paket verarbeiten
				$this->ProcessRxModbusPaket($RxData);
			}

			// RxRingbuffer zurückschreiben
			$this->SetBuffer('RxRingbuffer', $RxRingbuffer);
		}






		// *****************************************************************************************************
		// Private Funktionen
		// *****************************************************************************************************

		// Verarbeitet die empfangenen Modbuspakete
		private function ProcessRxModbusPaket(string $RxData)
		{
			// DebugLog ausgeben
			$this->SendDebug('Modbus Rx - Process Paket', $RxData, 0);

			// Modbus Daten dekodieren
			$AnswerArr = ModBusAscii_DecodeRegisterRequestAnswerCmd($RxData);

			// Sind es Statusdaten?
			if ( (!empty($AnswerArr)) && ($AnswerArr['registercount']>0) && (($AnswerArr['register0']>>8)==0x2E) )
			{
				// Korrektur der Ströme, wenn nicht vorhaden oder kleiner als 0.2kW
				if (($AnswerArr['register2']==1000) | ($AnswerArr['register2']<2)) {
					$AnswerArr['register2'] = 0.0;
				}
				if (($AnswerArr['register3']==1000) | ($AnswerArr['register3']<2)) {
					$AnswerArr['register3'] = 0.0;
				}
				if (($AnswerArr['register4']==1000) | ($AnswerArr['register4']<2)) {
					$AnswerArr['register4'] = 0.0;
				}
				
				$DataArray = Array(	"DataID" 		=> "{1B55984F-DD7A-1233-D33F-0D1D2198228D}",
									"DeviceID" 		=> $AnswerArr['id'],
									"Cmd" 			=> "ReceivedState",		// Typenkennung des Events
									"OutletState" 	=> strtoupper(sprintf('%01x', $AnswerArr['register0']>>4 & 0x000F)) . ($AnswerArr['register0'] & 0x000F),
									"CurrentL1" 	=> $AnswerArr['register2'] / 10,
									"CurrentL2" 	=> $AnswerArr['register3'] / 10,
									"CurrentL3" 	=> $AnswerArr['register4'] / 10,
									"CurrentMax"	=> round( (($AnswerArr['register1'] & 0x0FFF) / 10) / (26.7 / 16.0) ),	// PWM von 26,7% = 16A laut IEC 62196 Typ 2
									"InputEN1"		=> (($AnswerArr['register1']>>12) & 0x1) ? 'true':'false',
									"InputEN2"		=> (($AnswerArr['register1']>>13) & 0x1) ? 'true':'false',
									"EVConnected"	=> (($AnswerArr['register1']>>14) & 0x1) ? 'true':'false'
									);

				// Daten senden an alle Childrens
				$this->SendDataToChildren( json_encode($DataArray) );
			}


			// Ist es die SerienNr / Gerätetyp?
			if ( (!empty($AnswerArr)) && ($AnswerArr['registercount']>0) && (($AnswerArr['register0']>>8)==0x50) )
			{
				$devicetype = chr(($AnswerArr['register1']>>8) & 0xFF).chr($AnswerArr['register1'] & 0xFF);
				$devicetype = $devicetype.chr(($AnswerArr['register2']>>8) & 0xFF).chr($AnswerArr['register2'] & 0xFF);
				$devicetype = $devicetype.chr(($AnswerArr['register3']>>8) & 0xFF).chr($AnswerArr['register3'] & 0xFF);

				$serial = chr(($AnswerArr['register4']>>8) & 0xFF).chr($AnswerArr['register4'] & 0xFF);
				$serial = $serial.chr(($AnswerArr['register5']>>8) & 0xFF).chr($AnswerArr['register5'] & 0xFF);
				$serial = $serial.chr(($AnswerArr['register6']>>8) & 0xFF).chr($AnswerArr['register6'] & 0xFF);
				$serial = $serial.chr(($AnswerArr['register7']>>8) & 0xFF).chr($AnswerArr['register7'] & 0xFF);
				$serial = trim($serial);

				$DataArray = Array(	"DataID" 		=> "{1B55984F-DD7A-1233-D33F-0D1D2198228D}",
									"DeviceID" 		=> $AnswerArr['id'],
									"Cmd" 			=> "ReceivedDeviceIdent",		// Typenkennung des Events
									"DeviceType" 	=> $devicetype,
									"DeviceSerial" 	=> $serial
									);

				// Daten senden an alle Childrens
				$this->SendDataToChildren( json_encode($DataArray) );
			}
		}




	}