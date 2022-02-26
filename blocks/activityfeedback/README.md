# Plugin "Aktivitäten-Feedback"

Das Block-Plugin **Aktivitäten-Feedback** ermöglicht es Kursteilnehmenden, Feedback zu einzelnen Aktivitäten in Moodle abzugeben.
Die Standardkonfiguration stellt dafür vier verschiedene Emojis (begeistert, gelangweilt, verwirrt und frustriert) zur Auswahl bereit.

## Installation
Das Plugin kann wie andere Block-Plugins übers GUI installiert werden (hochladen vom ZIP-Verzeichnis).
Die manuelle Alternative dazu ist es, das gesamte Verzeichnis **activityfeedback** auf dem Server, wo Moodle läuft, im Moodle-Verzeichnis 
(meist **moodle** genannt) im Verzeichnis der Block-Plugins (**blocks**) abzuspeichern 
(Pfad ist damit beispielsweise Moodle311\www\moodle\blocks\activityfeedback). 
Sobald man anschliessend Moodle im Browser öffnet und sich als Administrator anmeldet, wird automatisch angezeigt, 
dass das Plugin installiert werden kann.
Am Ende der Installation werden die Einstellungen angezeigt. Da diese standardmässig sinnvoll eingestellt sind und sie auf Wunsch zu einem 
späteren Zeitpunkt angepasst werden können, kann dieser Punkt an dieser Stelle übersprungen werden.
Die Installation kann damit mit einem Klick auf "Änderungen speichern" am Ende der Seite beendet werden.

## Konfiguration
### Standardkonfiguration
Die Standardkonfiguration wird automatisch gezogen, wenn das Plugin neu installiert wird und man an den angezeigten Einstellungen keine Anpassung vornimmt.
Standardmässig stehen vier Emojis zur Auswahl, welche die Emotionen
* Begeisterung (Freude, Glück, Engagement, Flow)
* Langeweile
* Verwirrung und
* Frust (Wut)
repräsentieren, da diese in einer Lernsituation besonders relevant sind.

### Anpassung der Konfiguration
Die globalen Einstellungen des Plugins sind zu finden unter: Website-Administration -> Reiter Plugin -> Abschnitt Blöcke -> Akvititäten-Feedback. 
Grundsätzlich hat man die Möglichkeit, **maximal sieben Feedback-Optionen zu konfigurieren**.
Standardmässig sind vier eingeschaltet. Jede der sieben Optionen kann **aktiviert oder deaktiviert** werden.
Für jede aktive Option muss ein **Bild** hochgeladen werden, welches optimalerweise 40x40 Pixel gross ist. 
Falls bei den ersten vier Optionen kein Bild definiert wird, werden die Standardbilder genommen. 
Im zugehörigen Textfeld kann der zum Bild anzuzeigende **Text** definiert werden. 
Hier sollte der Text am besten bei jeder aktiven Option oder bei keiner angegeben werden.

### Aktivierung auf einem Kurs
Benutzende mit der Berechtigung, einen Kurs zu konfigurieren, können über **Bearbeiten einschalten** -> **Block hinzufügen** den Block
**Aktivitäten-Feedback** auswählen und damit auf einem Kurs aktivieren.
Sobald der Editiermodus wieder verlassen wird, wird der Block selbst ausgeblendet und die Feedback-Optionen werden auf jeder Aktivität des Kurses angezeigt.

## Bedienung
* Auf jeder Aktivität befindet sich ganz rechts ein Feedback-Button.
* Bei einem Klick darauf, hat man die Möglichkeit, eine der angezeigten Feedback-Optionen mit einem Klick auszuwählen.
* Die gewählte Option wird danach anstelle des bisherigen neutralen Buttons angezeigt.
* Um eine Feedback-Option wieder abzuwählen, muss die Option nochmals ausgewählt werden.
* Genauso kann auch anstelle des bereits abgegebenen Feedbacks direkt eine andere Feedback-Option ausgewählt werden.

## Technische Details
Das Feedback wird in der Tabelle prefix_block_activityfeedback gespeichert.

*Fernfachhochschule Schweiz, März 2022*