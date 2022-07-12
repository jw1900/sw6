# SW6 Test Plugin
Das Plugin lässt bis 10 Produkte eintragen und auf einmal zum Warenkorb hinzufügen. Danach erscheint die Warenkorb-Seite.

## Benutzung
Der Eingangspfad ist /**fastorder**.
In Product-number-feldern können Produktnummer eingetragen werden. Beim Eintragen soll jeweils Datalist mit Vorschlägen von verfügbaren Produkten erscheinen. Die wird durch ProductController (**hintProducts**-Methode) geliefert.
Wenn eine inkorrekte Produktnummer eingetragen worden ist, erscheint das Feld rot markiert, ganz unten soll auch eine Fehlermeldung sichtbar sein, daß rot markierte Produktnummer beim Hinzufügen in den Warenkorb ignoriert werden.
Wenn eine korrekte Produktnummer eingetragen ist, bekommt das Quantity-Feld **required** Attribut, muss also vor dem Absenden auch ausgefüllt werden.
## Architektur
Es gibt 2 Kontroller:
1. **TestController** - mit definiertem Startpfad (**fastorder**) + twig-Template mit dem Formular.
2. **ProductController** mit Methoden für Unterstützung von:
- von Javascript ausgerufenen Requests für Vorschläge von verfügbaren Produkten (**hintProducts**)
- von Javascript Requests für Finalkontrolle, ob die eingetragene Produktnummer korrekt ist, dann wird als Response ID des Produktes geliefert (**checkProduct**) und weiter beim Hinzufügen in den Warenkorb verwendet
- Hinzufügung der Produkte in den Warenkorb (**addToCart**)

Sowohl vorgeschlagen als auch zugelassen werden Produkte, die:
- verfügbar und
- aktiv sein und 
- keine "Children" (Subvarianten) haben.

Die Javascript-Scripts sollten natürlich als Plugins geliefert werden, ich konnte aber leider das Storefront nicht kompilieren, ich habe es versucht, aber wegen vieler Fehler leider nicht geschafft, weder im Docker-Container noch lokal in SW-Installation unter Windows und WAMP. So habe ich sie also in einer nicht eleganter Form halt in twig-Datei als Funktionen gelassen.
