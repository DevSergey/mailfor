# Projektantrag
## Projekttitel
Formail
## Ausgangslage Applikation
Ich werde auf keiner bestehenden Applikation aufbauen, sondern ein Neue von Grund auf entwickeln.
## Funktionen
### Grundfunktionen
Kann sich registrieren, anmelden, abmelden und benutzerspezifische Daten verwalten.
Ein Benutzer soll Endpoints erstellen können. Über diese kann man Formulardaten senden, welche dann validiert werden und per Mail an einen Empfänger verschickt werden. Des weiteren kann ein Rate-Limiting eingerichtet werden um sich vor einem Missbrauch zu schützen. Das Rate-Limiting umfasst ein monatliches Limit für einen Endpoint, so wie eine zeitliche Limitierung für einen Client (z.B. IP-Adresse) . Diese Endpoints sollen folgende Problematik lösen: Wenn man eine clientseitige Webapplikation ohne Backend entwickelt, gibt es keine geeigneten Möglichkeiten, Formulardaten zu übermitteln. Mit Formail hat man nun einen Endpoint, welcher per Ajax angesprochen werden kann.
### Zusatzfunktionen Administrator
Kann zusätzlich zum Standardbenutzer auch die Benutzer verwalten.
## Produktdaten
### Grundfunktionen
#### User
- name
- email 
- email_verified_at
- password
- remember_token
- admin
Alle weiteren Entitäten sind skizziert:
![Skizze](assets/sketch.jpg)
## Validierungsregeln
Noch nicht erarbeitet.
## Technologien
### UI
Laravel Frontend basierend auf Vue.js und Blade Templates.
### Frameworks
Laravel
### ORM
Eloquent (Laravel ORM)
## Umgebung
### Entwicklungsumgebung
- PHPStorm
- Docker & docker-compose
- Composer
- npm
- gitlab ci/cd
### Programiersprachen
- Javascript
- php
### Dienste
- Redis für:
    - Rate-Limiting
    - Queues
    - Cache
    - Session
- Postgres:
    - Datenbank
## Eigener Fokus
Der eigene Fokus liegt auf der Umsetzung von einem Rate-Limiting. Mit diesem können Endpoints vor Missbrauch geschützt werden und hat aus diesem Grund auch mit Sicherheit zu tun. Den mit jedem Request an einen Endpoint wird auch ein Mail verschickt.
Es werden zwei Rate-Limits eingebaut:
- Monthly Limit:
  - Um ungewollte Kosten beim Mail Service (z.b. Mailgun oder Sendgrid) zu verhindern, kann man ein monatliches Limit festlegen.
- Client Limit:
  - Damit kein Client ungewollt viele Requests  durchführen kann, soll festgelegt werden können, wieviele Requests dieser pro Zeiteinheit senden darf.
Bei einer Überschreitung wird ein Fehler zurückgegeben.
