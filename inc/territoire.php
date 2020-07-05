<?php
/**
 * Gestion du formulaire de chargement ou de vidage des territoires.
 *
 * @package    SPIP\TERRITOIRES\API
 */
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

$GLOBALS['territoires']['zone'] = array(
	'champs' => array(
		'base' => array(
			'code_num' => 'iso_territoire',
			'category' => 'categorie',
			'parent'   => 'iso_parent',
			'label'    => 'iso_titre',
		),
	),
	'index' => 'zones',
);

$GLOBALS['territoires']['country'] = array(
	'champs' => array(
		'base' => array(
			'code_alpha2'     => 'iso_territoire',
			'category'        => 'categorie',
			'code_continent'  => 'iso_continent',
			'label'           => 'iso_titre',
		),
		'addon' => array(
			'code_num_region' => 'iso_parent',
			'code_alpha3'     => '',
			'code_num'        => '',
			'capital'         => '',
			'area'            => '',
			'population'      => '',
			'tld'             => '',
			'code_4217_3'     => '',
			'currency_en'     => '',
			'phone_id'        => ''
		),
	),
	'index' => 'pays',
);

$GLOBALS['territoires']['subdivision'] = array(
	'champs' => array(
		'base' => array(
			'code_3166_2' => 'iso_territoire',
			'type'        => 'categorie',
			'country'     => 'iso_pays',
			'parent'      => 'iso_parent',
			'label'       => 'iso_titre',
		)
	),
	'index' => 'subdivisions',
);

/**
 * Peuple soit les régions du monde, soit les pays ou soit les subdivisions d'un pays.
 * La fonction utilise les données fournies par Nomenclatures.
 *
 * @api
 *
 * @param string $type Type de territoires à peupler. Prends les valeurs `zone`, `country` ou `subdivision`.
 * @param string $pays Code ISO 3166-1 alpha2 du pays dont on veut peupler les subdivisions.
 *                     N'est utilisé que si le type choisi est `subdivision`.
 *
 * @return array
 */
function territoire_peupler($type, $pays = '') {

	// On initialise le retour à une erreur nok
	$retour = array(
		'ok'   => false,
		'arg'  => false,
		'sha'  => false,
		'type' => $type,
		'pays' => $pays
	);

	// Le peuplement dépend du type :
	// - type = zone ou country : on charge l'ensemble des régions du monde ou l'ensemble des pays
	// - type = subdivision : il faut préciser le pays (code ISO alpha2) pour lequel on charge toutes les subdivisions
	if (type_pays_est_valide($type, $pays)) {
		$erreur = true;
		$type_identique = false;

		// On récupère tous les index fournis la collection et pas uniquement celui des territoires du type.
		// Ainsi, les autres index éventuels restent disponibles.
		$collection = territoire_acquerir($type, $pays);
		if (!empty($collection[$GLOBALS['territoires'][$type]['index']])) {
			// On extrait que l'index correspondant au type demandé
			$territoires = $collection[$GLOBALS['territoires'][$type]['index']];

			// Si le type de territoire est déjà chargé il possède un sha. Si le sha des data récupérées de Nomenclatures
			// possèdent le même sha alors on ne fait aucun traitement et on indique que la configuration n'a pas
			// changée. Dans ce cas, aucun traitement n'a lieu.
			$sha_type = sha1(json_encode($territoires));
			if (sha_est_identique($sha_type, $type, $pays)) {
				$type_identique = true;
			} else {
				// Le sha a changé : il est donc licite de recharger les territoires.
				// -- on préserve les éditions manuelles et les liens pour les réinjecter ensuite lors du
				//    rechargement
				$sauvegardes = territoire_preserver($type, $pays);

				// -- on vide les territoires avant de les remettre (inutile de gérer les erreurs
				//    car l'insertion les détectera).
				territoire_depeupler($type, $pays);

				// -- on insère chaque territoire comme un objet
				include_spip('action/editer_objet');
				$erreur_insertion = false;
				$ids = array();
				foreach ($territoires as $_territoire) {
					// On initialise le territoire avec les noms de champs de la table spip_territoires
					$territoire = enregistrement_initialiser($_territoire, $type, $pays);

					// Si le code iso_continent est rempli c'est qu'on vient de charger les pays. Comme le code
					// du continent est le code alpha2 on le remplace par le code M49 pour être homogène.
					// -- la collection pays contient pour cela un bloc 'continents'.
					if (!empty($territoire['iso_continent'])) {
						$territoire['iso_continent'] = $collection['continents'][$territoire['iso_continent']]['code_num'];
					}

					// Intégrer les éventuelles modifications sauvegardées
					if (isset($sauvegardes['editions'][$territoire['iso_territoire']])) {
						// -- descriptif en fusionnant les traductions (priorité aux sauvegardes)
						// -- remise à 'oui' de l'indicateur d'édition
						$territoire['descriptif'] = traduction_fusionner(
							$sauvegardes['editions'][$territoire['iso_territoire']]['nom_usage'],
							$territoire['descriptif']
						);
						$territoire['edite'] = 'oui';
					}

					if ($id = objet_inserer('territoire', null, $territoire)) {
						// On consigne le couple (iso, id) pour rétablir les liens si besoin
						$ids[$territoire['iso_territoire']] = $id;
					} else {
						$erreur_insertion = true;
						break;
					}
				}

				if (!$erreur_insertion) {
					// On rétablit les liens vers les territoires et les logos
					// -- les liens avec les autres objets
					lien_retablir('liens', $sauvegardes);

					// -- les liens avec les logos
					lien_retablir('logos', $sauvegardes);

					// On stocke les informations de chargement du pays dans une meta.
					$meta = array(
						'sha' => $sha_type,
						'nbr' => count($territoires),
						'maj' => date('Y-m-d H:i:s')
					);
					peuplement_consigner($meta, $type, $pays);
					$erreur = false;
				}
				spip_log("Les territoires (Type '${type}' - Pays '${pays}') ont été chargées", 'territoires' . _LOG_DEBUG);
			}
		} else {
			spip_log("Aucun territoire pour (Type '${type}' - Pays '${pays}') retourné par Nomenclatures", 'territoires' . _LOG_ERREUR);
		}

		// Si le territoire est en erreur, on passe on stocke le cas d'erreur.
		if ($erreur) {
			if ($type_identique) {
				$retour['sha'] = true;
				spip_log("Les territoires de (Type '${type}' - Pays '${pays}') sont inchangés", 'territoires' . _LOG_DEBUG);
			} else {
				$retour['ok'] = false;
			}
		} else {
			$retour['ok'] = true;
		}
	} else {
		$retour['arg'] = true;
		spip_log("Le couple (Type '${type}' - Pays '${pays}') est invalide", 'territoires' . _LOG_ERREUR);
	}

	return $retour;
}

/**
 * Supprime de la base soit les régions du monde, soit les pays ou soit les subdivisions d'un pays.
 *
 * @api
 *
 * @param string $type Type de territoires à peupler. Prends les valeurs `zone`, `country` ou `subdivision`.
 * @param string $pays Code ISO 3166-1 alpha2 du pays dont on veut peupler les subdivisions.
 *                     N'est utilisé que si le type choisi est `subdivision`.
 *
 * @return array Liste des code ISO 3166-1 alpha2 des pays chargés sous la forme [code] = nom multi.
 */
function territoire_depeupler($type, $pays = '') {

	// On initialise le retour à une erreur nok
	$retour = array(
		'ok'   => false,
		'arg'  => false,
		'sha'  => false,
		'type' => $type,
		'pays' => $pays
	);

	// Le vidage dépend du type :
	// - type = zone ou country : on vide l'ensemble des régions du monde ou l'ensemble des pays
	// - type = subdivision : il faut préciser le pays (code ISO alpha2) pour lequel on vide toutes les subdivisions
	if (type_pays_est_valide($type, $pays)) {
		// Inutile de vider une table vide
		if (territoire_est_peuple($type, $pays)) {
			// Avant de vider la table on réserve la liste des id de territoire qui seront supprimés
			// de façon à vider ensuite les liens éventuels y compris ceux des logos.
			// -- on calcule donc d'emblée la condition IN qui sera appliquée sur la table de liens.
			$from = 'spip_territoires';
			$where = array(
				'type=' . sql_quote($type),
			);
			if ($type === 'subdivision') {
				$where[] = 'iso_pays=' . sql_quote($pays);
			}
			$where_lien[] = sql_in_select('id_territoire', 'id_territoire', $from, $where);
			$where_logo[] = sql_in_select('id_objet', 'id_territoire', $from, $where);

			$sql_ok = sql_delete($from, $where);
			if ($sql_ok !== false) {
				// Vider les liens éventuels avec les autres objets
				$sql_ok = sql_delete('spip_territoires_liens', $where_lien);

				// Vider les liens éventuels avec les logos (on gère pas d'erreur)
				$where_logo[] = 'objet=' . sql_quote('territoire');
				sql_delete('spip_documents_liens', $where_logo);

				// Supprimer la meta propre au pays même si le vidage des liens est en erreur.
				peuplement_deconsigner($type, $pays);

				// Enregistrer le succès ou pas du déchargement de la table
				if ($sql_ok !== false) {
					// Succès complet
					$retour['ok'] = true;
					spip_log("Les territoires (Type '${type}' - Pays '${pays}') ont été vidés avec succès ainsi que les liens", 'territoires' . _LOG_DEBUG);
				} else {
					spip_log("Les territoires (Type '${type}' - Pays '${pays}') ont été vidés avec succès mais erreur lors du vidage des liens ", 'territoires' . _LOG_ERREUR);
				}
			} else {
				spip_log("Erreur de vidage des territoires (Type '${type}' - Pays '${pays}')", 'territoires' . _LOG_ERREUR);
			}
		} else {
			$retour['sha'] = true;
			spip_log("Aucun territoire (Type '${type}' - Pays '${pays}') à vider", 'territoires' . _LOG_ERREUR);
		}
	} else {
		$retour['arg'] = true;
		spip_log("Le couple (Type '${type}' - Pays '${pays}') est invalide", 'territoires' . _LOG_ERREUR);
	}

	return $retour;
}

/**
 * Extrait, pour un les régions, les pays ou les ubdivisions d'un pays, la liste des territoires ayant fait l'objet
 * d'une modification manuelle (descriptif ou logo) et la liste associations vers ses mêmes territoires.
 *
 * @api
 *
 * @param string $type Type de territoires à préserver. Prends les valeurs `zone`, `country` ou `subdivision`.
 * @param string $pays Code ISO 3166-1 alpha2 du pays nécessaire si le type est `subdivision`.
 *
 * @return array
 */
function territoire_preserver($type, $pays = '') {

	// Initialisation de la table et de la condition de base sur le type et éventuellement le pays.
	$territoires = array();
	$from = 'spip_territoires';
	$where = array(
		'type=' . sql_quote($type),
	);
	if ($type === 'subdivision') {
		$where[] = 'iso_pays=' . sql_quote($pays);
	}

	// Extraction des liens vers les territoires du pays
	$where_lien = array(
		sql_in_select('id_territoire', 'id_territoire', $from, $where)
	);
	$territoires['liens'] = sql_allfetsel('*', 'spip_territoires_liens', $where_lien);

	// Extraction des liens de logos vers les territoires du pays
	$where_logo = array(
		'objet=' . sql_quote('territoire'),
		sql_in_select('id_objet', 'id_territoire', $from, $where)
	);
	$territoires['logos'] = sql_allfetsel('*', 'spip_documents_liens', $where_logo);

	// Extraction de la correspondance id-iso pour les liens et les logos
	$select = array('id_territoire', 'iso_territoire');
	// -- les liens
	$where_ids = array(
		sql_in('id_territoire', array_column($territoires['liens'], 'id_territoire'))
	);
	$ids = sql_allfetsel($select, $from, $where_ids);
	$territoires['ids'] = array_column($ids, 'id_territoire', 'iso_territoire');

	// -- les logos
	$where_ids = array(
		sql_in('id_territoire', array_column($territoires['logos'], 'id_objet'))
	);
	$ids = sql_allfetsel($select, $from, $where_ids);
	$ids = array_column($ids, 'id_territoire', 'iso_territoire');
	$territoires['ids'] = array_merge($territoires['ids'], $ids);

	// Extraction des territoires éditées.
	// -- détermination de la liste des champs éditables.
	include_spip('base/objets');
	$description_table = lister_tables_objets_sql($from);
	// -- pour le select, les champs éditables sont complétés par le code ISO et l'id qui servira aux liens.
	$select = array_merge($description_table['champs_editables'], array('iso_territoire'));
	$where[] = 'edite=' . sql_quote('oui');
	$editions = sql_allfetsel($select, $from, $where);
	// -- indexer le tableau par le code iso de façon à simplifier la réintégration.
	$territoires['editions'] = array_column($editions, null, 'iso_territoire');

	return $territoires;
}

/**
 * Acquiert les données de territoires disponibles dans Nomenclatures.
 * La fonction utilise l'API fonctionnelle de Nomenclatures mais pourra ensuite utiliser directement API REST.
 *
 * @api
 *
 * @param string $type Type de territoires à acquérie. Prends les valeurs `zone`, `country` ou `subdivision`.
 * @param string $pays Code ISO 3166-1 alpha2 du pays nécessaire si le type est `subdivision`.
* @param array   $options Permet de demander l'exclusion (`exclure`) de certains index si ceux-ci sont inutilisés
 *                        ou de ne retourner qu'un seul index (`index`).
 *
 * @return array
 */
function territoire_acquerir($type, $pays = '', $options = array()) {

	// Initialiser les territoires à vide pour gérer une éventuelle erreur de type.
	$territoires = array();

	// Déterminer la collection et les conditions à appliquer.
	$conditions = array();
	$collection = array();
	if ($type === 'zone') {
		$collection = 'zones';
	} elseif ($type === 'country') {
		$collection = 'pays';
	} elseif ($type === 'subdivision') {
		$collection = 'subdivisions';
		if ($pays) {
			$conditions = array('country=' . sql_quote($pays));
		}
	}

	// Collectionner les territoires avec l'API fonctionnelle
	if ($collection) {
		// Ajouter les exclusions si nécessaire
		$filtres = array();
		if (!empty($options['exclure'])) {
			$filtres = array('exclure' => $options['exclure']);
		}

		// TODO : utiliser l'API REST quand le site sera disponible
		include_spip('ezrest/isocode');
		$collectionner = "${collection}_collectionner";
		$territoires = $collectionner(
			$conditions,
			$filtres,
			array()
		);

		// Si on a demandé un seul index on le renvoie seul sinon on renvoie le tableau complet.
		if (
			!empty($options['index'])
			and isset($territoires[$options['index']])
		) {
			$territoires = $territoires[$options['index']];
		}
	}

	return $territoires;
}

/**
 * Renvoie la liste des pays dont les territoires ont été chargées.
 * La fonction lit la meta de chargement et non la table spip_territoires.
 *
 * @api
 *
 * @param string       $type Type de territoires à acquérie. Prends les valeurs `zone`, `country` ou `subdivision`.
 * @param array|string $pays Code ISO 3166-1 alpha2 du pays si le type est une subdivision.
 *
 * @return bool true si le territoire est chargé, false sinon.
 */
function territoire_est_peuple($type, $pays = '') {

	// Initialisation de la liste
	$est_peuple = false;

	// La liste des territoires chargés est en meta.
	include_spip('inc/config');
	$peuplement = lire_config('territoires_peuplement', array());
	if ($type === 'subdivision') {
		// Chaque pays chargé est un index du tableau
		if (isset($peuplement[$type])) {
			$est_peuple = array_key_exists($pays, $peuplement[$type]);
		}
	} else {
		$est_peuple = isset($peuplement[$type]);
	}

	return $est_peuple;
}

// -----------------
// Services internes
// -----------------

/**
 * Vérifie si le couple (type, pays) est valide, à savoir, désigne bien un sous-ensemble cohérent de territoires.
 * Les sous-ensembles valides sont :
 * - les régions du monde
 * - les pays
 * - les subdivisions d'un pays.
 *
 * @internal
 *
 * @param string $type Type de territoires. Prends les valeurs `zone`, `country` ou `subdivision`.
 * @param string $pays Code ISO 3166-1 alpha2 du pays dont on veut peupler les subdivisions.
 *                     N'est utilisé que si le type choisi est `subdivision`.
 *
 * @return bool `true` si le couple (type, pays) est valide, `false` sinon.
 */
function type_pays_est_valide($type, $pays = '') {
	$est_valide = false;

	// On récupère le sha de la table dans les metas si il existe (ie. la table a été chargée)
	if ($type === 'subdivision') {
		if (strlen($pays) === 2) {
			$est_valide = true;
		}
	} elseif (in_array($type, array('zone', 'country'))) {
		$est_valide = true;
	}

	return $est_valide;
}

/**
 * Compare le sha passé en argument pour le type de territoire concerné avec le sha stocké dans la meta
 * pour cette même type.
 *
 * @internal
 *
 * @param string $sha  SHA à comparer à celui du type de territoire.
 * @param string $type Type de territoires à peupler. Prends les valeurs `zone`, `country` ou `subdivision`.
 * @param string $pays Code ISO 3166-1 alpha2 du pays dont on veut peupler les subdivisions.
 *                     N'est utilisé que si le type choisi est `subdivision`.
 *
 * @return bool `true` si le sha passé en argument est identique au sha stocké pour la table choisie, `false` sinon.
 */
function sha_est_identique($sha, $type, $pays = '') {
	$sha_identique = false;

	// On récupère le sha de la table dans les metas si il existe (ie. la table a été chargée)
	include_spip('inc/config');
	if ($type === 'subdivision') {
		$sha_stocke = lire_config("territoires_peuplement/${type}/${pays}/sha", false);
	} else {
		$sha_stocke = lire_config("territoires_peuplement/${type}/sha", false);
	}

	if ($sha_stocke and ($sha == $sha_stocke)) {
		$sha_identique = true;
	}

	return $sha_identique;
}

/**
 * Fusionne les traductions d'une balise `<multi>` avec celles d'une autre balise `<multi>`.
 * L'une des balise est considérée comme prioritaire ce qui permet de régler le cas où la même
 * langue est présente dans les deux balises.
 * Si on ne trouve pas de balise `<multi>` dans l'un ou l'autre des paramètres, on considère que
 * le texte est tout même formaté de la façon suivante : texte0[langue1]texte1[langue2]texte2...
 *
 * @internal
 *
 * @param string $multi_prioritaire
 *                                      Balise multi considérée comme prioritaire en cas de conflit sur une langue.
 * @param string $multi_non_prioritaire
 *                                      Balise multi considérée comme non prioritaire en cas de conflit sur une langue.
 *
 * @return string
 *                La chaine construite est toujours une balise `<multi>` complète ou une chaine vide sinon.
 */
function traduction_fusionner($multi_prioritaire, $multi_non_prioritaire) {
	$multi_merge = '';

	// On extrait le contenu de la balise <multi> si elle existe.
	$multi_prioritaire = trim($multi_prioritaire);
	$multi_non_prioritaire = trim($multi_non_prioritaire);

	// Si les deux balises sont identiques on sort directement avec le multi prioritaire ce qui améliore les
	// performances.
	if ($multi_prioritaire == $multi_non_prioritaire) {
		$multi_merge = $multi_prioritaire;
	} else {
		include_spip('inc/filtres');
		if (preg_match(_EXTRAIRE_MULTI, $multi_prioritaire, $match)) {
			$multi_prioritaire = trim($match[1]);
		}
		if (preg_match(_EXTRAIRE_MULTI, $multi_non_prioritaire, $match)) {
			$multi_non_prioritaire = trim($match[1]);
		}

		if ($multi_prioritaire) {
			if ($multi_non_prioritaire) {
				// On extrait les traductions sous forme de tableau langue=>traduction.
				$traductions_prioritaires = extraire_trads($multi_prioritaire);
				$traductions_non_prioritaires = extraire_trads($multi_non_prioritaire);

				// On complète les traductions prioritaires avec les traductions non prioritaires dont la langue n'est pas
				// présente dans les traductions prioritaires.
				foreach ($traductions_non_prioritaires as $_lang => $_traduction) {
					if (!array_key_exists($_lang, $traductions_prioritaires)) {
						$traductions_prioritaires[$_lang] = $_traduction;
					}
				}

				// On construit le contenu de la balise <multi> mergé à partir des traductions prioritaires mises à jour.
				// Les traductions vides sont ignorées.
				ksort($traductions_prioritaires);
				foreach ($traductions_prioritaires as $_lang => $_traduction) {
					if ($_traduction) {
						$multi_merge .= ($_lang ? '[' . $_lang . ']' : '') . trim($_traduction);
					}
				}
			} else {
				$multi_merge = $multi_prioritaire;
			}
		} else {
			$multi_merge = $multi_non_prioritaire;
		}

		// Si le contenu est non vide on l'insère dans une balise <multi>
		if ($multi_merge) {
			$multi_merge = '<multi>' . $multi_merge . '</multi>';
		}
	}

	return $multi_merge;
}

/**
 * Consigne le peuplement d'un type de territoire.
 *
 * @internal
 *
 * @param array  $contenu Informations sur le peuplement à consigner dans la meta
 * @param string $type    Type de territoires à peupler. Prends les valeurs `zone`, `country` ou `subdivision`.
 * @param string $pays    Code ISO 3166-1 alpha2 du pays dont on veut peupler les subdivisions.
 *                        N'est utilisé que si le type choisi est `subdivision`.
 *
 * @return void
 */
function peuplement_consigner($contenu, $type, $pays = '') {

	// Prendre en compte l'indexation par pays pour les subdivisions
	include_spip('inc/config');
	if ($type === 'subdivision') {
		ecrire_config("territoires_peuplement/${type}/${pays}", $contenu);
	} else {
		ecrire_config("territoires_peuplement/${type}", $contenu);
	}
}

/**
 * Supprime la consignation du peuplement d'un type de territoire.
 *
 * @internal
 *
 * @param string $type Type de territoires à peupler. Prends les valeurs `zone`, `country` ou `subdivision`.
 * @param string $pays Code ISO 3166-1 alpha2 du pays dont on veut peupler les subdivisions.
 *                     N'est utilisé que si le type choisi est `subdivision`.
 *
 * @return void
 */
function peuplement_deconsigner($type, $pays = '') {

	// Prendre en compte l'indexation par pays pour les subdivisions
	include_spip('inc/config');
	if ($type === 'subdivision') {
		effacer_config("territoires_peuplement/${type}/${pays}");
	} else {
		effacer_config("territoires_peuplement/${type}");
	}
}

/**
 * Supprime la consignation du peuplement d'un type de territoire.
 *
 * @internal
 *
 * @param string $type_lien   Type de liens à restaurer : `liens` ou `logos`.
 * @param array  $sauvegardes Tableau des sauvegardes dans lequel puiser les liens existants et les nouveaux id.
 *
 * @return void
 */
function lien_retablir($type_lien, $sauvegardes) {

	// Configuration des tables de liens
	static $tables = array(
		'liens' => array(
			'table'    => 'spip_territoires_liens',
			'id_table' => 'id_territoire'
		),
		'logos' => array(
			'table'    => 'spip_documents_liens',
			'id_table' => 'id_objet'
		)
	);

	// On contruit la liste des enregistrements correspondant aux liens à rétablir.
	$liens = array();
	foreach ($sauvegardes[$type_lien] as $_lien) {
		// identifier le code iso à partir de l'ancien id
		$iso = array_search($_lien[$tables[$type_lien]['id_table']], $sauvegardes['ids']);
		// en déduire le nouvel id de la territoire et le mettre à jour dans le tableau
		// -- si l'iso n'est pas trouvé dans les nouveaux c'est que la territoire n'existe plus
		//    et qu'il ne faut pas rétablir les liens
		if (isset($ids[$iso])) {
			$lien = $_lien;
			$lien[$tables[$type_lien]['id_table']] = $ids[$iso];
			$liens[] = $lien;
		}
	}

	// Insertion des liens en une seule requête
	if ($liens) {
		sql_insertq_multi($tables[$type_lien]['table'], $liens);
	}
}

function enregistrement_initialiser($territoire, $type, $pays = '') {

	// Traduire les champs de Isocode en champs pour Territoires
	$enregistrement = array();
	$champs = $GLOBALS['territoires'][$type]['champs']['base'];
	foreach ($champs as $_champ_source => $_champ_territoire) {
		$enregistrement[$_champ_territoire] = $territoire[$_champ_source];
	}

	// Compléter systématiquement avec le type et le nom d'usage qui pour l'instant n'est pas fourni
	// TODO : pour l'instant Nomenclatures ne fournit pas de nom d'usage : on duplique le nom ISO.
	$enregistrement['type'] = $type;
	$enregistrement['nom_usage'] = $enregistrement['iso_titre'];


	// Gestion des parentés inter-types : on remplit systématiquement le champ parent pour créer une hiérarchie complète
	// inter-type et ce que le type parent soit peuplé ou pas.
	// Cela revient :
	// -- à ajouter le pays d'appartenance des subdivisions de plus haut niveau
	if (
		($type === 'subdivision')
		and (!$enregistrement['iso_parent'])
	) {
		// Le pays d'appartenance est toujours inclus dans le champ iso_pays
		$enregistrement['iso_parent'] = $enregistrement['iso_pays'];
	}
	// -- à ajouter la région d'appartenance des pays (pas de hiérarchie dans les pays).
	if (
		($type === 'country')
	) {
		// La région d'appartenance est toujours inclus dans le champ code_num_region fourni par Nomenclatures
		$enregistrement['iso_parent'] = $territoire['code_num_region'];
	}

	return $enregistrement;
}
