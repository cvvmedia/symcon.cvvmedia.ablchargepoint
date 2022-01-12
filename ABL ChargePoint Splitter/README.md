# ABL ChargePoint Splitter
Der ABL Chargepoint Splitter dient zur Kommunikation mit dem RS485 Bus, woran ein oder mehrere Wallboxen
angeschlossen sind. Der Splitter unterstützt sowohl den Zugriff über einen COM-Port, also auch über die Client Socket-Instanz via TCP/IP.
Bei letzteren ist ein Converter von LAN auf RS485 Bus notwendig.

### Inhaltsverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-vorraussetzungen)
3. [Software-Installation](#3-software-installation)
4. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)


### 1. Funktionsumfang

Dies ist die Splitterinstanz, die die Kommunikation mit dem Bus koordiniert.

### 2. Vorraussetzungen

- IP-Symcon ab Version 5.6
- Alle Wallboxen müssen via RS485 an einem Bus angeschlossen sein
- Bei Kabellängen größer 5m muss der Bus am Anfang und am Ende korrekt terminiert sein [(siehe Wikipedia RS485)](https://de.wikipedia.org/wiki/EIA-485)
- Jede Wallbox benötigt eine eindeutige GeräteID (zu setzen über eMH Instanz oder via ABL CONFCAB-Software)
- Die leistungsfähigeren Wallboxen eMH2/3 müssen für den "Standalone-Modus ohne Backend" konfiguriert sein (ABL CONFCAB-Software)
- Der RS485-Bus kann entweder über eine Client Socket (TCP) oder einer COM Port Instanz angesprochen werden
- Bei Nutzung des Client Sockets muss der RS485 Bus über einen "RS485 to Ethernet Adapter" (getestet mit "USR-TCP232-304" von USR IoT) angebunden werden
- Der RS485-Bus benötigt folgende Einstellungen: 38400 Baud, 8Bit, Parity Even, 1 Stop Bit


### 3. Software-Installation

* Über den Module Store das 'ABL ChargePoint Splitter'-Modul installieren.
* Alternativ das Repository manuell in das Module Verzeichnis der Symcon Installation kopieren.


### 4. Einrichten der Instanzen in IP-Symcon

 Unter 'Instanz hinzufügen' kann das 'ABL ChargePoint Splitter'-Modul mithilfe des Schnellfilters gefunden werden.  
	- Weitere Informationen zum Hinzufügen von Instanzen in der [Dokumentation der Instanzen](https://www.symcon.de/service/dokumentation/konzepte/instanzen/#Instanz_hinzufügen)


__Konfigurationsseite__:

Die Splitterinstanz besitzt keine Einstellungen.
