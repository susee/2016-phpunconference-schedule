PHP Unconference schedule
=========================

Auf der Suche nach dem Schedule?
http://bootev.github.io/2015-phpunconference-schedule

Installation
------------

Um den Schedule zu Generieren müssen erst alle Abhängigkeiten geladen werden.
Dazu benötigst du Composer und Bower. Wenn beides vorhanden ist:

```
composer install && bower install
```

Danach kannst du mit folgendem Befehl den Schedule generieren.

```
php build.php phpuc:build
```

Konfiguration
-------------

Der Plan wird in der schedule.yml gespeichert. Die Datei muss folgenden Aufbau haben

```
<index>:
  title: <Sprechender Name des tages>
  rooms:
    <1..n Raumnamen als Array>
  slots:
    <index>:
      time: <Angabe der Uhrzeit>
      rooms:
        <Gleiche Anzahl an Talks in der Reihemnfolge in der auch die Räume oben angegeben wurden>
```

Workflow
--------

Nachdem der Schedule generiert wurde kann man den gesamten Inhalt des Ordners ./build in den branch gh-pages
commiten.