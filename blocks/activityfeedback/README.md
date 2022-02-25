# Activity Feedback Plugin

Das Block-Plugin **Activity Feedback** ermöglicht es den Studierenden, 
Feedback zu einzelnen Aktivitäten abzugeben.
Die Standardkonfiguration stellt dafür vier verschiedene Emojis zur Auswahl bereit.

## Installation und Konfiguration
### Installation
Das Block-Plugin kann wie andere Block-Plugins installiert werden.
Dazu muss das gesamte Verzeichnis **activityfeedback** auf dem Server, wo Moodle läuft 
im Moodle-Verzeichnis (meist **moodle** genannt) im Verzeichnis der Block-Plugins (**blocks**) 
gespeichert werden. Sobald man anschliessend Moodle im Browser öffnet und sich danach als Administrator anmeldet, 
wird automatisch angezeigt, dass das Plugin installiert werden kann.

### Standardkonfiguration
Die Standardkonfiguration wird automatisch gezogen, wenn das Plugin neu installiert 
wird und man an den angezeigten Einstellungen keine Anpassung vornimmt.
Standardmässig (Stand Release 1.0, März 2022) stehen vier Emojis zur Auswahl, welche die Emotionen
* Begeisterung (Freude, Glück, Engagement, Flow)
* Langeweile
* Verwirrung und
* Frust (Wut)

repräsentieren, da diese in einer Lernsituation besonders relevant sind.

### Benutzerdefinierte Konfiguration
Grundsätzlich hat man die Möglichkeit, maximal 7 Feedback-Optionen zu konfigurieren.
Standardmässig sind 4 eingeschaltet. Jede der sieben Optionen kann aktiviert oder deaktiviert werden.
Es kann ein Bild augewählt werden (falls bei den ersten 4 Optionen keines angegeben wird, werden die
Standardbilder genommen) und im zugehörigen Textfeld kann der zum Bild anzuzeigende Text definiert werden.

### Aktivieren auf einem Kurs
Benutzende mit der Berechtigung, einen Kurs zu konfigurieren, können über **Add block** das Plugin auf dem Kurs aktiveren.
Damit werden die Feedback-Optionen automatisch auf jeder Aktivität der Kurses angezeigt.
Im Gegensatz zu den meisten anderen Blöcken, wird der Block selbst ausgeblendet sobald der Editiermodus beendet wird.

## Bedienung
* Bei jeder Aktivität ist ganz rechts ein Feedback-Button ersichtlich.
* Bei einem Klick darauf hat man die Möglichkeit, eine Feedback-Option auszuwählen.
* Um eine Feedback-Option wieder abzuwählen, muss die Option nochmals ausgewählt werden.
* Um ein bereits abgegebenes Feedback zu ändern, kann direkt eine andere Feedback-Option ausgewählt werden.

## Technische Details
Bemerkungen zum Reporting / DB-Tabellen

*Fernfachhochschule Schweiz, März 2022*