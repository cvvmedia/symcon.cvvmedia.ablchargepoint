# ABL ChargePoint Library

Folgende Module beinhaltet das ABL ChargePoint Library Repository:

- __ABL ChargePoint Splitter__ ([Dokumentation](ABL%20ChargePoint%20Splitter))  
	Der ABL Chargepoint Splitter dient zur Kommunikation mit dem RS485 Bus, woran ein oder mehrere Wallboxen
	vom Typ eMH der Firma ABL Sursum angeschlossen sind. Es werden die Gerätetypen eMH1, eMH2 und eMH3 unterstützt,
	wenn diese über den RS485-Bus angeschlossen werden. Der Splitter unterstützt sowohl den Zugriff über einen COM-Port,
	also auch über TCP/IP mittels Converter auf den RS485 Bus. Bei der eMH2/3 darf kein Backend verwendet werden, da dann
	die Wallbox nicht auf Befehle über den RS485 Bus reagiert.

- __ABL eMH__ ([Dokumentation](ABL%20eMH))  
	Die Instanz ABL eMH repräsentiert eine einzelne ABL-Wallbox.
	Die Kommunikation erfolgt über den ABL ChargePoint Splitter, welche die Verbindung zum Bus herstellt.
	
	
