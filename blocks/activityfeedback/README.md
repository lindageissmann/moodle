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
* Begeisterung (Freude, Glück)
* Langeweile
* Verwirrung und
* Frust (Wut)
repräsentieren, da diese in einer Lernsituation besonders relevant sind.

### Anpassung der Konfiguration
Die globalen Einstellungen des Plugins sind zu finden unter: Website-Administration -> Reiter Plugin -> Abschnitt Blöcke -> Akvititäten-Feedback. 
Grundsätzlich hat man die Möglichkeit, **maximal sieben Feedback-Optionen zu konfigurieren**.
Standardmässig sind vier eingeschaltet. Jede der sieben Optionen kann **aktiviert oder deaktiviert** werden.
Für jede aktive Option muss ein **Bild im PNG-Format** hochgeladen werden, welches optimalerweise 40x40 Pixel gross ist. 
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
Eine Übersicht über die Feedbacks ist nicht implementiert.
Es empfiehlt sich, das Plugin **https://moodle.org/plugins/block_configurable_reports**, zu installieren.
Als Anhaltspunkt kann folgendes SQL genutzt werden:

SELECT
cm.id AS AktivitaetID,
CASE modtype.name
WHEN 'assign' THEN (SELECT name FROM prefix_assign WHERE course = course.id AND id = cm.instance)
WHEN 'assignment' THEN (SELECT name FROM prefix_assignment WHERE course = course.id AND id = cm.instance)
WHEN 'book' THEN (SELECT name FROM prefix_book WHERE course = course.id AND id = cm.instance)
WHEN 'chat' THEN (SELECT name FROM prefix_chat WHERE course = course.id AND id = cm.instance)
WHEN 'choice' THEN (SELECT name FROM prefix_choice WHERE course = course.id AND id = cm.instance)
WHEN 'data' THEN (SELECT name FROM prefix_data WHERE course = course.id AND id = cm.instance)
WHEN 'feedback' THEN (SELECT name FROM prefix_feedback WHERE course = course.id AND id = cm.instance)
WHEN 'folder' THEN (SELECT name FROM prefix_folder WHERE course = course.id AND id = cm.instance)
WHEN 'forum' THEN (SELECT name FROM prefix_forum WHERE course = course.id AND id = cm.instance)
WHEN 'glossary' THEN (SELECT name FROM prefix_glossary WHERE course = course.id AND id = cm.instance)
WHEN 'h5pactivity' THEN (SELECT name FROM prefix_h5pactivity WHERE course = course.id AND id = cm.instance)
WHEN 'imscp' THEN (SELECT name FROM prefix_imscp WHERE course = course.id AND id = cm.instance)
WHEN 'label' THEN (SELECT name FROM prefix_label WHERE course = course.id AND id = cm.instance)
WHEN 'lesson' THEN (SELECT name FROM prefix_lesson WHERE course = course.id AND id = cm.instance)
WHEN 'lti' THEN (SELECT name FROM prefix_lti WHERE course = course.id AND id = cm.instance)
WHEN 'page' THEN (SELECT name FROM prefix_page WHERE course = course.id AND id = cm.instance)
WHEN 'quiz' THEN (SELECT name FROM prefix_quiz WHERE course = course.id AND id = cm.instance)
WHEN 'resource' THEN (SELECT name FROM prefix_resource WHERE course = course.id AND id = cm.instance)
WHEN 'scorm' THEN (SELECT name FROM prefix_scorm WHERE course = course.id AND id = cm.instance)
WHEN 'survey' THEN (SELECT name FROM prefix_survey WHERE course = course.id AND id = cm.instance)
WHEN 'url' THEN (SELECT name FROM prefix_url WHERE course = course.id AND id = cm.instance)
WHEN 'wiki' THEN (SELECT name FROM prefix_wiki WHERE course = course.id AND id = cm.instance)
WHEN 'workshop' THEN (SELECT name FROM prefix_workshop WHERE course = course.id AND id = cm.instance)
ELSE "andere Aktivitaet"
END AS Aktivitaet,
sections.name AS Abschnitt,
fb.fbname AS Feedback,
count(fb.id) AS Anzahl
FROM prefix_block_activityfeedback fb
INNER JOIN prefix_user usr ON usr.id = fb.userid
INNER JOIN prefix_course_modules cm ON cm.id = fb.cmid
INNER JOIN prefix_course course ON course.id = cm.course
INNER JOIN prefix_modules modtype ON modtype.id = cm.module
LEFT OUTER JOIN prefix_course_sections sections ON sections.id = cm.section
WHERE course.id = %%COURSEID%%
GROUP BY AktivitaetID, Aktivitaet, Feedback, Abschnitt
ORDER BY AktivitaetID

*Fernfachhochschule Schweiz, März 2022*