# Installation  

****

## Docker Wordpress@5.5-php7.4-fpm / node@12.18 / mysql@5.7 / composer / nginx

Solution dockerisée avec 5 containers. Tourne avec le https.

Si vous souhaitez utiliser la base partagée, vous pouvez commenter les lignes du container **db** et la dépendance **db** du service **nginx** ligne 76 du **fichier .docker/docker-compose.yml.**

Dans le répertoire .docker se situent tous les fichiers nécessaires à la config et au build des différents containers ainsi que différents scripts pour lancer et arrêter les services.

### Configuration

#### 1 - Editer le fichier .env

Dupliquer et renommer .docker/.env-sample => **.docker/.env** **/!\ ne pas renommer autrement**


#### 2 - Ajouter le ServerName dans hosts, Générer les certificats, start
```
.docker/scripts/hosts-file-setup.sh
.docker/scripts/cert-create.sh
.docker/scripts/cert-trust.sh
.docker/run.sh
```

Editer la feuille CSS **web/wp-content/themes/[default ou ${WP_THEME_NAME}]/style.css** : changer l'entête

```
/*
Theme Name: Mon Theme
Author: Lonsdale Dev Team
Author URI: https://www.lonsdale.fr/
Version: 1.0
Text Domain: default
*/
```

**sur wordpress@5.5-php7.4-fpm :** wp-cli est installé, entrer dans le container : (${APP_NAME} est renseigné dans .docker/.env)
```
docker exec -it ${APP_NAME}-wp bash
```
puis se référer aux commands https://developer.wordpress.org/cli/commands/
```
wp-cli --info
```

pour couper les containers
```
.docker/stop.sh
```

#### Rentrer dans un shell à l'intérieur du conteneur
```
docker exec -it [container] /bin/bash
```

#### Imprimer les logs du conteneur docker
```
docker logs [container_name || container_id] -f
```


## Wordpress Administration
```
Dans apparence séléctionner le nouveau theme
```

## Process deploiement automatique

### 1 - Pour activer la CI/CD
Renommez le fichier .gitlab-ci-sample.yml en .gitlab-ci.yml

### 2 - ticket bearsteck
```
Bonjour

Pouvez-vous :
- Créer un environnement de préprod "[projet].preprod.lonsdale.io" sur "lonsdale-preprod.ovh.bearstech.com".
- Créer un environnement de prod "nom de domaine" sur "lonsdale.ovh.bearstech.com".
- Mettre en place une protection htaccess sur la preprod
- Activer HTTPS via lets encrypt.
- Ajouter ces Variables d'environnement pour que nous puissions y accéder depuis le code php (pouvez-vous remplacer les "XXXX" par la valeur correspondante que vous connaitrez lors de la création de l'environnement)

° MYSQL_DATABASE=XXXXX
° MYSQL_USER=XXXXX
° MYSQL_PASSWORD=XXXXX
° MYSQL_HOST=127.0.0.1

- Ajouter cette clé pour l'accès au serveur "lonsdale-preprod.ovh.bearstech.com" ?
ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQC06uGrpswAZrIpRQfiAxOrGiuLZguefwzyljyL4VPlBo+YPNl7vcJB3NeQ2DllWH7khHCkYHk0+RoXL2mRembUlfMwT5+CZyDRuD26QAPwVFBRVqOuZW+WAP0XK9+prMJobQqtV7B0JcrzRLTiLZ3lcCtPqnuJEWEJ3mTTMCmD53Htnkh7w1t0arEaXedBDkQ4LXqbNCjz8PU1lxDf5V/bzoDGGTNBHW3bEqNEUF8KqRqDbh8vDE4JwVWalR5ypZdH0dfcAjG6YLD+fi+MnqMOjMjZSWmj6PNVe537zPXwGiqPCwHl7eVrHA0+dUVQ1cRAJfM7HIOzIqR6j/JxLkc7 deploy@lonsdale.fr

Merci
```

### 3 - gitlab

Télécharger https://projects.lonsdale.fr/#/files/7047136, copier contenu clé privé et la mettre dans la var SSH_KEY

Dans Settings > CI / CD > Variables, ajouter: (key / value)
PP_DOCROOT = root
PP_SSH_SERVER = lonsdale-preprod.ovh.bearstech.com
PP_SSH_USER = [le user du projet]
SSH_KEY = CLE_PRIVEE_DU_FICHIER_TELECHARGE

Dans le repo à la racine: .gitlab-ci.ym:
décommenté de la ligne 7 à 22

Créer une branche preprod et la protéger
settings > repository > Protected Branches (mettre maintainers dans selects) 

### 4 - installation de la preprod

Se connecter en ssh au serveur bearstech:
```
ssh user@lonsdale-preprod.ovh.bearstech.com
```

**génerer cle ssh:**
ssh-keygen
cat ~/.ssh/id_rsa.pub 
copier la cle dans:
Settings > Repository > Deploy Keys

**Vider le dossier root:**
```
cd root
rm -rf web
git clone [le repo du projet] .
```

**Attention** checkout preprod

### 5 - base bearstech
Si besoin d'importer la base sur la préprod, voici comment obtenir les infos de connexions

Lire le fichier .my.cnf pour récupérer le password
```
cd [le repo du projet]/
ls -la
cat .my.cnf
```

host: 127.0.0.1  
user: [le user du projet]  
password: my.cnf password  
  
shh host: lonsdale-preprod.ovh.bearstech.com  
ssh user: [le user du projet]  
ssh key: user key id_rsa  

<hr>

### workfow
L'environnement utilise les modules js natif<br>
Les styles qui sont dans **asets/styles** seront compilés dans un fichier, pour pouvoir gérer le css critque<br>
Les **css** et/ou les **js** des views ne seront chargé que si la view et dans la page



