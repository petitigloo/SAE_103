<?php
    $dossierRendu = "./rendu"; //Repertoire avec fichiers .dat
    $dossier = "./textes/";
    $ftexte = file("$dossierRendu/texte.dat");
    $fconfig = file("./textes/region.conf");
    $dhtml = "./dossier_html"; 

    if (is_dir($dossier)) //si $doss est un dossier
    {
        if ($doss = opendir($dossier)) //On ouvre le dossier $doss
        {
            while (($file = readdir($doss)) !== false) //Tant qu'il y a des document dans $doss
            {
                if (strpos($file,".txt") !== false) //Si le fichier est en .txt
                {
                    $fichier = file("./textes/$file");
                    $nom_region = explode(".", $file);
                    $nom_region = $nom_region[0];
                    
                    foreach ($fichier as $key => $val) //Parcourt de chaque ligne de notre fichier $fichier
                    {     
                        if (strpos(strtoupper($val),"CODE=") !== false) //Si la ligne possede la balise CODE alors on recupere la ligne bien formater
                        {
                            $code = strtoupper($val);
                            $val = rtrim($val, "\n");  
                            $val = explode("=", $val);
                            $codeEx = $val[1];
                            $codeEx = strtoupper($codeEx);
                            $codeEx = rtrim($codeEx, " "); 
                                                        
                            $date = date("d/m/Y"); 
                            $heure = date("H:i");
                            $annee = date("Y"); 

                            $mois = date("m"); 
                            $mois = (int)$mois; 
                            if ($mois >= 1 && $mois <= 4) 
                            {
                                $trimestre = "1"; 
                            }
                            elseif ($mois >= 5 && $mois <= 8)
                            {
                                $trimestre = "2"; 
                            }
                            else
                            {
                                $trimestre = "3"; 
                            }

                            foreach ($fconfig as $key => $donnee)
                            {
                                $donnee = rtrim($donnee, "\n");  
                                $donnee = explode(",", $donnee);

                                if ($donnee[0] === $codeEx)
                                {
                                    $code_region = $donnee[0]; 
                                    $nom_region = $donnee[1]; 
                                    $nombre_habitant = $donnee[2]; 
                                    $superficie = $donnee[3]; 
                                    $nb_departement = $donnee[4]; 
                                }
                            }

                            $fhtml=fopen("$dhtml/$nom_region.html", "w"); 

                            $compteur = 0;
                            $zoneTexte = 0;
                            $texte = "<div class='paragraphe'>";
                            $tableau = "<table>
                                            <caption>Resultat</caption>
                                            
                                            <tr>
                                                <th>Nom Produit</th>
                                                <th>Vente du trimestre</th>
                                                <th>Chiffre d'affaires du trimestre</th>
                                                <th>Ventes du même trimestre année précédente</th>
                                                <th>CA du même trimestre année précédente</th>
                                                <th>Evolution de CA</th>
                                            </tr>";
                            $arret = -1; 

                            foreach ($ftexte as $cle => $valeur) //Parcourt de chaque ligne de notre fichier $ftexte
                            {
                                if (strpos($valeur, "CODE=") !== false)
                                {
                                    if ($valeur !== $code)
                                    {
                                        $compteur = 0;
                                    }
                                }
                                
                                if ($compteur === 1)
                                {
                                    if (strpos(strtoupper($valeur),"TITRE=") !== false) //Si la ligne possede la balise TITRE alors on regarde si c'est un TITRE ou un SOUS_TITRE
                                    {
                                        $parts = explode("=", $valeur); //On separe le TITRE et sa valeur
                                        $idDoc = $parts[1]; //Valeur que possede le titre
                                        $idDoc = rtrim($idDoc, "\n");

                                        if (strpos(strtoupper($valeur),"SOUS_TITRE=") !== false) 
                                        {
                                            $texte = $texte . "<h4>$idDoc</h4>";
                                        }

                                        else 
                                        {
                                            $texte = $texte . "<h3>$idDoc</h3>";
                                        }
                                    }

                                    if (strpos(strtoupper($valeur),"FIN_") !== false) 
                                    {
                                        if (strpos(strtoupper($valeur),"_TEXTE") !== false)
                                        {
                                            $zoneTexte = 0;
                                            $texte = $texte . "</p>";
                                        }
                                    }
        
                                    if ($zoneTexte === 1) 
                                    {
                                        $texte = $texte . $valeur;
                                    }

                                    if (strpos(strtoupper($valeur),"BUT_") !== false) 
                                    {
                                        if (strpos(strtoupper($valeur),"_TEXTE") !== false)
                                        {
                                            $zoneTexte = 1;
                                            $texte = $texte . "<p>";
                                        }
                                    }

                                    if (strpos(strtoupper($valeur),"ZZZ") !== false)
                                    {     
                                        $indice = 0; 
                                        foreach ($fichier as $col => $value)
                                        {
                                            
                                            if (strpos(strtoupper($value),"MEILLEUR") !== false) //Permet de recuperer le meilleur producteur et de l'ecrire dans le fichier meilleur.dat
                                            {
                                                $producteur = explode(":",$value);
                                                $producteur = $producteur[1];
                                                $prod = "";
                                                $prod1 = 0;
                                                $prod2 = 0;
                                                $prod3 = 0;

                                                for ($i=0; $i<strlen($producteur); $i++) //Permet d'ecrire les 3 plus grand vendeurs dans meilleur.dat
                                                {
                                                    if ((int)$producteur[$i] > 0) //Pour ne pas recuperer les lettres qui sont devenus des 0 alors que les chiffres restent inchange sauf de type
                                                    {
                                                        $prod = $producteur[$i] . $producteur[$i+1];
                                                        $prod = (int)$prod;
                                                        $i = $i + 1;

                                                        if ($prod > $prod3)
                                                        {
                                                            if ($prod > $prod2)
                                                            {
                                                                if ($prod > $prod1) //Plus grande valeur
                                                                {
                                                                    $prod3 = $prod2;
                                                                    $prod2 = $prod1;
                                                                    $prod1 = $prod;
                                                                }

                                                                else //Seconde valeur
                                                                {
                                                                    $prod3 = $prod2;
                                                                    $prod2 = $prod;
                                                                }
                                                            }

                                                            else
                                                            {
                                                                $prod3 = $prod; //Troisieme valeur
                                                            }
                                                        }
                                                    }
                                                }

                                                $producteur = explode(",", $producteur); //Separation des differents producteurs

                                                for ($i=0; $i<count($producteur); $i++) //Parcourt de la ligne pour trouver la plus grande valeur soit $prod1 et l'ecrire dans meilleur.dat
                                                {
                                                    if (strpos($producteur[$i], $prod1))
                                                    {
                                                        $prod1 = $producteur[$i];
                                                        //fwrite($fmeilleur,$producteur[$i] . ", ");
                                                    }
                                                }

                                                for ($i=0; $i<count($producteur); $i++) //Parcourt de la ligne pour trouver la seconde plus grande valeur soit $prod1 et l'ecrire dans meilleur.dat
                                                {
                                                    if (strpos($producteur[$i], $prod2))
                                                    {
                                                        $prod2 = $producteur[$i];
                                                        //fwrite($fmeilleur,$producteur[$i] . ", ");
                                                    }
                                                }

                                                for ($i=0; $i<count($producteur); $i++) //Parcourt de la ligne pour trouver la troisieme plus grande valeur soit $prod1 et l'ecrire dans meilleur.dat
                                                {
                                                    if (strpos($producteur[$i], $prod3))
                                                    {
                                                        $prod3 = $producteur[$i];
                                                        //fwrite($fmeilleur,$producteur[$i] . "\n");
                                                    }
                                                }
                                            }

                                            if (strpos(strtoupper($value),"PROD #") !== false)
                                            {
                                                $tableau = $tableau . "<tr>\n";
                            
                                                $prod = explode(",", $value);
                                                $cat = 0;
                                                $catp = 0;

                                                for ($i=0; $i<6; $i++)
                                                {
                                                    if ($i === 2)
                                                    {
                                                        $cat = $prod[$i];
                                                    }
                                
                                                    else if ($i === 4)
                                                    {
                                                        $catp = $prod[$i];
                                                    }

                                                    if ($i === 5)
                                                    {
                                                        $cat = $cat - $catp;
                                                        $tableau = $tableau . "<td id='Calcul$indice' class='negatif$indice'>$cat</td>\n";
                                                    }
                                                    else
                                                    {
                                                        $element = $prod[$i];
                                                        $tableau = $tableau . "<td>$element</td>\n";
                                                    }
                                                }

                                                $tableau = $tableau . "</tr>";
                                                $indice = $indice + 1;
                                            }
                                        }
                                        $tableau = $tableau . "</table>";
                                    }
                                }
                                
                                if ($code === $valeur)
                                {
                                    $compteur = 1;
                                }
                            }

                            $texte = $texte . "</div>";

                            $prod1 = explode("/", $prod1);
                            $image_1 = strtolower($prod1[0]);
                            $part = explode("=", $prod1[1]);
                            $vend_1 = $part[0]; 
                            $ca_vend_1 = $part[1]; 

                            $prod2 = explode("/", $prod2);
                            $image_2 = strtolower($prod2[0]);
                            $part = explode("=", $prod2[1]);
                            $vend_2 = $part[0]; 
                            $ca_vend_2 = $part[1]; 

                            $prod3 = explode("/", $prod3);
                            $image_3 = strtolower($prod3[0]);
                            $part = explode("=", $prod3[1]);
                            $vend_3 = $part[0]; 
                            $ca_vend_3 = $part[1]; 





                            $code_reg_low = strtolower($code_region); 


                            fwrite($fhtml, "<!DOCTYPE html>\n");
                            fwrite($fhtml, "<html lang='fr'>\n"); 
                            fwrite($fhtml, "<head>\n"); 
                                fwrite($fhtml, "<title>$nom_region</title>\n
                                    <meta charset='utf-8' >\n
                                    <meta http-equiv='X-UA-Compatible' content='IE=edge' >\n
                                    <meta name='description' content='$nom_region'>\n
                                    <meta name='keywords' content='$nom_region' >\n
                                    <link rel='stylesheet' type='text/css' href='style.css' >\n
                                    <script src='javascript.js' defer></script>\n"); 
                            fwrite($fhtml, "</head>\n\n"); 

                            /* ----- Body ----- */

                            fwrite($fhtml, "<body>\n"); 
                                /* ----- Couverture ----- */
                                fwrite($fhtml, "<section class='PageCouverture'>\n
                                    <!-- page de couverture -->\n
                                    <h4>Code de la région : <span>$code_region</span></h4>\n
                                    <h1>Nom de la région : <strong>$nom_region</strong></h1>\n
                                    <ul>\n
                                        <li>Nombre d'habitant : $nombre_habitant</li>\n
                                        <li>Superficie : $superficie</li>\n
                                        <li>Nombre de départements : $nb_departement</li>\n
                                    </ul>\n
                                    <img src='./../logos/$code_region.png'>\n\n

                                    <div class='bottom'>\n
                                        <p>$date</p>\n
                                        <p>$heure</p>\n
                                        <p>Couverture</p>\n
                                    </div>\n"); 
                                fwrite($fhtml, "</section>"); 

                                /* ----- Page 1 ----- */

                                fwrite($fhtml, "<section class='Page1'> \n
                                    <!-- page 1 -->\n
                                    <h2>Résultats trimestriels</h2>\n
                                    <h4>$trimestre trimestre - $annee</h4>\n\n

                                    $texte\n
                                    <article>\n
                                        $tableau\n
                                    </article>\n\n
                                    
                                    <div class='bottom'>\n
                                        <p>$date</p>\n
                                        <p>$heure</p>\n
                                    <p>Page 1</p>\n
                                    </div>\n");  
                                fwrite($fhtml, "</section>\n\n");

                                /* ----- Page 2 ----- */
                                fwrite($fhtml, "<section class='Page2'>\n 
                                    <!-- page 2 -->\n
                                    <h2>Meilleurs vendeurs du trimestre</h2>\n
                                    <ul>\n
                                        <div>\n
                                            <img src='./../photos/photos_modif/$image_1.png'>\n 
                                            <li>\n
                                                <h3>Meilleur.e vendeur ou vendeuse :</h3>\n
                                                <p>Nom : $vend_1</p>\n
                                                <p>CA : $ca_vend_1</p>\n
                                            </li>\n
                                        </div>\n
                                        <div>\n
                                            <img src='./../photos/photos_modif/$image_2.png'>\n
                                            <li>\n
                                                <h3>Second.e meilleur.e vendeur ou vendeuse :</h3>\n
                                                <p>Nom : $vend_2</p>\n
                                                <p>CA : $ca_vend_2</p>\n
                                            </li>\n
                                        </div>\n
                                        <div>\n
                                            <img src='./../photos/photos_modif/$image_3.png'>\n 
                                            <li>\n
                                                <h3>Troisième meilleur.e vendeur ou vendeuse :</h3>\n
                                                <p>Nom : $vend_3</p>\n
                                                <p>CA : $ca_vend_3</p>\n
                                            </li>\n
                                        </div>\n
                                    </ul>\n\n

                                    <div class='bottom'>\n
                                        <p>$date</p>\n
                                        <p>$heure</p>\n
                                    <p>Page 2</p>\n
                                    </div>\n"); 
                                fwrite($fhtml, "</section>");
                                
                                /* ----- Page 3 ----- */
                                fwrite($fhtml, "<section class='Page3'>\n
                                    <p><a href='https://bigbrain.biz/$code_region'>lien vers site société</a></p>\n
                                    <aside>\n
                                        <img src='./../qrcode_fic/qrcode_$code_reg_low.png'>\n
                                    </aside>\n
                                    
                                    <div class='bottom'>\n
                                        <p>$date</p>\n
                                        <p>$heure</p>\n
                                        <p>Page 3</p>\n
                                    </div>\n");
                                fwrite($fhtml, "</section>"); 
                            fwrite($fhtml, "</body>"); 
                            fwrite($fhtml, "</html>");
                        }
                    }
                }
            }
        }
    }
?>