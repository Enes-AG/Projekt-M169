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

## 4. Vorgehen altes Moodle 3.10.11 Containersieren samt DB

### 4.1 VM starten & Pakete und Paketlisten aktualieren
```bash
sudo apt update
sudo apt upgrade
```

Wenn man sudo apt upgrade durchführt taucht zweimal diese Meldung auf.
Man sollte beide male die oberste Option anwählen und mit Enter bestätigen.

![Screenshot 2025-05-25 164421](https://github.com/user-attachments/assets/2967f1e8-e704-4b09-9b64-b7d46de98933)

Nachdem alles durchgelaufen ist die VM neustarten.

### 4.2 Verzeichnis erstellen

```bash
mkdir -p moodle-docker/{data,docker,dumps,wwwroot} && touch moodle-docker/docker-compose.yml
```

![image](https://github.com/user-attachments/assets/093dae61-77f5-4663-aaf8-4d0ff316e6ef)


### 4.3 docker-compose.yml erstellen

Auf den erstellten Ordner **moodle-docker** springen, darin befindet sich das docker-compose.yml.

```bash
  cd moodle-docker
  nano docker-compose.yml
```

Wichtig ist das man die Seite gemäss der Anforderung auf Port **8080** leitet. 

![image](https://github.com/user-attachments/assets/d2c86b33-e2c1-430c-82b6-ad96746305f9)



### 4.4 Dockerfile erstellen

Nun muss ein passendes Dockerfile erstellt werden.

```bash
cd docker/
mkdir moodle
cd moodle/
nano Dockerfile
```

Auf die richtige PHP Version achten!

![image](https://github.com/user-attachments/assets/8c029207-1eab-42d5-92b5-278b3affeb69)



### 4.5 DB Dump erstellen 

Nun muss ein Datenbank Dump erstellt werden damit die Daten übernommen werden. 

```bash
sudo mysqldump -u root -p moodle > dumps/moodle-dump.sql

```

![image](https://github.com/user-attachments/assets/3f2a01ac-6050-481d-9878-82f7699780a7)


### 4.6 Moodle-Sourcecode inkl. alle Files kopieren und bearbeiten

```bash
cp /var/www/html/config.php ~/moodle-docker/wwwroot/
```

Wurde erfolgreich kopiert.

![image](https://github.com/user-attachments/assets/7f04cd18-4a8c-4c18-8261-4cb136acd228)

Nun muss die config.php Datei angepasst werden.

```bash
nano ~/moodle-docker/wwwroot/config.php
```
![image](https://github.com/user-attachments/assets/3bc6c34c-b85e-46a4-a7aa-3355d275a59b)


### 4.7 Container starten (bauen)

```bash
docker compose up -d --build
```
![image](https://github.com/user-attachments/assets/b0fd82db-4878-4a0f-b820-84d67886b595)



### 4.8 Prüfen ob es geklappt hat

Moodle läuft im Container über Port 8080, alle Daten sind vorhanden und es ist klar als altes System gekenzeichnet. 

![image](https://github.com/user-attachments/assets/4769d50c-1c66-4c7c-a73f-e0900bd4ceb7)


![image](https://github.com/user-attachments/assets/4cef18aa-d1da-4610-bf84-092c56d06a55)


![image](https://github.com/user-attachments/assets/902174c5-24cd-46e5-8806-8462f290105d)











## 5. Probleme & Lösung

## 6. Ergebnis & Fazit


## 7. Quellenverzeichnis
## 8. Netzwerkplan
