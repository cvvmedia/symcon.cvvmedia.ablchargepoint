# ABL ChargePoint Splitter
Der ABL Chargepoint Splitter dient zur Kommunikation mit dem RS485 Bus, woran ein oder mehrere Ladepunkte
der Firma ABL angeschlossen sind. Es werden die Geräte eMH1, eMH2 und eMH3 unterstützt, soweit diese über
RS485 Bus ansprechbar sind.

### Inhaltsverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Software-Installation](#3-software-installation)
4. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)


### 1. Funktionsumfang

Dies ist die Splitterinstanz, die die Kommunikation mit dem Bus koordiniert.

### 2. Vorraussetzungen

- IP-Symcon ab Version 5.2
- Alle ABL Ladepunkte müssen via RS485 an einem Bus angeschlossen sein
- Der RS485 Bus muss über ein RS485 to Ethernet Adapter übers Netzwerk erreichbar sein (z.B. USR-TCP232-304)
- Der RS485 Ethernet Adapter muss den Bus wie folgt ansprechen: 38400 Baud, 8Bit, Parity Even, 1 Stop Bit
- Bei mehreren Ladepunkten muss jedes Gerät eine andere GeräteID haben


### 3. Software-Installation

* Über den Module Store das 'ABL ChargePoint Splitter'-Modul installieren.


### 4. Einrichten der Instanzen in IP-Symcon

 Unter 'Instanz hinzufügen' kann das 'ABL ChargePoint Splitter'-Modul mithilfe des Schnellfilters gefunden werden.  
	- Weitere Informationen zum Hinzufügen von Instanzen in der [Dokumentation der Instanzen](https://www.symcon.de/service/dokumentation/konzepte/instanzen/#Instanz_hinzufügen)


__Konfigurationsseite__:

Die Splitterinstanz hat keine Einstellungen.


