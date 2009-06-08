[DE]

Zum Auslesen der Datenbank erstellt das Plugin ein Backend Modul, dass sich
innerhalb von "Admin-Werkzeuge" im T3 Backend platziert.

Installiert wird die Extension als Frontend Plugin auf der Root-Seite des Webs.

Vor dem Einfügen sollte man jedoch in der Datei
typo3conf/ext/px_phpids/IDS/Config/Config.ini die E-Mail Adresse des Webmasters
und die Datenbankverbindung angeben. Außerdem ist es essentiell wichtig dieses
Verzeichniss (typo3conf/ext/px_phpids/IDS/Config/) mit einer .htaccess zu
schützen oder die Config.ini außerhalb des Web-Roots zu platzieren!

Zum testen, ob die Extension auch funktioniert kann man in der Datei
typo3conf/ext/px_phpids/pi1/class.tx_pxphpids_pi1.php den Debug-Modus anschalten
und so simulierte Hacking-Versuche starten.

Für weitere Fragen verweise ich auf die doc/manual.sxw :-)

[EN]

Please refer to the doc/manual.sxw