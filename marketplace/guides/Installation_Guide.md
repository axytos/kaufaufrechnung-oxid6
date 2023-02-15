---
author: axytos GmbH
title: "Installationsanleitung"
subtitle: "axytos Kauf auf Rechnung, Oxid6"
header-right: axytos Kauf auf Rechnung, Oxid6
lang: "de"
titlepage: true
titlepage-rule-height: 2
toc-own-page: true
linkcolor: blue
---

## Installationsanleitung

Das Modul stellt die Bezahlmethode __Kauf Auf Rechnung__ für den Einkauf in Ihrem Oxid Shop bereit.

Einkäufe mit dieser Bezahlmethode werden von axytos ggf. bis zum Forderungsmanagement übernommen.

Alle relevanten Änderungen an Bestellungen mit dieser Bezahlmethode werden automatisch an axytos übermittelt.

Anpassungen über die Installation hinaus, z.B. von Rechnungs- und E-Mail-Templates, sind nicht notwendig.

Weitere Informationen erhalten Sie unter [https://www.axytos.com/](https://www.axytos.com/).


## Voraussetzungen

1. Vertragsbeziehung mit [https://www.axytos.com/](https://www.axytos.com/).

2. Verbindungsdaten, um das Modul mit [https://portal.axytos.com/](https://portal.axytos.com/) zu verbinden.

Um dieses Modul nutzen zu können benötigen Sie zunächst eine Vertragsbeziehung mit [https://www.axytos.com/](https://www.axytos.com/).

Während des Onboarding erhalten Sie die notwendigen Verbindungsdaten, um das Modul mit [https://portal.axytos.com/](https://portal.axytos.com/) zu verbinden.


## Modul-Installation

1. Zur Administration Ihrer Oxid Distribution wechseln. Nach Installation ist das Modul unter _Erweiterungen > Module_ aufgeführt.

2. Unter _Stamm_ __Aktivieren__ ausführen.

Das Modul ist jetzt installiert und aktiviert und kann konfiguriert werden.

Um das Modul nutzen zu können, benötigen Sie valide Verbindungsdaten zu [https://portal.axytos.com/](https://portal.axytos.com/) (siehe Voraussetzungen).


## Modul- und Shop-Konfiguration in Oxid

1. Zur Administration Ihrer Oxid Distribution wechseln. Das Modul ist unter _Erweiterungen > Module_ aufgeführt.

2. Zu _Einstell._ wechseln um die Konfiguration zu öffnen.

3. __API Host__ eintragen. Entweder [https://api.axytos.com/](https://api.axytos.com/) oder [https://api-sandbox.axytos.com/](https://api-sandbox.axytos.com/), die korrekten Werte werden Ihnen von axytos während des Onboarding mitgeteilt (siehe Voraussetzungen)

4. __API Key__ zwei mal eintragen. Der korrekte Wert wird Ihnen während des Onboarding von axytos mitgeteilt (siehe Voraussetzungen).

5. __Client Secret__ zwei mal eintragen. Der korrekte Wert wird Ihnen ebenfalls im Onboarding mitgeteilt (siehe Voraussetzungen).

6. __Speichern__ ausführen.

10. Die Bezahlmethode einer Versandart unter _Shopeinstellungen > Versandarten > (Ausgewählte Versandart) > Zahlungsarten > Zahlungsarten zuordnen_ zuordnen.

Zur Konfiguration müssen Sie valide Verbindungsdaten zu [https://portal.axytos.com/](https://portal.axytos.com/) (siehe Voraussetzungen), d.h. __API Host__, __API Key__ und __Client Secret__ für das Modul speichern.

## Kauf auf Rechnung kann nicht für Einkäufe ausgewählt werden?

Überprüfen Sie folgende Punkte:

1. Das Modul __axytos Kauf auf Rechnung__ ist installiert.

2. Das Modul __axytos Kauf auf Rechnung__ ist aktiviert.

3. Das Modul __axytos Kauf auf Rechnung__ ist mit korrekten Verbindungsdaten (__API Host__ & __API Key__) konfiguriert.

4. Sie haben Versandarten, Benutzergruppen und Länder zugeordnet.

Fehlerhafte Verbindungsdaten führen dazu, dass das Moduul nicht für Einkäufe ausgewählt werden kann.
