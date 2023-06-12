#!/bin/bash

#recupère le chemin absolu pour le volume sur Docker
chemin_absolu=$(readlink -f .)

#creer un fichier 
if ! [ -d "qrcode_fic" ]; 
then
    mkdir ./qrcode_fic
fi
for logo in './'logos/*.png
do
 #récupère uniquement le nom et pas le .png
 code_reg=$(basename "${logo}" | cut -d '.' -sf1 | tr '[:upper:]' '[:lower:]')
 #commande pour transformer l'image 
 docker container run -ti --rm -v $chemin_absolu:/work bigpapoo/sae103-qrcode qrcode -o qrcode_fic/qrcode_$code_reg.png "https://bigbrain.biz/$code_reg"

done
