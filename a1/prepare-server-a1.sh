#!/usr/bin/env bash
sudo sh -c 'cd /opt/lampp/htdocs/Architektur-Verteilter-Systeme && git fetch origin master && git reset --hard FETCH_HEAD && git clean -df' &&
cd /opt/lampp/htdocs/Architektur-Verteilter-Systeme/a1 &&
sudo chmod 777 messages.txt &&
sh /opt/lampp/lampp startapache &&
read -p "Server started: http://localhost/Architektur-Verteilter-Systeme/a1/a1.php"