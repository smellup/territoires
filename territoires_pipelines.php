<?php
/**
 * Utilisations de pipelines par Territoires
 *
 * @plugin     Territoires
 * @copyright  2020
 * @author     Eric Lupinacci
 * @licence    GNU/GPL
 * @package    SPIP\Territoires\Pipelines
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Ajout de contenu sur certaines pages,
 * notamment des formulaires de liaisons entre objets
 *
 * @pipeline affiche_milieu
 * @param  array $flux Données du pipeline
 * @return array       Données du pipeline
 */
function territoires_affiche_milieu($flux) {

	if (isset($flux['args']['exec'])) {
		$texte = '';
		$exec = trouver_objet_exec($flux['args']['exec']);

		if (
			($exec['edition'] !== true) // page visu
			and ($table = $exec['table_objet_sql'])
			and (in_array($table, lire_config('territoires/association_objets', array())))
			and ($id_table = $exec['id_table_objet'])
			and ($objet = $exec['type'])
			and isset($flux['args'][$id_table])
			and ($id_objet = intval($flux['args'][$id_table]))
		) {
			$texte .= recuperer_fond(
				'prive/objets/editer/liens',
				array(
					'table_source' => 'territoires',
					'objet'        => $objet,
					'id_objet'     => $id_objet,
					'editable'     => autoriser('associerv', $objet, $id_objet) ? 'oui' : 'non'
				)
			);
		}

		// Insertion dans la fiche objet.
		if ($texte) {
			if ($p = strpos($flux['data'], '<!--affiche_milieu-->')) {
				$flux['data'] = substr_replace($flux['data'], $texte, $p, 0);
			} else {
				$flux['data'] .= $texte;
			}
		}
	}

	return $flux;
}

/**
 * Enlever l'id_territoire de la liste des critères conditionnels pour la table spip_territoires
 * car cela peut renvoyer une liste vide si l'env contient déjà l'id du territoire.
 *
 * @param array $flux
 *
 * @return array
 */
function territoires_exclure_id_conditionnel($flux) {
	if ($flux['args']['table'] == 'spip_territoires') {
		$flux['data'] = array_merge($flux['data'], array('id_territoire'));
	}

	return $flux;
}

/**
 * Surcharge l'action `modifier` d'un territoire
 * - en positionnant l'indicateur d'édition à `oui`afin que les modifications manuelles soient préservées
 * lors d'un prochain rechargement.
 *
 * @pipeline pre_edition
 *
 * @param array		$flux
 * 		Données du pipeline fournie en entrée (chaque pipeline possède une structure de donnée propre).
 *
 * @return array
 * 		Données du pipeline modifiées pour refléter le traitement.
 *
**/
function territoires_pre_edition($flux) {

	$table = $flux['args']['table'];
	$id = intval($flux['args']['id_objet']);
	$action = $flux['args']['action'];

	// Traitements particuliers de l'objet territoire quand celui-ci est modifié manuellement
	if (($table == 'spip_territoires') and $id) {
		// Modification d'un des champs éditables du territoire
		if ($action == 'modifier') {
			// -- On positionne l'indicateur d'édition à oui, ce qui permettra d'éviter lors
			//    d'un rechargement de perdre les modifications manuelles des champs éditables.
			$flux['data']['edite'] = 'oui';
		}
	}

	return $flux;
}

/**
 * Optimiser la base de données
 *
 * Supprime les liens orphelins de l'objet vers quelqu'un et de quelqu'un vers l'objet.
 *
 * @pipeline optimiser_base_disparus
 * @param  array $flux Données du pipeline
 * @return array       Données du pipeline
 */
function territoires_optimiser_base_disparus($flux) {

	include_spip('action/editer_liens');
	$flux['data'] += objet_optimiser_liens(array('territoire'=>'*'), '*');

	return $flux;
}
