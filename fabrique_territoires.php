<?php

/**
 *  Fichier généré par la Fabrique de plugin v7
 *   le 2020-07-02 13:44:33
 *
 *  Ce fichier de sauvegarde peut servir à recréer
 *  votre plugin avec le plugin «Fabrique» qui a servi à le créer.
 *
 *  Bien évidemment, les modifications apportées ultérieurement
 *  par vos soins dans le code de ce plugin généré
 *  NE SERONT PAS connues du plugin «Fabrique» et ne pourront pas
 *  être recréées par lui !
 *
 *  La «Fabrique» ne pourra que régénerer le code de base du plugin
 *  avec les informations dont il dispose.
 *
**/

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

$data = array (
  'fabrique' => 
  array (
    'version' => 7,
  ),
  'paquet' => 
  array (
    'prefixe' => 'territoires',
    'nom' => 'Territoires',
    'slogan' => 'La hiérarchie des zones géographiques du continent à la subdivision d\'un pays',
    'description' => '',
    'logo' => 
    array (
      0 => '',
    ),
    'credits' => 
    array (
      'logo' => 
      array (
        'texte' => '',
        'url' => '',
      ),
    ),
    'version' => '1.0.0',
    'auteur' => 'Eric Lupinacci',
    'auteur_lien' => 'https://blog.smellup.net',
    'licence' => 'GNU/GPL',
    'categorie' => 'divers',
    'etat' => 'dev',
    'compatibilite' => '[3.3.0-dev;3.3.*]',
    'documentation' => '',
    'administrations' => 'on',
    'schema' => '1',
    'formulaire_config' => 'on',
    'formulaire_config_titre' => 'Configuration du plugin',
    'fichiers' => 
    array (
      0 => 'autorisations',
      1 => 'pipelines',
    ),
    'inserer' => 
    array (
      'paquet' => '',
      'administrations' => 
      array (
        'maj' => '',
        'desinstallation' => '',
        'fin' => '',
      ),
      'base' => 
      array (
        'tables' => 
        array (
          'fin' => '',
        ),
      ),
    ),
    'scripts' => 
    array (
      'pre_copie' => '',
      'post_creation' => '',
    ),
    'exemples' => '',
  ),
  'objets' => 
  array (
    0 => 
    array (
      'nom' => 'Territoires',
      'nom_singulier' => 'Territoire',
      'genre' => 'masculin',
      'logo' => 
      array (
        0 => '',
        32 => '',
        24 => '',
        16 => '',
        12 => '',
      ),
      'table' => 'spip_territoires',
      'cle_primaire' => 'id_territoire',
      'cle_primaire_sql' => 'bigint(21) NOT NULL',
      'table_type' => 'territoire',
      'champs' => 
      array (
        0 => 
        array (
          'nom' => 'Code ISO',
          'champ' => 'iso_territoire',
          'sql' => 'varchar(10) NOT NULL DEFAULT \'\'',
          'recherche' => '10',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        1 => 
        array (
          'nom' => 'Nom ISO',
          'champ' => 'iso_titre',
          'sql' => 'text NOT NULL DEFAULT \'\'',
          'recherche' => '5',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        2 => 
        array (
          'nom' => 'Nom d\'usage',
          'champ' => 'nom_usage',
          'sql' => 'text NOT NULL DEFAULT \'\'',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
          ),
          'recherche' => '5',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        3 => 
        array (
          'nom' => 'Descriptif',
          'champ' => 'descriptif',
          'sql' => 'text NOT NULL DEFAULT \'\'',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
          ),
          'recherche' => '2',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        4 => 
        array (
          'nom' => 'Type de territoire',
          'champ' => 'type',
          'sql' => 'varchar(12) DEFAULT \'\' NOT NULL',
          'caracteristiques' => 
          array (
            0 => 'editable',
            1 => 'versionne',
          ),
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        5 => 
        array (
          'nom' => 'Catégorie',
          'champ' => 'categorie',
          'sql' => 'varchar(64) NOT NULL DEFAULT \'\'',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        6 => 
        array (
          'nom' => 'Continent',
          'champ' => 'iso_continent',
          'sql' => 'varchar(3) DEFAULT \'\' NOT NULL',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        7 => 
        array (
          'nom' => 'Pays',
          'champ' => 'iso_pays',
          'sql' => 'varchar(2) NOT NULL DEFAULT \'\'',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        8 => 
        array (
          'nom' => 'Territoire de rattachement',
          'champ' => 'iso_parent',
          'sql' => 'varchar(10) NOT NULL DEFAULT \'\'',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
        9 => 
        array (
          'nom' => 'Edité ?',
          'champ' => 'edite',
          'sql' => 'varchar(3) NOT NULL DEFAULT \'\'',
          'recherche' => '',
          'saisie' => '',
          'explication' => '',
          'saisie_options' => '',
        ),
      ),
      'champ_titre' => 'nom_usage',
      'champ_date' => 'date',
      'champ_date_ignore' => '',
      'statut' => '',
      'chaines' => 
      array (
        'titre_objets' => 'Territoires',
        'titre_page_objets' => 'Les territoires',
        'titre_objet' => 'Territoire',
        'info_aucun_objet' => 'Aucun territoire',
        'info_1_objet' => 'Un territoire',
        'info_nb_objets' => '@nb@ territoires',
        'icone_creer_objet' => 'Créer un territoire',
        'icone_modifier_objet' => 'Modifier ce territoire',
        'titre_logo_objet' => 'Logo de ce territoire',
        'titre_langue_objet' => 'Langue de ce territoire',
        'texte_definir_comme_traduction_objet' => 'Ce territoire est une traduction du territoire numéro :',
        'titre_\\objets_lies_objet' => 'Liés à ce territoire',
        'titre_objets_rubrique' => 'Territoires de la rubrique',
        'info_objets_auteur' => 'Les territoires de cet auteur',
        'retirer_lien_objet' => 'Retirer ce territoire',
        'retirer_tous_liens_objets' => 'Retirer tous les territoires',
        'ajouter_lien_objet' => 'Ajouter ce territoire',
        'texte_ajouter_objet' => 'Ajouter un territoire',
        'texte_creer_associer_objet' => 'Créer et associer un territoire',
        'texte_changer_statut_objet' => 'Ce territoire est :',
        'supprimer_objet' => 'Supprimer cet territoire',
        'confirmer_supprimer_objet' => 'Confirmez-vous la suppression de cet territoire ?',
      ),
      'liaison_directe' => '',
      'table_liens' => 'on',
      'vue_liens' => 
      array (
        0 => 'spip_articles',
      ),
      'afficher_liens' => 'on',
      'roles' => '',
      'auteurs_liens' => '',
      'vue_auteurs_liens' => '',
      'saisies' => 
      array (
        0 => 'objets',
      ),
      'autorisations' => 
      array (
        'objets_voir' => '',
        'objet_creer' => '',
        'objet_voir' => '',
        'objet_modifier' => '',
        'objet_supprimer' => '',
        'associerobjet' => '',
      ),
      'boutons' => 
      array (
        0 => 'menu_edition',
      ),
    ),
  ),
  'images' => 
  array (
    'paquet' => 
    array (
      'logo' => 
      array (
        0 => 
        array (
          'extension' => 'png',
          'contenu' => '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',
        ),
      ),
    ),
    'objets' => 
    array (
      0 => 
      array (
        'logo' => 
        array (
          32 => 
          array (
            'extension' => 'png',
            'contenu' => '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',
          ),
          24 => 
          array (
            'extension' => 'png',
            'contenu' => 'iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAHhlWElmTU0AKgAAAAgABAEaAAUAAAABAAAAPgEbAAUAAAABAAAARgEoAAMAAAABAAIAAIdpAAQAAAABAAAATgAAAAAAAABFAAAAAQAAAEUAAAABAAOgAQADAAAAAQABAACgAgAEAAAAAQAAABigAwAEAAAAAQAAABgAAAAAWh3XEQAAAAlwSFlzAAAKnQAACp0BJpU99gAAA/5JREFUSA2dVm9MW1UU//HalbJuQGFO/gkrkdE61GgWWXHN5rbMBEPIFpF9cZ0Zmc6ZRW3MIp/ggwYSP5hosg+KMZh1sy4bLHHLJootiV2IjC3SRIGt0D1XKa5vZau0QPs895XXvLaIwEl+95x77u+c88577973gOVlGy1/tI7jTpPWLE9d3WoW0U8drTTP/7LPJh7e8oJI8+LVpUiw1YqgN7Sqdbsjsfm7L23eetxqqC2oLdyCz8ec6J4YVNBWZyoLTHxo2n+4bpMBZev1UpYe/hZOjw3IGTeS4ZcnK9UqBXEiMPvogKWoquhG4RweBoI4/us5sHuzKBbS5whR2bEW/cHlnksiE6fTyXKnw0e+CwTzWpKjrKzsvJSdhvr6+vTkynmcCnQRniU8s+JiRqPxB1YgGAyKWq1WmTDDLtLmirbqvRIKNboz/1VE+ZDh9/tnBEFAIBBAJBJZMiabU4NeW7z1pAXr1YmtIcyFD33lvX6CAh6kB3GLjg2kT7W3tx/U6/WoqqqCwWBI50rzq7tP4H3j3mRy5txeUMHyvCIR0ga5wJ7W1tYOi4W9KADHcejq6oJKpXzJEpFnJ4cShmLkZ6UL5xWupClnCI2MjOyw2WzleXl50iLrpLKyEn19faipqcGxhtfQojGiQleAkpwER84yH4/hIn+L5TIR9hAeEqQ9w44EWUqpg9sulyubOaLRKDwej3SrNBoNvvvsS+wYuC9zU3RMjMMXFqCmzi/f8+DT0f5dRHAxknyLmH2go6NDSh6LxeD8qR/xeBysE51Oh1ffOYqxuSDjZYgqi4NhQyGeoBOgJq8EpTn5HxOpnBHlW8Tsv7IiCyen7f3cyJkrMF2fxqU7w/AJAZhMJjiv9aHixn1wWcqmWViqlOv0MG8ylJ/1Df1OK0PKDnyeq67RFyP5qI0XSInyY2o0NzeDOoM/+DduR5fuILUEwJ4JiXTxygK4KfDnf3twL8mvHg3je8cFFBcXw2q1gq8rRVxke255GQxOMsIAG9L7zdm17fmZQ/lPqR+VbERD+0lUm4zSpuvu7kZLSwvs9W9ju2ozi8U33sH4z9Njd/yzIe9UZOYu7ejH9hUZX/b9Iyz8OPUH21sZV1Pa2dkpH0dJ7XA4RNrZ0vyTI++KPTuPiXbzETGbU30hVUodzPRd+VZ2pRwV5PzT7XZPkX5cJjAdDofh9XoRCoXw3OsN0ptFRRF1x75W8hZtN3203Ev4k643h4eHk1fPjLa2tozDjthzhMSXKRmaaaQ85MVlR29vLzuOwfM8mpqaMD4+nhkJOMgpLLXwv77GxsZJu90u5ubm9hD5aUId4T3CAmEnYT9hTT8BFCeJlcaDhPQODYnllY//AjlMjpM+MnUaAAAAAElFTkSuQmCC',
          ),
          16 => 
          array (
            'extension' => 'png',
            'contenu' => 'iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAAXNSR0IArs4c6QAAAHhlWElmTU0AKgAAAAgABAEaAAUAAAABAAAAPgEbAAUAAAABAAAARgEoAAMAAAABAAIAAIdpAAQAAAABAAAATgAAAAAAAABJAAAAAQAAAEkAAAABAAOgAQADAAAAAQABAACgAgAEAAAAAQAAABCgAwAEAAAAAQAAABAAAAAATKD9sAAAAAlwSFlzAAALOgAACzoBZH9XDQAAAjtJREFUOBFjYEADguxcvuFyxme5mJm90KSwclmgolaibLy2FiLyPslKVjb7Xt1i+Pb3rzxWHTgEleeYRv3b7pD9v0XX5z8jA8N/oLp5ONRiF44JCL3548eP/9am5iDNMLwbyI4DYqCZBEB/f//nW7duwTSi0Mo8ohedxNW2Ao2QRjeGCSogDKQZnz17hiIvwMrJUKbhwrDeJlVvqnG4ly6fZDGKAiAHbIC7u/uxsLAwbmtrawZDQ0O4GjEOXoZoBTMGViZmsBg3K7sCXBLKAMv8+vWLw9fX101aWpqBj4+PgZeXlyHOL5gh/pc8Aw8LOwMzE8ShL398lpTi5A948f0z649/v8+AzABHI9DpNwUEBBhevXrF8PvbD4b58+czPHn8mOHZySkMbMywmGZgSFexEQDqscg8vZzlwOs7S4Hsz2AX/P///w332cdl4puvMz67fofh6r/3DH9+/WbgOnoP7nyQbTCgLygjtfjBqW1A/iNYIL67dv/uRX5goIl9Z2YQ5OBm4ObjZbioiLD98+8fDLc/v2J4+eMTw41PL0CxdB1mIJi2sLCYuGXu0v9v37wFOuj//w0bNvw/evDQ/9OuZf/rtL1+AhUFAjEohJ2VeEQOgzUBCbgVDx48OGrq45InJCzE8PPnT4aHDx8y3Lx5k+GHDTfDxnVP9wHVrodpuvflzV4YG5lmnDJlyucvX778nzt37n9gbCAnphhkhTjZwPSwxc7ObglQgTgQmwJxPRArAjEsrIBMKgMAvCjICKREtKIAAAAASUVORK5CYII=',
          ),
          12 => 
          array (
            'extension' => 'png',
            'contenu' => 'iVBORw0KGgoAAAANSUhEUgAAAAwAAAAMCAYAAABWdVznAAAAAXNSR0IArs4c6QAAAHhlWElmTU0AKgAAAAgABAEaAAUAAAABAAAAPgEbAAUAAAABAAAARgEoAAMAAAABAAIAAIdpAAQAAAABAAAATgAAAAAAAABFAAAAAQAAAEUAAAABAAOgAQADAAAAAQABAACgAgAEAAAAAQAAAAygAwAEAAAAAQAAAAwAAAAAqyRY4QAAAAlwSFlzAAAKnQAACp0BJpU99gAAAPJJREFUKBVt0D1qAkEYxnHxIyoiGMEPTOcKYuz86LSxEcU7aCGxs9M7eBQPoQdI4QUkiGATkkAghUHU5P+sO4uLPvBj35md2XdmfT5v2gzHiHinb0cjpnooYoME7ibgzCZ5vmGCFkrYYoe76TIbxAf+rsyo1dkTdRkgjj3MhhdqP+rIwY4mMtCiH0zxjTleccYvGniGm75TWTxDyLpvLsUjD/2YsLl0noG+HEYaD/iCibq8wzIbDgzK+MQJOsoRKTRRgO6yhB19Ub9TeYIW1jCESUWFLq2oQxQ6UgcxqJM6mqxUmCOp1tmrWEDHWEOLtNHNPydHK4UTvhrcAAAAAElFTkSuQmCC',
          ),
        ),
      ),
    ),
  ),
);
