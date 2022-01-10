<?php
	// externe Libraries aus dem Libs-Verzeichnis einbinden
	require_once __DIR__ . '/../libs/ModbusAsciiLib.php';


	class ABLeMH extends IPSModule {

		// Systemfunktion, wird aufgerufen, wenn die Instanz erstellt wird
		public function Create()
		{
			parent::Create();		// wichtig!
			$this->ConnectParent("{9391CDDD-EF82-1D6B-1B54-08F3A61D9834}");		// Verbindung zum Splitter herstellen

			// Erstelle Variablenprofil für MAXCURRENT 16A
			if (IPS_VariableProfileExists('ABLEMH.MaxCurrent.16') === true) {
				IPS_DeleteVariableProfile('ABLEMH.MaxCurrent.16');
			}
			IPS_CreateVariableProfile('ABLEMH.MaxCurrent.16', 1);
			IPS_SetVariableProfileValues('ABLEMH.MaxCurrent.16', 5, 16, 1);
			IPS_SetVariableProfileDigits('ABLEMH.MaxCurrent.16', 0);
			IPS_SetVariableProfileText('ABLEMH.MaxCurrent.16', '', ' A');
			IPS_SetVariableProfileIcon('ABLEMH.MaxCurrent.16', 'Intensity');
		
			// Erstelle Variablenprofil für MAXCURRENT 32A
			if (IPS_VariableProfileExists('ABLEMH.MaxCurrent.32') === true) {
				IPS_DeleteVariableProfile('ABLEMH.MaxCurrent.32');
			}	
			IPS_CreateVariableProfile('ABLEMH.MaxCurrent.32', 1);
			IPS_SetVariableProfileValues('ABLEMH.MaxCurrent.32', 5, 32, 1);
			IPS_SetVariableProfileDigits('ABLEMH.MaxCurrent.32', 0);
			IPS_SetVariableProfileText('ABLEMH.MaxCurrent.32', '', ' A');
			IPS_SetVariableProfileIcon('ABLEMH.MaxCurrent.32', 'Intensity');
		
			// Erstelle Variablenprofil für BOOL-Zustände
			if (IPS_VariableProfileExists('ABLEMH.BoolState') === true) {
				IPS_DeleteVariableProfile('ABLEMH.BoolState');
			}
			IPS_CreateVariableProfile('ABLEMH.BoolState', 0);
			IPS_SetVariableProfileIcon('ABLEMH.BoolState', 'Flag');
			IPS_SetVariableProfileAssociation('ABLEMH.BoolState', 0, $this->Translate('No'), '', -1);
			IPS_SetVariableProfileAssociation('ABLEMH.BoolState', 1, $this->Translate('Yes'), '', -1);
		
			// Erstelle Variablenprofil für Fehler-Zustände
			if (IPS_VariableProfileExists('ABLEMH.ErrorState') === true) {
				IPS_DeleteVariableProfile('ABLEMH.ErrorState');
			}
			IPS_CreateVariableProfile('ABLEMH.ErrorState', 0);
			IPS_SetVariableProfileIcon('ABLEMH.ErrorState', 'Warning');
			IPS_SetVariableProfileAssociation('ABLEMH.ErrorState', 0, $this->Translate('OK'), '', -1);
			IPS_SetVariableProfileAssociation('ABLEMH.ErrorState', 1, $this->Translate('Error'), '', 0xFF0000);

			// Erstelle Variablenprofil für Lade-Zustände
			if (IPS_VariableProfileExists('ABLEMH.OutletState') === true) {
				IPS_DeleteVariableProfile('ABLEMH.OutletState');
			}
			IPS_CreateVariableProfile('ABLEMH.OutletState', 3);
			IPS_SetVariableProfileIcon('ABLEMH.OutletState', 'Information');
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'A1', $this->Translate('Waiting for EV'), 'Hourglass', -1);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'B1', $this->Translate('EV is asking for charging'), 'Information', -1);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'B2', $this->Translate('EV has the permission to charge'), 'Ok', -1);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'C2', $this->Translate('EV is charged'), 'Plug', -1);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'C3', $this->Translate('C2, reduced current (error F16, F17)'), 'Information', -1);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'C4', $this->Translate('C2, reduced current (imbalance F15)'), 'Information', -1);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'E0', $this->Translate('Outlet disabled'), 'Information', -1);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'E1', $this->Translate('Production test'), 'Information', -1);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'E2', $this->Translate('EVCC setup mode'), 'Information', -1);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'E3', $this->Translate('Bus idle'), 'Information', -1);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'F1', $this->Translate('Unintended closed contact (Welding)'), 'Warning', 0xFFFF00);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'F2', $this->Translate('Internal error'), 'Warning', 0xFFFF00);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'F3', $this->Translate('DC residual current detected'), 'Warning', 0xFFFF00);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'F4', $this->Translate('Upstream communication timeout'), 'Warning', 0xFFFF00);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'F5', $this->Translate('Lock of socket failed'), 'Warning', 0xFFFF00);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'F6', $this->Translate('CS out of range'), 'Warning', 0xFFFF00);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'F7', $this->Translate('State D requested by EV'), 'Warning', 0xFFFF00);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'F8', $this->Translate('CP out of range'), 'Warning', 0xFFFF00);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'F9', $this->Translate('Overcurrent detected'), 'Warning', 0xFFFF00);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'FA', $this->Translate('Temperature outside limits'), 'Warning', 0xFFFF00);
			IPS_SetVariableProfileAssociation('ABLEMH.OutletState', 'FB', $this->Translate('Unintended opened contact'), 'Warning', 0xFFFF00);


			// Variablen der Instanz-Konfigurationsseite erstellen
			$this->RegisterPropertyInteger('deviceid', 1);

			// Variablen unter der Instanz erstellen
			$this->RegisterVariableBoolean('CONNECTED', $this->Translate('EV connected'), 'ABLEMH.BoolState', 0);
			$this->RegisterVariableBoolean('CHARGERELEASE', $this->Translate('EV has charge release'), 'ABLEMH.BoolState', 1);
			$this->RegisterVariableBoolean('CHARGING', $this->Translate('EV charging'), 'ABLEMH.BoolState', 2);
			$this->RegisterVariableBoolean('OUTLETLOCKED', $this->Translate('Outlet Locked'), '~Lock', 3);
			$this->RegisterVariableString('OUTLETSTATE', $this->Translate('Outlet State'), 'ABLEMH.OutletState', 4);
			$this->RegisterVariableFloat('CURRENTL1', $this->Translate('Current L1'), '~Ampere', 5);
			$this->RegisterVariableFloat('CURRENTL2', $this->Translate('Current L2'), '~Ampere', 6);
			$this->RegisterVariableFloat('CURRENTL3', $this->Translate('Current L3'), '~Ampere', 7);
			$this->RegisterVariableInteger('MAXCURRENT', $this->Translate('Max Output Current'), 'ABLEMH.MaxCurrent.32', 8);	// Erstmal der 32A Profil
			$this->RegisterVariableBoolean('ERROR_DEVICE', $this->Translate('Device Error'), 'ABLEMH.ErrorState', 9);
			$this->RegisterVariableBoolean('ERROR_COMMUNICATION', $this->Translate('Communication Error'), 'ABLEMH.ErrorState', 10);
			$this->RegisterVariableBoolean('INPUTEN1', $this->Translate('Input EN1'), '~Switch', 11);
			$this->RegisterVariableBoolean('INPUTEN2', $this->Translate('Input EN2'), '~Switch', 12);
			$this->RegisterVariableString('DEVICETYPE', $this->Translate('Device Type'), '', 13);
			$this->RegisterVariableString('DEVICESERIAL', $this->Translate('Device Serialnumber'), '', 14);

			// Aktiviert die Standardaktion von Variablen, um diese über das Webfront schalten zu können
			// => Die Aktion muss über die Funktion "RequestAction" verarbeitet werden
			$this->EnableAction("OUTLETLOCKED");	
			$this->EnableAction("MAXCURRENT");	

			// Fehlerflags initalisieren
			$this->SetBuffer('ERROR_LastReceivedData', time());
			$this->SetBuffer('ERROR_DeviceState', 'A1');

			// Timer anlegen
			$this->RegisterTimer("RequestStatusTimer", 2000, 'ABLEMH_RequestStatus('.$this->InstanceID.');' );			// Regelmäßig Gerätestatus Abfragen
		}



		// Systemfunktion, wird aufgerufen, wenn die Instanz beendet wird
		public function Destroy()
		{
			parent::Destroy();				// wichtig!
		}



		// Systemfunktion, wird aufgerufen, wenn sich Properties ändern
		// Dies kann beim Übernehmen der Einstellungen auf der Instanz-Konfigurationsseite
		// oder beim Start der Instanz (wenn diese erstmalig geladen wird) erfolgen
		public function ApplyChanges()
		{
			parent::ApplyChanges();			// wichtig!

			// Variiert das Updateintervall ein wenig um eine Kollision von Abfragen zu vermeiden
			$this->SetTimerInterval("RequestStatusTimer", 2000 + (($this->ReadPropertyInteger('deviceid')-1) * 5) );

			// Gerätetyp / SerienNr abfragen
			$this->GetDeviceIdent();
		}



		// Systemfunktion, wird aufgerufen, wenn von einer Parent-Instanz Daten vorhanden sind
		public function ReceiveData($JSONString)
		{
			//Empfangenes JSON dekodieren
			$RxArr = json_decode($JSONString);

			// Zur Kontrolle Ausgabe der Daten im Log
			//IPS_LogMessage("Device empfängt von Splitter", utf8_decode($RxArr->DeviceID).' , '.utf8_decode($RxArr->Cmd) );
			//IPS_LogMessage("xx", print_r($RxArr, true));

			// Sind es Statusdaten und unsere DeviceID?
			if ( ($RxArr->Cmd=='ReceivedState') && ($RxArr->DeviceID==$this->ReadPropertyInteger('deviceid')) )
			{
				// Zusandsvariablen setzen je nach Outlet State
				//$outletstate = (string) strtoupper(sprintf('%02x', $RxArr->OutletState));
				$outletstate = (string) $RxArr->OutletState;
				$connected = false;
				$chargerelease = false;
				$charging = false;
				$outletlocked = false;

				switch ($outletstate) {
					case "A1":		break;
					case "B1":		$connected=true; break;
					case "B2":		$connected=true; $chargerelease= true; break;
					case "C2":		$connected=true; $chargerelease= true; $charging=true; break;
					case "C3":		$connected=true; $chargerelease= true; $charging=true; break;
					case "C4":		$connected=true; $chargerelease= true; $charging=true; break;
					case "E0":		$outletlocked=true; break;
					default: 		break;
				}
				
				
				$this->SetBuffer('ERROR_DeviceState', $outletstate);

				if ($this->GetValue("CONNECTED") !== $connected) { $this->SetValue("CONNECTED", $connected); }	
				
				if ($this->GetValue("CHARGERELEASE") !== $chargerelease) { $this->SetValue("CHARGERELEASE", $chargerelease); }

				if ($this->GetValue("CHARGING") !== $charging) { $this->SetValue("CHARGING", $charging);	}

				if ($this->GetValue("OUTLETLOCKED") !== $outletlocked) { $this->SetValue("OUTLETLOCKED", $outletlocked);	}	

				if ($this->GetValue("OUTLETSTATE") !== $outletstate) { $this->SetValue("OUTLETSTATE", $outletstate); }

				$temp = (float) $RxArr->CurrentL1;
				if ($this->GetValue("CURRENTL1") != $temp) { $this->SetValue("CURRENTL1", $temp); }

				$temp = (float) $RxArr->CurrentL2;
				if ($this->GetValue("CURRENTL2") != $temp) { $this->SetValue("CURRENTL2", $temp); }

				$temp = (float) $RxArr->CurrentL3;
				if ($this->GetValue("CURRENTL3") != $temp) { $this->SetValue("CURRENTL3", $temp); }
				
				$temp = (int) $RxArr->CurrentMax;
				if ($this->GetValue("MAXCURRENT") != $temp) { $this->SetValue("MAXCURRENT", $temp); }

				$temp = (bool) ($RxArr->InputEN1 == 'true' ? true : false);
				if ($this->GetValue("INPUTEN1") != $temp) { $this->SetValue("INPUTEN1", $temp); }

				$temp = (bool) ($RxArr->InputEN2 == 'true' ? true : false);
				if ($this->GetValue("INPUTEN2") != $temp) { $this->SetValue("INPUTEN2", $temp); }

				// DebugLog ausgeben
				$this->SendDebug('ReceivedState', str_replace(chr(10),'', print_r($RxArr, true)), 0);
				
				// Die aktuelle Zeit merken, zur Erkennung von Kommunikationsfehlern
				$this->SetBuffer('ERROR_LastReceivedData', time());
			}


			// Sind es Daten über das Gerät und für unsere DeviceID?
			if ( ($RxArr->Cmd=='ReceivedDeviceIdent') && ($RxArr->DeviceID==$this->ReadPropertyInteger('deviceid')) )
			{
				if ($this->GetValue("DEVICETYPE") !== $RxArr->DeviceType) { $this->SetValue("DEVICETYPE", $RxArr->DeviceType); }	
				if ($this->GetValue("DEVICESERIAL") !== $RxArr->DeviceSerial) { $this->SetValue("DEVICESERIAL", $RxArr->DeviceSerial); }

				// Da wir nun wissen, welches Modell wir haben (11 oder 22KW),
				// können wir nun das passende Profil für MaxCurrent einstellen.
				// Dazu registrieren wir die Variable einfach neu. Ist diese bereits vorh., wird sie nur aktualisiert
				if ( strpos($RxArr->DeviceType, 'W11') !== false ) {
					$this->MaintainVariable('CURRENTL1', $this->Translate('Current L1'), 2, '~Ampere.16', 5, true);
					$this->MaintainVariable('CURRENTL2', $this->Translate('Current L2'), 2, '~Ampere.16', 6, true);
					$this->MaintainVariable('CURRENTL3', $this->Translate('Current L3'), 2, '~Ampere.16', 7, true);
					$this->MaintainVariable('MAXCURRENT', $this->Translate('Max Output Current'), 1, 'ABLEMH.MaxCurrent.16', 8, true);
				}			
				if ( strpos($RxArr->DeviceType, 'W22') !== false ) {
					$this->MaintainVariable('CURRENTL1', $this->Translate('Current L1'), 2, '~Ampere', 5, true);
					$this->MaintainVariable('CURRENTL2', $this->Translate('Current L2'), 2, '~Ampere', 6, true);
					$this->MaintainVariable('CURRENTL3', $this->Translate('Current L3'), 2, '~Ampere', 7, true);
					$this->MaintainVariable('MAXCURRENT', $this->Translate('Max Output Current'), 1, 'ABLEMH.MaxCurrent.32', 8, true);
				}

				// DebugLog ausgeben
				$this->SendDebug('ReceivedDeviceIdent', str_replace(chr(10),'', print_r($RxArr, true)), 0);

				// Die aktuelle Zeit merken, zur Erkennung von Kommunikationsfehlern
				$this->SetBuffer('ERROR_LastReceivedData', time());
			}	
		}




		// Systemfunktion, wird von außen angesteuert, wenn jemand eine Variable ändert, z.B. über die Visu
		// Wichtig ist dabei, dass bei den entsprechenden Variablen die Funktion mit $this->EnableAction() aktiviert wurde
		public function RequestAction($Ident, $Value) {
 
			switch($Ident) {
				case "OUTLETLOCKED":
					$this->SetValue("OUTLETLOCKED", $Value);	// Setzt die Variable auf den neuen Wert	
					$this->SetLockOutlet($Value);					// Sendet eine Nachricht zum Ladepunkt mit dem neuen Status
					break;
				case "MAXCURRENT":
					$this->SetValue("MAXCURRENT", round($Value));	// Setzt die Variable auf den neuen Wert	
					$this->SetMaxCurrent(round($Value));			// Sendet eine Nachricht zum Ladepunkt mit dem neuen Status
					break;					
				default:
					throw new Exception("Invalid Ident");
			}
		}



		// Fragt den Status des Ladepunktes ab (öffendlicher Befehl mit Prefix, da "public function")
		// Ist keine Systemfuntion, hat sich aber eingebürgert, diese so zu nennen um den Status abzufragen
		public function RequestStatus() 		
		{
			// Zu sendende Daten erzeugen
			$TxArr = Array(	"DataID" 	=> "{5D9BE907-4A52-C23B-3A5F-52802555C983}",
							"DeviceID" 	=> $this->ReadPropertyInteger('deviceid'),
							"Cmd" 		=> utf8_encode("GetState")
							);

			// Daten ans übergeordnete Parent-Modul (hier Splitter) senden
			$this->SendDataToParent( json_encode($TxArr) );

			// DebugLog ausgeben
			$this->SendDebug('Send GetState', str_replace(chr(10),'', print_r($TxArr, true)), 0);


			// aktualisiert der Fehlerstatus
			$this->UpdateErrorState();
		}

		

		// Fragt die Seriennummer und Typ des Ladepunktes ab (öffendlicher Befehl mit Prefix, da "public function")
		public function GetDeviceIdent() 		
		{
			// Zu sendende Daten erzeugen
			$TxArr = Array(	"DataID" 	=> "{5D9BE907-4A52-C23B-3A5F-52802555C983}",
							"DeviceID" 	=> $this->ReadPropertyInteger('deviceid'),
							"Cmd" 		=> "GetDeviceIdent"
							);

			// Daten ans übergeordnete Parent-Modul (hier Splitter) senden
			$this->SendDataToParent( json_encode($TxArr) );

			// DebugLog ausgeben
			$this->SendDebug('Send GetDeviceIdent', str_replace(chr(10),'', print_r($TxArr, true)), 0);
		}


		
		// Aktiviert / Sperrt den Ladepunkt (öffendlicher Befehl mit Prefix, da "public function")
		public function SetLockOutlet(bool $value)
		{
			// Zu sendende Daten erzeugen
			$TxArr = Array( "DataID" 	=> "{5D9BE907-4A52-C23B-3A5F-52802555C983}",
							"DeviceID" 	=> $this->ReadPropertyInteger('deviceid'),
							"Cmd" 		=> "SetLockOutlet",
							"Value"		=> $value ? 'true':'false'
							);
			
			// Daten an die übergeordnete Parent-Instanz (hier Splitter) senden
			$this->SendDataToParent( json_encode($TxArr) );

			// DebugLog ausgeben
			$this->SendDebug('Send SetLockOutlet', str_replace(chr(10),'', print_r($TxArr, true)), 0);
		}



		// Setzt die max. Ladeleistung in Ampere (öffendlicher Befehl mit Prefix, da "public function")
		public function SetMaxCurrent(int $value)
		{
			// Grenzen überprüfen (muss positiv sein)
			if ($value<0)
				{ $value = 0; }

			// Zu sendende Daten erzeugen
			$TxArr = Array(	"DataID" 	=> "{5D9BE907-4A52-C23B-3A5F-52802555C983}",
							"DeviceID" 	=> $this->ReadPropertyInteger('deviceid'),
							"Cmd" 		=> "SetMaxCurrent",
							"Value"		=> $value
							);
			
			// Daten an die übergeordnete Parent-Instanz (hier Splitter) senden
			$this->SendDataToParent( json_encode($TxArr) );

			// DebugLog ausgeben
			$this->SendDebug('Send SetMaxCurrent', str_replace(chr(10),'', print_r($TxArr, true)), 0);
		}



		// Setzt die DeviceID des angeschlossenen Gerätes (öffendlicher Befehl mit Prefix, da "public function")
		public function SetDeviceID(int $value)
		{
			// Grenzen überprüfen (muss positiv sein)
			if ($value<1) { $value = 1; }
			if ($value>16) { $value = 16; }

			// Zu sendende Daten erzeugen
			$TxArr = Array(	"DataID" 	=> "{5D9BE907-4A52-C23B-3A5F-52802555C983}",
							"DeviceID" 	=> $this->ReadPropertyInteger('deviceid'),
							"Cmd" 		=> "SetDeviceID",
							"Value"		=> $value
							);
			
			// Daten an die übergeordnete Parent-Instanz (hier Splitter) senden
			$this->SendDataToParent( json_encode($TxArr) );

			// DebugLog ausgeben
			$this->SendDebug('Send SetDeviceID', str_replace(chr(10),'', print_r($TxArr, true)), 0);
		}



		// Resettet das Gerät (öffendlicher Befehl mit Prefix, da "public function")
		public function ResetDevice()
		{
			// Zu sendende Daten erzeugen
			$TxArr = Array(	"DataID" 	=> "{5D9BE907-4A52-C23B-3A5F-52802555C983}",
							"DeviceID" 	=> $this->ReadPropertyInteger('deviceid'),
							"Cmd" 		=> "ResetDevice"
							);
			
			// Daten an die übergeordnete Parent-Instanz (hier Splitter) senden
			$this->SendDataToParent( json_encode($TxArr) );

			// DebugLog ausgeben
			$this->SendDebug('Send ResetDevice', str_replace(chr(10),'', print_r($TxArr, true)), 0);
		}



		

		

		// *****************************************************************************************************
		// Private Funktionen
		// *****************************************************************************************************

		// Wertet die Fehler aus und setzt die Fehlervariable und den Instanzstatus
		private function UpdateErrorState()
		{
			if ((time() - (int) $this->GetBuffer('ERROR_LastReceivedData')) > 10)
				{$CommunicationError = true; } else {$CommunicationError = false;}

			if (strpos($this->GetBuffer('ERROR_DeviceState'), 'F') !== false )
				{$DeviceError = true; } else {$DeviceError = false;}
			
			$this->SendDebug("ERROR_LastReceivedData",$CommunicationError,0);
			$this->SendDebug("ERROR_DeviceState",$DeviceError,0);

			
			// Fehlervariablen setzen
			if ( $CommunicationError ) {	
				if ($this->GetValue("ERROR_COMMUNICATION") !== true) { $this->SetValue("ERROR_COMMUNICATION", true); }
			}
			else
			{
				if ($this->GetValue("ERROR_COMMUNICATION") !== false) { $this->SetValue("ERROR_COMMUNICATION", false); }
			}
			
			if ( $DeviceError ) {
				if ($this->GetValue("ERROR_DEVICE") !== true) { $this->SetValue("ERROR_DEVICE", true); }
			}
			else
			{
				if ($this->GetValue("ERROR_DEVICE") !== false) { $this->SetValue("ERROR_DEVICE", false); }
			}


			// Fehlerzustan der Instanz setzen
			if ($CommunicationError) {
				if ($this->GetStatus()<>200) { $this->SetStatus(200); }
			}
			else
			{ 
				if ($this->GetStatus()>=200) { $this->SetStatus(102); }
			}

			
			return ($CommunicationError | $DeviceError);
		}




	}
