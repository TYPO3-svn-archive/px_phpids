[DE]

Zum Auslesen der Datenbank erstellt das Plugin ein Backend Modul, dass sich
innerhalb von "Admin-Werkzeuge" im T3 Backend platziert.

Installiert wird die Extension als Frontend Plugin auf der Root-Seite des Webs.

Vor dem Einf�gen sollte man jedoch in der Datei
typo3conf/ext/px_phpids/IDS/Config/Config.ini die E-Mail Adresse des Webmasters
und die Datenbankverbindung angeben. Au�erdem ist es essentiell wichtig dieses
Verzeichniss (typo3conf/ext/px_phpids/IDS/Config/) mit einer .htaccess zu
sch�tzen oder die Config.ini au�erhalb des Web-Roots zu platzieren!

Zum testen, ob die Extension auch funktioniert kann man in der Datei
typo3conf/ext/px_phpids/pi1/class.tx_pxphpids_pi1.php den Debug-Modus anschalten
und so simulierte Hacking-Versuche starten.

F�r weitere Fragen verweise ich auf die doc/manual.sxw :-)

[EN]

Please refer to the doc/manual.sxw