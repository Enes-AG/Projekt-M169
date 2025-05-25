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
8. [Lernjournal](#Lernjournal)
   - Lernjournal Arin[Lernjournal_Arin](#Lernjournal_Arin)
   - Lernjournal Till[Lernjournal_Till](#Lernjournal_Till)
   - Lernjournal Levin[LernjournalLevin](#LernjournalLevin)

##  1. Einleitung
In diesem Projekt wurde die bestehende Moodle-Installation die auf einer Ubuntu VM läuft von der Version **3.10.11** auf die aktuelle **LTS-Version 4.5.2** erfolgreich upgegradet und in Docker Container migriert. Ausserdem wurde die alte Moodle Plattform (Version **3.10.11**) in eine Docker Umgebung verschoben und so konfiguriert das sie über den Port **8080** läuft, diese Umgebung wurde auch per Banner klar als veraltet gekenzeichnet. 

##  2. Zielsetzung
- Upgrade auf aktuelle Moodle-Version (4.5.2)
- Nutzung von Docker zur Containerisierung
- Alle Daten der alten Lösung sind korrekt migriert.
- Altes System noch funktionsfähig unter Port 8080 (klare Kennzeichnung)
- Die Lösung soll in unterschiedlichen Containern in einem eigenen Netzwerk
  funktionsfähig sein.
- Dokumentation für Reproduzierbarkeit


##  3. Ausgangslage

| Komponente       | Version/Status         |
|------------------|------------------------|
| Moodle           | 3.10.11                |
| PHP              | 7.4 (veraltet)         |
| Datenbank        | MySQL                  |
| Containerisierung| Noch nicht vorhanden   |

## 4. Vorgehen altes Moodle 3.10.11 Containersieren mit DB

### 4.1 VM starten & Pakete und Paketlisten aktualieren und Docker installieren
```bash
sudo apt update
sudo apt upgrade
sudo snap install docker
sudo apt  install docker-compose
```

Wenn man sudo apt upgrade durchführt taucht zweimal diese Meldung auf.
Man sollte beide male die oberste Option anwählen und mit Enter bestätigen.

![Screenshot 2025-05-25 164421](https://github.com/user-attachments/assets/2967f1e8-e704-4b09-9b64-b7d46de98933)

Nachdem alles durchgelaufen ist, die VM neustarten.

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


### 4.9 Error

Es kommt Folgende Error Meldung:

![image](https://github.com/user-attachments/assets/fa14a134-1819-43bc-a425-d3ede89c9de5)

Das config.php musste auf diesen Zeilen angepasst werden:
Der Grund war das in der config.php und im yml File 2 verschiedene Sachen eingetragen waren.

$CFG->dbuser    = 'moodle';
$CFG->dbpass    = 'moodlepass';

Geändert zu:

![image](https://github.com/user-attachments/assets/0fc3db24-58fc-461c-8542-64de70ed0ef7)


### 4.9 Prüfen ob es geklappt hat

Moodle läuft im Container über Port 8080, alle Daten sind vorhanden und es ist klar als altes System gekenzeichnet. 

![image](https://github.com/user-attachments/assets/4769d50c-1c66-4c7c-a73f-e0900bd4ceb7)


![image](https://github.com/user-attachments/assets/4cef18aa-d1da-4610-bf84-092c56d06a55)


![image](https://github.com/user-attachments/assets/902174c5-24cd-46e5-8806-8462f290105d)

![image](https://github.com/user-attachments/assets/275ad082-6484-4fed-b7d4-1fe93ecf0cfe)

![image](https://github.com/user-attachments/assets/9b545fa4-2b08-4864-ad9c-b9388e3e44a6)

## 5. Lokales Moodle auf Version 4.5.2 upgraden 

### 5.1 Backup machen

```bash
# Datenbank sichern
sudo mysqldump -u root -p moodle > moodle_backup.sql

# Moodle-Datenverzeichnis sichern
cp -r /var/www/html/moodle /var/www/html/moodle_backup

# Moodledata sichern
sudo cp -a /var/www/moodledata /var/moodledata_backup
```

![image](https://github.com/user-attachments/assets/f6da29eb-a7bb-45b4-bb33-7aad4afecd1b)

![image](https://github.com/user-attachments/assets/a8344e58-d85d-42ac-97e3-59ec614169ed)

![image](https://github.com/user-attachments/assets/a709ceb0-7aca-4180-8fbd-3a31c30a423f)



### 5.2 Download der Moodle Version 3.11.17

Das Moodle muss Schritt für Schritt geupgradtet werden weil es sonst zu Fehler kommen kann.

```bash
cd /tmp
wget https://download.moodle.org/download.php/direct/stable311/moodle-latest-311.tgz
```

![image](https://github.com/user-attachments/assets/7ed51cec-22e7-4726-ad0e-200b5579bb66)


### 5.3  Moodle-Dateien ersetzen

```bash
sudo systemctl stop apache2
sudo mv /var/www/html /var/www/html_old
sudo tar -xzf /tmp/moodle-latest-311.tgz -C /var/www/
sudo mv /var/www/moodle /var/www/html
```

![image](https://github.com/user-attachments/assets/5f5db247-52ca-44a7-80c9-ec654daf4ed3)

![image](https://github.com/user-attachments/assets/1a65d6d8-bdd2-4850-b379-701787926913)

![image](https://github.com/user-attachments/assets/fcfbe79e-083c-4d46-b32e-6bd9a3b9d4b3)

![image](https://github.com/user-attachments/assets/342b099e-8d1d-4a1e-abe5-d4e8eb6087f4)


### 5.4 config.php zurückkopieren & Dateirechte prüfen

```bash
sudo cp /var/www/html_old/config.php /var/www/html/
sudo find /var/www/html -type f -exec chmod 0644 {} \;
sudo find /var/www/html -type d -exec chmod 0755 {} \;
```

![image](https://github.com/user-attachments/assets/d058fc3b-8d9b-45d6-a6c8-705c5e04a345)

![image](https://github.com/user-attachments/assets/163fbbdd-2c46-4fd6-bace-54506d6dd672)


### 5.5 Upgrade ausführen auf Version 3.11.17

Diese Fehlermeldung kam:

![image](https://github.com/user-attachments/assets/ea5f4776-354f-4cb1-aed8-72aaed3a5c3c)

Diese Befehle wurden ausgeführt.

```bash
sudo -u www-data php /var/www/html/admin/cli/upgrade.php
sudo apt install php8.1-mysqli
sudo systemctl restart apache2
sudo phpenmod mysqli
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.0 php8.0-cli php8.0-common php8.0-mysql php8.0-xml php8.0-mbstring php8.0-curl php8.0-zip php8.0-gd php8.0-soap php8.0-intl
sudo a2dismod php8.1
sudo a2enmod php8.0
sudo update-alternatives --set php /usr/bin/php8.0
sudo systemctl restart apache2
sudo -u www-data php /var/www/html/admin/cli/upgrade.php
```

Danach kam diese Meldung:

![image](https://github.com/user-attachments/assets/5656c658-106b-44ce-9257-9109188a08e2)

```bash
sudo nano /etc/php/8.0/apache2/php.ini
sudo nano /etc/php/8.0/cli/php.ini
```

Die max_input_vars müssen von 1000 auf 5000 erhöht werden. 
Das ganze im php.ini.


![image](https://github.com/user-attachments/assets/dffaeca0-c006-457f-a97c-e2da776e3c13)


Zu


![image](https://github.com/user-attachments/assets/84574934-7e21-47b6-9c06-80aec0f09006)



Nun kann man localhost aufrufen und das Upgrade per Web Interface starten.

![image](https://github.com/user-attachments/assets/2d7b377f-2940-4f2e-bb32-1ddf4a8fd7dd)

![image](https://github.com/user-attachments/assets/31a8fe60-8162-4663-ae28-b8294d5a8842)

![image](https://github.com/user-attachments/assets/e656f4fa-ed53-42ad-8969-a24c2d436b00)

![image](https://github.com/user-attachments/assets/68837dd9-a9f5-41e6-8d6e-1d9a6d6b1354)

![image](https://github.com/user-attachments/assets/fe7e9369-c1b7-42ae-a3b4-9855f5074d90)

![image](https://github.com/user-attachments/assets/fd5fb8cd-a1b0-4a07-a0d5-1f08346e3a23)

![image](https://github.com/user-attachments/assets/29da9d33-5949-4c88-a7a5-6fe6b5a3260c)

![image](https://github.com/user-attachments/assets/678a04e2-0240-4d19-a7de-82ef5329be88)

![image](https://github.com/user-attachments/assets/165c2301-ec55-45bb-be19-4c72a2982699)

![image](https://github.com/user-attachments/assets/2c1aeb1a-b9ca-43dd-bd52-f143cb30f8c9)

Das Upgrade ist erfolgreich.



### 5.6 Upgrade auf Version 4.1

```bash
cd /tmp
sudo systemctl stop apache2
sudo mv /var/www/html /var/www/html_311_backup
sudo tar -xzf /tmp/moodle-400.tgz -C /var/www/
sudo mv /var/www/moodle /var/www/html
sudo cp /var/www/html_311_backup/config.php /var/www/html/
sudo apt install composer
sudo chown -R $USER:$USER /var/www/html
composer install --no-dev
sudo -u www-data php -d max_input_vars=5000 /var/www/html/admin/cli/upgrade.php
```

![image](https://github.com/user-attachments/assets/4b12a778-6821-4dd2-aa7e-e2b3b89353ac)

![image](https://github.com/user-attachments/assets/adb43df9-1cc9-48d2-aa83-a643c5ff4f22)

![image](https://github.com/user-attachments/assets/dcbd941a-b89c-4120-a4b9-862c1d8e11cf)

Nun ist es auf 4.0, jetzt geht es auf 4.1.

```bash
cd /tmp
wget https://download.moodle.org/download.php/direct/stable401/moodle-latest-401.tgz -O moodle-401.tgz
sudo systemctl stop apache2
sudo mv /var/www/html /var/www/html_400_backup
sudo tar -xzf /tmp/moodle-401.tgz -C /var/www/
sudo mv /var/www/moodle /var/www/html
sudo cp /var/www/html_400_backup/config.php /var/www/html/
sudo apt install composer
composer install --no-dev
sudo chown -R www-data:www-data /var/www/html
sudo -u www-data php -d max_input_vars=5000 /var/www/html/admin/cli/upgrade.php
sudo apt install php8.1-mbstring
sudo systemctl restart apache2
```


![image](https://github.com/user-attachments/assets/6ba222d2-9716-4a9c-8cce-8574ae0f6032)

![image](https://github.com/user-attachments/assets/b7a69dfd-1370-4a28-9bc6-df15e5407fff)

![image](https://github.com/user-attachments/assets/58157ded-7922-4460-928f-91e84f31fae4)




### 5.7 Upgrade auf Version 4.2.1

```bash
cd /tmp
wget https://download.moodle.org/download.php/direct/stable402/moodle-latest-402.tgz -O moodle-402.tgz
sudo systemctl stop apache2
sudo mv /var/www/html /var/www/html_401_backup
sudo tar -xzf /tmp/moodle-402.tgz -C /var/www/
sudo mv /var/www/moodle /var/www/html
sudo cp /var/www/html_401_backup/config.php /var/www/html/
cd /var/www/html
sudo chown -R $USER:$USER /var/www/html
composer install --no-dev
sudo chown -R www-data:www-data /var/www/html
sudo -u www-data php -d max_input_vars=5000 /var/www/html/admin/cli/upgrade.php
sudo systemctl start apache2
sudo apt install php8.1-intl
sudo systemctl restart apache2
```

![image](https://github.com/user-attachments/assets/3a1e4acd-94ef-4221-9874-ead70d7eb962)

![image](https://github.com/user-attachments/assets/db510008-db91-433c-97bf-95937214d4ec)



### 5.8 Upgrade auf Version 4.5.4

```bash
cd /tmp
wget https://download.moodle.org/download.php/direct/stable405/moodle-latest-405.tgz -O moodle-405.tgz
sudo systemctl stop apache2
sudo mv /var/www/html /var/www/html_423_backup
sudo tar -xzf /tmp/moodle-405.tgz -C /var/www/
sudo mv /var/www/moodle /var/www/html
sudo cp /var/www/html_423_backup/config.php /var/www/html/
cd /var/www/html
sudo update-alternatives --set php /usr/bin/php8.1
sudo apt install php8.1-curl php8.1-zip php8.1-gd
sudo systemctl restart apache2
sudo chown -R $USER:$USER /var/www/html
composer install --no-dev
sudo -u www-data php -d max_input_vars=5000 admin/cli/upgrade.php
```

![image](https://github.com/user-attachments/assets/b1c55536-2ca9-49bd-a10c-332b16a716fc)

![image](https://github.com/user-attachments/assets/17223248-0915-47fc-a035-eb2d8c731c97)

![image](https://github.com/user-attachments/assets/03ac834d-fd1d-4d25-82f2-84ecb4f83b86)


Hiermit wurde Moodle auf der VM auf die Version 4.5.4 geupdatet samt allen Daten nun muss dieses System in eine Container Umgebung migriert werden.



## 6. Migration der neuen Version 4.5.4 in die Container Umgebung


### 6.1 Verzeichnisstruktur erstellen

## Project Structure

```bash
moodle-docker-setup/
├── dataroot/            # Moodle’s uploaded files and private data directory
├── db/                  # Persistente Datenbankdateien und Dumps
├── docker-compose.yml   # Definition der Docker-Services und Netzwerke
├── master/              # Haupt-Branch oder zentraler Projekt-Code
├── moodle/              # Moodle-Anwendung (Source, Dockerfile, config.php)
└── phpmyadmin/          # phpMyAdmin-Konfiguration und Assets


```bash
mkdir -p moodle-docker-setup/{dataroot,db,master,moodle,phpmyadmin} \
&& touch moodle-docker-setup/docker-compose.yml
```

### 6.2 docker-compose.yml erstellen


```bash
cd moodle-docker-setup/
nano docker-compose.yml

```

![image](https://github.com/user-attachments/assets/4fed17a7-cde7-44e7-9eb6-84865c41133b)


### 6.3 .env erstellen

```bash
nano .env
```

![image](https://github.com/user-attachments/assets/05d98ff3-61aa-436d-adac-729f6479b768)


### 6.4 Moodle-Source in master/ klonen

```bash
git clone https://github.com/moodle/moodle.git master --branch MOODLE_405_STABLE --depth 1
```

![image](https://github.com/user-attachments/assets/a20cdcc5-91d5-4abf-821d-db95e3e2d03f)

### 6.5 config.php erstellen

```bash
 cd master/
 nano config.php
```

![image](https://github.com/user-attachments/assets/ed0518b5-4de9-4bff-9241-61c3aa01c6a5)




### 6.6 Dockerfile erstellen

```bash
 cd moodle/
nano Dockerfile
```


![image](https://github.com/user-attachments/assets/533c7ea0-2e8b-46b1-b687-804f8070c145)


### 6.7 Datenbank Dump erstellen

```bash
 cd ..
 sudo mysqldump -u root -p moodle > ~/old_mysql_dump.sql
 cp ~/moodle_backup.sql db/moodle.sql
 rm -rf dataroot/*
 tar xzf ~/moodle_backup_files.tar.gz -C dataroot
 chmod -R 0777 dataroot
```

![image](https://github.com/user-attachments/assets/b5c46b03-264d-49a5-a13f-c233c8d779f7)

![image](https://github.com/user-attachments/assets/3950be52-67cb-4e95-8ab8-847ff08485b4)

![image](https://github.com/user-attachments/assets/ea047d9a-22db-42a1-b3f1-6d86f6812661)




### 6.7 Container bauen und hochfahren

```bash
 cd ~/moodle-docker-setup
 docker-compose up -d --build

```

![image](https://github.com/user-attachments/assets/d83a5e55-6243-42b4-8d2a-3959ff082c4b)


### 6.8 Schauen ob alle Daten korrekt migriert worden

```bash
 docker-compose logs db | grep -i initdb

```

![image](https://github.com/user-attachments/assets/30d9d3fe-5208-4288-b391-f6c65d8f7321)


## 8. Lernjournal
### 8.1 Lernjournal Arin

[Meine PDF-Datei anzeigen](./Lernjournal/Lernjournal_1_Arin_Erenler.pdf)

[Meine PDF-Datei anzeigen](./Lernjournal/Lernjournal_2_Arin_Erenler.pdf)

[Meine PDF-Datei anzeigen](./Lernjournal/Lernjournal_3_Arin_Erenler.pdf)

### 8.2 Lernjournal Till

[Meine PDF-Datei anzeigen](./Lernjournal/Lernjournal_1_Till_Schmuki.pdf)

[Meine PDF-Datei anzeigen](./Lernjournal/Lernjournal_2_Till_Schmuki.pdf)

[Meine PDF-Datei anzeigen](./Lernjournal/Lernjournal_3_Till_Schmuki.pdf)

### 8.3 Lernjournal Levin

[Meine PDF-Datei anzeigen](./Lernjournal/Lernjournal_1_Levin_Schöbi.pdf)

[Meine PDF-Datei anzeigen](./Lernjournal/Lernjournal_2_Levin_Schöbi.pdf)

[Meine PDF-Datei anzeigen](./Lernjournal/Lernjournal_3_Levin_Schöbi.pdf)

## 7. Quellenverzeichnis
## 8. Netzwerkplan
