# IP-Symcon Modul - ABL ChargePoint Library

Dieses PHP-Modul erweitert IP-Symcon um eine Schnittstelle zu den Wallboxen von ABL Sursum.
Damit ist es möglich, den Status der Wallboxen abzufragen sowie die maximale Ladeleistung und die Betriebsart des Anschlusses zu setzen.

Unterstützt werden folgende Wallboxen des Herstellers:

- eMH1 11kW  (ab Herstelldatum 03.2021 keine Strommessung mehr)
- eMH1 22kW
- eMH2 22kW  (nur ohne Backend im StandAlone Modus)

Die eMH3 sollte ebenfalls funktionieren, wurde aber nicht getestet.


### Folgende Module beinhaltet das ABL ChargePoint Library Repository:

- __ABL ChargePoint Splitter__ ([Dokumentation](ABL%20ChargePoint%20Splitter))  
	Der ABL Chargepoint Splitter dient zur Kommunikation mit dem RS485 Bus, woran ein oder mehrere Wallboxen
	angeschlossen sind.

- __ABL eMH__ ([Dokumentation](ABL%20eMH))  
	Die Instanz ABL eMH repräsentiert eine einzelne Wallbox.
	Die Kommunikation erfolgt über den ABL ChargePoint Splitter, welche letztendlich die Verbindung zum Bus herstellt.
	
	
