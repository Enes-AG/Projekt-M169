# Projekt-M169 Realisierung Moodle-Upgrade mit Docker
<img src="https://github.com/user-attachments/assets/c1c78ab7-ecab-40a1-9f65-7af78f7361de" width="800"/>




##  Inhaltsverzeichnis
1. [Einleitung](#einleitung)
2. [Zielsetzung](#zielsetzung)
3. [Ausgangslage](#ausgangslage)
4. [Vorgehen](#vorgehen)
   - [Backup der alten Moodle-Version](#1-backup-der-alten-moodle-version)
   - [Docker-Setup für neue Version](#2-docker-setup-für-neue-version)
   - [Upgrade durchführen](#3-upgrade-durchführen)
5. [Probleme & Lösungen](#probleme--lösungen)
6. [Ergebnis & Fazit](#ergebnis--fazit)
7. [Screenshots](#screenshots)

##  1. Einleitung
In diesem Projekt wurde die bestehende Moodle-Installation die auf einer Ubuntu VM läuft von der Version **3.10.11** auf die aktuelle **LTS-Version 4.5.2** erfolgreich upgegradet und in Docker Container migriert. Ausserdem wurde die alte Moodle Plattform (Version **3.10.11**) in eine Docker Umgebung verschoben und so konfiguriert das sie über den Port **8080** läuft, diese Umgebung wurde auch per Banner klar als veraltet gekenzeichnet. 

##  2. Zielsetzung
- Upgrade auf aktuelle Moodle-Version (4.5.2)
- Nutzung von Docker zur Containerisierung
- Alle Daten der alten Lösung sind korrekt migriert.
- Altes System noch lauffähig unter Port 8080 (klare Kennzeichnung)
- Die Lösung soll in unterschiedlichen Containern in einem eigenen Netzwerk
lauffähig sein.
- Dokumentation für Reproduzierbarkeit


##  3. Ausgangslage

| Komponente       | Version/Status         |
|------------------|------------------------|
| Moodle           | 3.10.11                |
| PHP              | 7.4 (veraltet)         |
| Datenbank        | MySQL                  |
| Containerisierung| Noch nicht vorhanden   |

## 4. Vorgehen

### 4.1 VM starten & Pakete und Paketlisten aktualieren
```bash
sudo apt update
sudo apt upgrade
```

### 4.2 Docker-Setup für neue Version

```bash
docker-compose up -d
docker cp moodle_db_backup.sql moodle-db:/moodle_db_backup.sql
docker exec -it moodle-db mysql -u root -proot moodle < /moodle_db_backup.sql
```

### 4.3 Anpassung PHP-Version
PHP-Version anpassen in Docker-Compose.yaml wegen nicht unterstützer PHP Version **8.2**

```bash
  moodle-web:
    image: moodlehq/moodle-php-apache:8.1
```

### 4.4 Upgrade durchführen
```bash
docker cp moodle-4.1.11/ moodle-web:/var/www/html/
docker cp config.php.backup moodle-web:/var/www/html/config.php
docker exec -it moodle-web php admin/cli/upgrade.php
```

## 5. Probleme & Lösung

## 6. Ergebnis & Fazit


## 7. Quellenverzeichnis
## 8. Netzwerkplan
