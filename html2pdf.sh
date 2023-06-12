#!/bin/bash

#recupère le chemin absolu pour le volume sur Docker
chemin_absolu=$(readlink -f .)

#creer un fichier 
if ! [ -d "pdf" ]; 
then
    mkdir ./pdf
fi
for fichier in './'dossier_html/*.html
do
 #récupère uniquement le nom et pas le .html
 nom=$(basename "${fichier}" | cut -d '.' -sf1)
 
 #commande pour transformer le html en pdf
 docker container run -v $chemin_absolu:/work bigpapoo/sae103-html2pdf "html2pdf ./dossier_html/$nom'.html' ./pdf/$nom'.pdf'"

done
#archivage des fichiers pdf 
tar czvf ./pdf.tar.gz ./pdf/
