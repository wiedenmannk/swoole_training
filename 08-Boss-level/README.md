# Boss Level – Observability und Debugging

## Ziel

In den vorherigen Übungen haben wir gelernt, wie ein Swoole-Server aufgebaut wird und wie Requests von Worker- und Task-Workern verarbeitet werden.

Im Boss Level betrachten wir den Server aus einer anderen Perspektive:

> Nicht **"Wie programmiere ich Swoole?"**, sondern **"Wie beobachte und analysiere ich ein laufendes Swoole-System?"**

In produktiven Anwendungen reicht es selten aus, nur zu wissen, dass ein Request bearbeitet wurde. Viel wichtiger ist die Frage:

* Welcher Worker hat den Request angenommen?
* Welcher Task Worker verarbeitet ihn?
* Wie lange wartet ein Task?
* Wie lange dauert die Verarbeitung?
* Welche Prozesse arbeiten gerade?
* Wo entstehen Engpässe?

Genau diese Fragen beantworten die Übungen dieses Kapitels.

---

## 01 – Task Tracing

Der Schwerpunkt liegt auf dem Lebenszyklus eines einzelnen Requests.

Ein Request wird vom Eingang bis zur Fertigstellung verfolgt.

Dabei werden unter anderem protokolliert:

* Trace-ID
* Request Worker
* Task Worker
* Wartezeit bis zum Start des Tasks
* Bearbeitungszeit
* Rückmeldung über `Finish`

Ziel ist es, den vollständigen Ablauf einer einzelnen Anfrage nachvollziehen zu können.

---

## 02 – Task Overview

Während sich das Task Tracing auf einen einzelnen Request konzentriert, zeigt Task Overview den aktuellen Zustand des gesamten Systems.

Über Diagnose-Endpunkte können Informationen wie folgende abgefragt werden:

* Status des Servers
* Aktive Worker
* Aktive Task Worker
* Laufende oder bekannte Tasks
* Speicherverbrauch des PHP-Prozesses

Die Ausgabe erfolgt bewusst als JSON und kann direkt mit `curl` oder `jq` ausgewertet werden.

Dadurch entsteht eine kleine Diagnose-API, mit der sich ein laufendes System jederzeit beobachten lässt.

---

## 03 – Linux Observability (Bonus)

Dieser optionale Teil erweitert die Beobachtungsebene über Swoole hinaus.

Neben den Informationen aus der Anwendung können zusätzlich Betriebssystemdaten betrachtet werden, beispielsweise:

* CPU-Auslastung
* Speicherverbrauch
* Prozesse
* offene Dateien
* Netzwerkverbindungen

Dadurch lassen sich Zusammenhänge zwischen der Swoole-Konfiguration und der tatsächlichen Auslastung des Systems erkennen.

---

## Fazit

Ein funktionierendes System ist nicht automatisch ein verständliches System.

Erst durch Logging, Tracing und Observability entstehen belastbare Fakten, mit denen Engpässe erkannt, Hypothesen überprüft und technische Entscheidungen nachvollziehbar begründet werden können.

Der Boss Level zeigt deshalb nicht nur, wie Swoole funktioniert, sondern vor allem, wie man ein produktives System analysiert und versteht.
