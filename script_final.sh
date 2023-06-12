#!/bin/bash


php ./textes/main.php

echo etape 1 good

bash gen_qrcode.sh

echo etape 2 good

bash conversion_img.sh

echo etape 3 good

php fusion.php

echo etape 4 good

bash html2pdf.sh 

echo etape 5 good
