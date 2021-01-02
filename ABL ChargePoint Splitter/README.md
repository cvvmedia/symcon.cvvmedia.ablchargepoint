# ABL ChargePoint Splitter
Der ABL Chargepoint Splitter dient zur Kommunikation mit dem RS485 Bus, woran ein oder mehrere Wallboxen
vom Typ eMH der Firma ABL Sursum angeschlossen sind. Es werden die Geräte eMH1, eMH2 und eMH3 unterstützt,
wenn diese über den RS485-Bus angeschlossen werden. Der Splitter unterstützt sowohl den Zugriff über einen COM-Port,
also auch über TCP/IP mittels Converter auf den RS485 Bus.

### Inhaltsverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Voraussetzungen](#2-voraussetzungen)
3. [Software-Installation](#3-software-installation)
4. [Einrichten der Instanzen in IP-Symcon](#4-einrichten-der-instanzen-in-ip-symcon)


### 1. Funktionsumfang

Dies ist die Splitterinstanz, die die Kommunikation mit dem Bus koordiniert.

### 2. Vorraussetzungen

- IP-Symcon ab Version 5.2
- Alle Wallboxen müssen via RS485 an einem Bus angeschlossen sein
- Jeder Ladepunkt benötigt eine eindeutige GeräteID (zu setzen über eMH Instanz oder via ABL Tool)
- Der RS485-Bus kann entweder über eine Client Socket oder COM Port Instanz angesprochen werden
- Bei Nutzung des Client Sockets muss der RS485 Bus über einen "RS485 to Ethernet Adapter" (getestet mit USR-TCP232-304) angebunden werden
- Der RS485-Bus benötigt folgende Einstellungen: 38400 Baud, 8Bit, Parity Even, 1 Stop Bit


### 3. Software-Installation

* Über den Module Store das 'ABL ChargePoint Splitter'-Modul installieren.
* Alternativ das Repository manuell in das Module Verzeichnis der Symcon Installation kopieren.


### 4. Einrichten der Instanzen in IP-Symcon

 Unter 'Instanz hinzufügen' kann das 'ABL ChargePoint Splitter'-Modul mithilfe des Schnellfilters gefunden werden.  
	- Weitere Informationen zum Hinzufügen von Instanzen in der [Dokumentation der Instanzen](https://www.symcon.de/service/dokumentation/konzepte/instanzen/#Instanz_hinzufügen)


__Konfigurationsseite__:

Die Splitterinstanz hat keine Einstellungen.


