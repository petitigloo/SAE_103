<?php
    $doss = "./"; //Repertoire qui va etre explorer
    
    if (file_exists("./rendu") !== true)
    {
        mkdir("./rendu");
    }

    //Fichier qui seront creer pour ecrire le contenu bien formate
    $ftexte = fopen("./rendu/texte.dat", "w");
    $ftableau = fopen("./rendu/tableau.dat", "w");
    $furl = fopen("./rendu/url.dat", "w");
    $fmeilleur = fopen("./rendu/meilleur.dat", "w");

    if (is_dir($doss)) //si $doss est un dossier
    {
        if ($dossier = opendir($doss)) //On ouvre le dossier $doss
        {
            while (($file = readdir($dossier)) !== false) //Tant qu'il y a des document dans $doss
            {                
                if (strpos($file,".txt") !== false) //Si le fichier est en .txt
                {
                    //Ecrire le tableau en HTML
                    fwrite($ftableau, "<table>\n");
                    fwrite($ftableau, "<caption>\n");
                    fwrite($ftableau, "<tbody>\n");

                    fwrite($ftableau, "<tr>\n");
                    fwrite($ftableau, "<th>Nom Produit</th>\n");
                    fwrite($ftableau, "<th>Vente du trimestre</th>\n");
                    fwrite($ftableau, "<th>Chiffre d'affaires du trimestre</th>\n");
                    fwrite($ftableau, "<th>Ventes du même trimestre année précédente</th>\n");
                    fwrite($ftableau, "<th>CA du même trimestre année précédente</th>\n");
                    fwrite($ftableau, "<th>Evolution de CA</th>\n");
                    fwrite($ftableau, "</tr>\n");
                    fwrite($ftableau, "\n");
                    
                    $lines = file($file); //Fichier qui va etre lu pour recuperer les informations
                    $zone_txt = 0;
                    $arret = 0;

                    //Suppression des caractères non utiles et genant pour le formatage du fichier
                    $lines = str_replace("   ","",$lines);
                    $lines = str_replace("  "," ",$lines);
                    
                    foreach ($lines as $key => $val) //Parcourt de chaque ligne de notre fichier $lines
                    {
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        if (strpos(strtoupper($val),"MEILLEURS:") !== false) //Permet de recuperer le meilleur producteur et de l'ecrire dans le fichier meilleur.dat
                        {
                            $val = rtrim($val, "\n");
                            $producteur = explode(":",$val);
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
                                    $i = $i + 2;
                                    
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
                                    fwrite($fmeilleur,$producteur[$i] . ", ");
                                }
                            }

                            for ($i=0; $i<count($producteur); $i++) //Parcourt de la ligne pour trouver la seconde plus grande valeur soit $prod1 et l'ecrire dans meilleur.dat
                            {
                                if (strpos($producteur[$i], $prod2))
                                {
                                    fwrite($fmeilleur,$producteur[$i] . ", ");
                                }
                            }

                            for ($i=0; $i<count($producteur); $i++) //Parcourt de la ligne pour trouver la troisieme plus grande valeur soit $prod1 et l'ecrire dans meilleur.dat
                            {
                                if (strpos($producteur[$i], $prod3))
                                {
                                    fwrite($fmeilleur,$producteur[$i] . "\n");
                                }
                            }
                        }
























                        if (strpos(strtoupper($val),"CODE=") !== false) //Si la ligne possede la balise CODE alors on ecrit le code dans texte.dat
                        {
                            $parts = explode("=", $val);
                            $idDoc = strtoupper($parts[1]);
            
                            fwrite($ftexte,"CODE=$idDoc");
                        }
                        
                        if (strpos(strtoupper($val),"FIN_") !== false) //Si il y a marque FIN_TEXTE alors on arrete d'ecrire du texte et on ecrit fin texte dans texte.dat
                        {
                            if (strpos(strtoupper($val),"_TEXTE") !== false)
                            {
                                $zone_txt = 0;
                                fwrite($ftexte,"FIN_TEXTE\n");
                            }
                        }
        
                        if ($zone_txt === 1) //Si la ligne est du texte alors on ecrit dans texte.dat
                        {
                            fwrite($ftexte,"$val");
                        }

                        if (strpos(strtoupper($val),"TITRE=") !== false) //Si la ligne possede la balise TITRE alors on regarde si c'est un TITRE ou un SOUS_TITRE
                        {
                            $parts = explode("=", $val);
                            $idDoc = $parts[1];

                            if (strpos(strtoupper($val),"SOUS_TITRE=") !== false) //Si la ligne possede la balise SOUS_TITRE alors on ecrit le sous_titre dans le fichier texte.dat
                            {
                                fwrite($ftexte,"SOUS_TITRE=$idDoc");
                            }

                            else //Si la ligne possede juste la balise TITRE alors on ecrit le titre dans le fichier texte.dat
                            {
                                fwrite($ftexte,"TITRE=$idDoc");
                            }
                        }

                        if (strpos(strtoupper($val),"BUT_") !== false) //Si la ligne possede la balise DEBUT_TEXTE alors on commence a ecrire le texte et on ecrit DEBUT_TEXTE dans le fichier texte.dat
                        {
                            if (strpos(strtoupper($val),"_TEXTE") !== false)
                            {
                                $zone_txt = 1;
                                fwrite($ftexte,"DEBUT_TEXTE\n");
                            }
                        }

                        if (strpos(strtoupper($val), "HTTP") !== false) //Si la ligne possede la balise HTTP alors on ecrit le http lier a une balise html dans url.dat
                        {
                            $url = "";
                            $compteur_parenthese = 0;

                            for ($i=0; $i<strlen($val)-1; $i++) //On parcourt la ligne contenant le HTTP et on ajoute le lien dans $url
                            {
                                if ($compteur_parenthese > 0) //Si on est dans une "(" alors on ecrit le contenu souhaite de $val[$i] dans $url
                                {
                                    $url = $url . $val[$i];
                                }
                                
                                if (strpos($val[$i+1], ")") !== false) //Si $val est suivi d'une ")" et que $compteur_parenthese est egal a 0 alors on arrete d'ecrire pour le prochaine $val[$i]
                                {
                                    $compteur_parenthese = $compteur_parenthese - 1;
                                }

                                if ((strpos($val[$i], "(") !== false) && (strpos($val[$i+1], "h") !== false)) //Si $val[$i] est une "(" et $val[$i+1] est egal a h(de http) alors on commence a regarder les prochaines valeur pour modifier $url
                                {
                                    $compteur_parenthese = $compteur_parenthese + 1;
                                }

                                else if ((strpos($val[$i], "(") !== false) && ($compteur_parenthese != 0)) //Sinon si $val[$i] est une "(" et $compteur_parenthese est different de 0 alors on commence a regarder les prochaines valeur pour modifier $url
                                {
                                    $compteur_parenthese = $compteur_parenthese + 1;
                                }
                            }

                            if ($url !== "") //Si $url n'est pas vide alors elle contient un url donc on ecrit dans le fichier url.dat
                            {
                                $url = "<a href=$url>$url</a>"; //Creation de la balise HTML
                                fwrite($furl, $url);
                                fwrite($furl, "\n");
                            }
                        }
                        
                        if (strpos(strtoupper($val),"PROD #") !== false) //Si il y a un Prod dans dans la ligne
                        {
                            if ($arret === 0)
                            {
                                fwrite($ftexte, "ZZZ\n");
                                $arret = 1;
                            }
                            fwrite($ftableau, "<tr>\n"); //Ecrirer la balise <tr> dans le fichier $ftableau
                            
                            $prod = explode(",", $val); //On recupere chaque elements de prod
                            $cat = 0;
                            $catp = 0;

                            for ($i=0; $i<6; $i++) //Parcourt de chaque element de $prod
                            {
                                if ($i === 2) //Recupere la valeur du CA de ce trimestre
                                {
                                    $cat = $prod[$i];
                                }
                                
                                else if ($i === 4) //Recupere la valeur du CA de ce precedent trimestre
                                {
                                    $catp = $prod[$i];
                                }

                                if ($i === 5) //Pour la derniere partie qui demande la difference entre 2 trimestres
                                {
                                    $cat = $cat - $catp;
                                    $indice = $i - 1;
                                    fwrite($ftableau, "<th id=Calcul$indice class=negatif$indice>$cat</th\n");
                                }
                                else //On ecrit $prod[$i] dans $ftableau
                                {
                                    $element = $prod[$i];
                                    fwrite($ftableau, "<th>$element</th>\n");
                                }
                            }


                            fwrite($ftableau, "</tr>\n");
                            fwrite($ftableau, "\n");
                        }
                    }
                    
                    //Ferme les balises HTML ouvertes au debut du script dans $ftableau
                    fwrite($ftableau, "</tbody>\n");
                    fwrite($ftableau, "</caption>\n");
                    fwrite($ftableau, "</table>\n");
                    fwrite($ftableau, "\n");
                }
            }
            closedir($dossier); //Fermeture du repertoire que nous avons utiliser 
        }
    }
    //On ferme les differents fichiers ouvert
    fclose($ftexte);
    fclose($ftableau);
    fclose($furl);
    fclose($fmeilleur);
?>