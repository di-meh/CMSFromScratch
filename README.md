# CMSFromScratch

Un CMS from scratch en HTML5, CSS3, JS et PHP pour l'ESGI.

# Installation

## Framework CSS

- Au moment du clone: ``git clone --recurse-submodules <url>``
  
- Sinon faire ``git submodule update --init``
  
- dans le dossier ``www/framework`` faire la commande `npm install` 

- ``npm run build``
  
## CMS

Dans le dossier ``www/``:

- ``npm install``
  
- ``composer install``
  
- à la racine faire ``docker-compose up -d --build``
