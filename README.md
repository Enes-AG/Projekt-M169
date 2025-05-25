# Projekt-M169 Realisierung Moodle-Upgrade mit Docker
<img src="https://github.com/user-attachments/assets/c1c78ab7-ecab-40a1-9f65-7af78f7361de" width="800"/>




##  Inhaltsverzeichnis
## Inhaltsverzeichnis

1. [Einleitung](#1-einleitung)  
2. [Zielsetzung](#2-zielsetzung)  
3. [Ausgangslage](#3-ausgangslage)  
4. [Vorgehen altes Moodle 3.10.11 Containersieren mit DB](#4-vorgehen-altes-moodle-31011-containersieren-mit-db)  
   - [4.1 VM starten & Pakete aktualisieren](#41-vm-starten--pakete-und-paketlisten-aktualieren-und-docker-installieren)  
   - [4.2 Verzeichnis erstellen](#42-verzeichnis-erstellen)  
   - [4.3 docker-compose.yml erstellen](#43-docker-composeyml-erstellen)  
   - [4.4 Dockerfile erstellen](#44-dockerfile-erstellen)  
   - [4.5 DB Dump erstellen](#45-db-dump-erstellen)  
   - [4.6 Moodle-Sourcecode kopieren und bearbeiten](#46-moodle-sourcecode-inkl-alle-files-kopieren-und-bearbeiten)  
   - [4.7 Container starten](#47-container-starten-bauen)  
   - [4.9 Fehler und Anpassung](#49-error)  
   - [4.9 Prüfung](#49-prüfen-ob-es-geklappt-hat)  
5. [Lokales Moodle auf Version 4.5.2 upgraden](#5-lokales-moodle-auf-version-452-upgraden)  
   - [5.1 Backup machen](#51-backup-machen)  
   - [5.2 Download der Moodle Version 3.11.17](#52-download-der-moodle-version-31117)  
   - [5.3 Moodle-Dateien ersetzen](#53--moodle-dateien-ersetzen)  
   - [5.4 config.php zurückkopieren](#54-configphp-zurückkopieren--dateirechte-prüfen)  
   - [5.5 Upgrade auf 3.11.17](#55-upgrade-ausführen-auf-version-31117)  
   - [5.6 Upgrade auf Version 4.1](#56-upgrade-auf-version-41)  
   - [5.7 Upgrade auf Version 4.2.1](#57-upgrade-auf-version-421)  
   - [5.8 Upgrade auf Version 4.5.4](#58-upgrade-auf-version-454)  
6. [Migration der neuen Version 4.5.2 in die Container Umgebung](#6-migration-der-neuen-version-452-in-die-container-umgebung)  
   - [6.1 Verzeichnisstruktur erstellen](#61-verzeichnisstruktur-erstellen)  
   - [6.2 docker-compose.yml erstellen](#62-docker-composeyml-erstellen)  
   - [6.3 .env erstellen](#63-env-erstellen)  
   - [6.4 Moodle-Source klonen](#64-moodle-source-in-master-klonen)  
   - [6.5 config.php erstellen](#65-configphp-erstellen)  
   - [6.6 Dockerfile erstellen](#66-dockerfile-erstellen)  
   - [6.7 Datenbank Dump erstellen](#67-datenbank-dump-erstellen)  
   - [6.8 Container bauen und hochfahren](#68-container-bauen-und-hochfahren)  
   - [6.9 Prüfung der Migration](#69-schauen-ob-alle-daten-korrekt-migriert-worden)  
   - [6.10 Anforderungen überprüfen](#610-anforderungen-überprüfen)  
   - [6.11 phpMyAdmin](#611-phpmyadmin)  
7. [Lernjournal](#7-lernjournal)  
   - [Lernjournal Arin](#71-lernjournal-arin)  
   - [Lernjournal Till](#72-lernjournal-till)  
   - [Lernjournal Levin](#73-lernjournal-levin)  
   - [Reflexion Gruppe](#74-reflexion-gruppe)  
8. [Quellenverzeichnis](#8-quellenverzeichnis)  




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

![image](https://github.com/user-attachments/assets/67875a0c-9b7b-40d6-b3c3-c030c7f644c7)



### 4.3 docker-compose.yml erstellen

Auf den erstellten Ordner **moodle-docker** springen, darin befindet sich das docker-compose.yml.

```bash
  cd moodle-docker
  nano docker-compose.yml
```

Wichtig ist das man die Seite gemäss der Anforderung auf Port **8080** leitet. 

![image](https://github.com/user-attachments/assets/4c122b03-2a86-44c9-ba84-c519cd0082b9)



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


![image](https://github.com/user-attachments/assets/aacb1c95-0f0a-4547-8a92-42049b95b78c)

![image](https://github.com/user-attachments/assets/275ad082-6484-4fed-b7d4-1fe93ecf0cfe)

![image](https://github.com/user-attachments/assets/47076468-9138-4be4-8138-f5f2c27909c5)


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

![image](https://github.com/user-attachments/assets/34403c9c-92db-48d7-a66a-56706bedf20d)

![image](https://github.com/user-attachments/assets/e656f4fa-ed53-42ad-8969-a24c2d436b00)

![image](https://github.com/user-attachments/assets/68837dd9-a9f5-41e6-8d6e-1d9a6d6b1354)

![image](https://github.com/user-attachments/assets/fe7e9369-c1b7-42ae-a3b4-9855f5074d90)

![image](https://github.com/user-attachments/assets/fd5fb8cd-a1b0-4a07-a0d5-1f08346e3a23)

![image](https://github.com/user-attachments/assets/29da9d33-5949-4c88-a7a5-6fe6b5a3260c)

![image](https://github.com/user-attachments/assets/82506e9d-0d2f-40a9-8218-bc34e2745798)

![image](https://github.com/user-attachments/assets/3f3f769c-728a-4aab-8659-62c0625f42d1)



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


![image](https://github.com/user-attachments/assets/761b2641-a67c-49c3-b5ed-8592b72bf159)

![image](https://github.com/user-attachments/assets/b7a69dfd-1370-4a28-9bc6-df15e5407fff)

![image](https://github.com/user-attachments/assets/e2c35e04-4e66-402a-81b4-1cc7244cce5c)




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



## 6. Migration der neuen Version 4.5.2 in die Container Umgebung


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

![image](https://github.com/user-attachments/assets/ca709b6c-ac46-4438-a4c3-45311c860c7b)




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


![image](https://github.com/user-attachments/assets/f926f972-5f23-4d66-8cc9-c3e6c80d225c)

![image](https://github.com/user-attachments/assets/76c28322-721e-4fc3-9214-bd9c57a9842e)

![image](https://github.com/user-attachments/assets/3d768817-8c35-4ae2-acf8-77206b067f33)




### 6.8 Container bauen und hochfahren

```bash
 cd ~/moodle-docker-setup
 docker-compose up -d --build

```

![image](https://github.com/user-attachments/assets/d83a5e55-6243-42b4-8d2a-3959ff082c4b)


### 6.9 Schauen ob alle Daten korrekt migriert worden

```bash
 docker-compose logs db | grep -i initdb
```

![image](https://github.com/user-attachments/assets/e2927100-75f2-4a33-85d8-52eaad9410b7)



```bash
 sudo mysql -u root -p   -e "SELECT COUNT(*) AS tables_old FROM information_schema.tables \
      WHERE table_schema='moodle';"

docker-compose exec -T db \
  mysql -u root -p"${MYSQL_ROOT_PASSWORD}" \
    -e "SELECT COUNT(*) AS tables_new \
        FROM information_schema.tables \
        WHERE table_schema='${MYSQL_DATABASE}';"

```

![image](https://github.com/user-attachments/assets/b9782c19-5b0a-4915-b9c5-348715a4fb76)

![image](https://github.com/user-attachments/assets/6419ad0f-fd42-4849-9ef5-06b71cac1646)


Im phpMyAdmin

![image](https://github.com/user-attachments/assets/a79e8682-9287-4b47-9bfb-0f8baf0604eb)

Auf Docker 

![image](https://github.com/user-attachments/assets/b2e09e60-2e3f-4e9e-8276-8802c2264b5a)

![image](https://github.com/user-attachments/assets/e6b6e47c-d841-42cf-bb7c-a8079e7b3706)

Alle Daten der alten Lösung sind korrekt migriert. 


### 6.10 Anforderungen überprüfen

![image](https://github.com/user-attachments/assets/f2f6422d-c72b-4894-af30-a474e8b3c71c)


```bash
 cd ~/moodle-docker-setup
docker-compose exec -T moodle \
  grep "\$release" /var/www/html/version.php
```

![image](https://github.com/user-attachments/assets/f4cb2c77-cdf4-41bd-ae07-1a66cd24d3c9)


Die Version stimmt.

Nun schauen wir ob sie in einem eigenen Netzwerk läfut und ob es einzelne Container gibt.

```bash
 docker ps -a
```
![image](https://github.com/user-attachments/assets/b4e333b8-b69d-49a6-a782-9fb6d9943ec0)



```bash
 docker network inspect moodle-docker-setup_moodle_network
```

![image](https://github.com/user-attachments/assets/7833fb65-207a-484d-9006-d967b02f4df3)
![image](https://github.com/user-attachments/assets/e9f94c2a-b749-4d9c-b32a-1a81e1d66dab)


Die Docker Version ist richtig. 
Die Container laufen in einem eigenen Netzwerk.


### 6.11 phpMyAdmin

phpMyAdmin ist über Port 8880 erreichbar und zeigt das die gesamte DB korrekt migriert wurde.

![image](https://github.com/user-attachments/assets/e7d01433-2018-433d-a9eb-94fb5fa40322)









## 7. Lernjournal
### 7.1 Lernjournal Arin

[Lernjournal Nr. 1](./Lernjournal/Lernjournal_1_Arin_Erenler.pdf)

[Lernjournal Nr. 2](./Lernjournal/Lernjournal_2_Arin_Erenler.pdf)

[Lernjournal Nr. 3](./Lernjournal/Lernjournal_3_Arin_Erenler.pdf)

### 7.2 Lernjournal Till

[Lernjournal Nr. 1](./Lernjournal/Lernjournal_1_Till_Schmuki.pdf)

[Lernjournal Nr. 2](./Lernjournal/Lernjournal_2_Till_Schmuki.pdf)

[Lernjournal Nr. 3](./Lernjournal/Lernjournal_3_Till_Schmuki.pdf)

### 7.3 Lernjournal Levin

[Lernjournal Nr. 1](./Lernjournal/Lernjournal_1_Levin_Schöbi.pdf)

[Lernjournal Nr. 2](./Lernjournal/Lernjournal_2_Levin_Schöbi.pdf)

[Lernjournal Nr. 3](./Lernjournal/Lernjournal_3_Levin_Schöbi.pdf)

### 7.4 Reflexion Gruppe
[Reflexion_Gruppe](./Lernjournal/Reflexion_Gruppe.pdf)


## 8. Quellenverzeichnis

https://docs.moodle.org/500/en/Moodle_version
https://download.moodle.org/releases/latest/
https://hub.docker.com/
https://www.reddit.com/

https://chatgpt.com/ = für Errors 


