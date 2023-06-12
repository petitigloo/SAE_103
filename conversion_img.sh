#!/bin/bash

#recupère le chemin absolu pour le volume sur Docker
chemin_absolu=$(readlink -f .)

if ! [ -d ./photos/"photos_modif" ]; 
then
    mkdir ./photos/photos_modif
fi

for photo in './'photos/*.svg
do
 #recupère uniquement le nom et pas le .svg
 nom_img=$(basename "${photo}" | cut -d '.' -sf1)
 
 #commande pour transformer l'image 
 docker container run -v $chemin_absolu:/work bigpapoo/sae103-imagick "convert $photo -shave 45x45 -type grayscale -resize 200x200  './'photos/photos_modif/$nom_img'.png'"

 
done
