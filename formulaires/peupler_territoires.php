<?php
/**
 * Gestion du formulaire de chargement ou de vidage des territoires.
 *
 * @package    SPIP\TERRITOIRES\OBJET
 */
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Chargement des données : le formulaire propose les actions possibles sur les tables de codes ISO,
 * à savoir, charger ou vider et la liste des tables regroupées par service.
 * L'action vider s'appelle décharger car il existe dékà une fonction d'administration de vidage des tables.
 *
 * @uses territoire_acquerir()
 * @uses territoire_est_peuple()
 *
 * @return array
 *      Tableau des données à charger par le formulaire (affichage). Aucune donnée chargée n'est un
 *      champ de saisie, celle-ci sont systématiquement remises à zéro.
 *      - `_actions_peuplement`: (affichage) alias et libellés des actions possibles, `peupler` et `depeupler`
 *      - `_actions_disable`   : (affichage) liste des actions désactivées (`depeupler` si aucun pays n`est chargé)
 *      - `_action_defaut`     : (affichage) action sélectionnée par défaut, `peupler`
 *      - `_types`             : (affichage) types region et pays.
 *      - `_pays`              : (affichage) pays avec leur code et leur nom ISO dont les subdivisions sont disponibles.
 */
function formulaires_peupler_territoires_charger() {

	// Initialisation des valeurs à transmettre au formulaire
	$valeurs = array();

	// Lister les actions sur les tables
	$valeurs['_actions_peuplement'] = array(
		'peupler' => _T('territoires:option_peupler_action'),
		'depeupler'   => _T('territoires:option_depeupler_action')
	);

	// Initialiser la liste des types de territoire disponibles (régions, pays)
	include_spip('inc/territoire');
	$un_territoire_charge = false;
	$valeurs['_types'] = array(
		'zone'    =>  _T('territoires:option_zone_territoire'),
		'country' =>  _T('territoires:option_country_territoire'),
	);
	// -- indiquer les territoires déjà chargés
	foreach ($valeurs['_types'] as $_type => $_label) {
		if (territoire_est_peuple($_type)) {
			$valeurs['_types'][$_type] .= ' - <em>[' . _T('territoires:info_territoire_peuple') . ']</em>';
			$un_territoire_charge = true;
		}
	}

	// Acquérir la liste des pays dont les subdivisions sont disponibles
	$options = array(
		'exclure' => 'alternates',
		'index'   => 'pays'
	);
	$valeurs['_pays'] = territoire_acquerir('subdivision', array(), $options);
	// -- indiquer les territoires déjà chargés
	foreach ($valeurs['_pays'] as $_code => $_pays) {
		if (territoire_est_peuple('subdivision', $_code)) {
			$valeurs['_pays'][$_code] .= ' - <em>[' . _T('territoires:info_territoire_peuple') . ']</em>';
			$un_territoire_charge = true;
		}
	}

	// Ne pas sélectionner des pays par défaut et ne pas se trainer les messages d'erreur
	set_request('types', array());
	set_request('pays', array());

	// Désactiver l'action vider si aucun pays encore chargé
	if (!$un_territoire_charge) {
		$valeurs['_actions_disable'] = array('depeupler' => 'oui');
		$valeurs['_action_defaut'] = 'peupler';
	}

	return $valeurs;
}

/**
 * Vérification des saisies : il est indispensable de choisir une action (`depeupler` ou `peupler`) et
 * au moins un territoire.
 *
 * @return array
 *      Tableau des erreurs sur l'action et/ou le pays ou tableau vide si aucune erreur.
 */
function formulaires_peupler_territoires_verifier() {
	$erreurs = array();

	if (!_request('action_peuplement')) {
		$erreurs['action_peuplement'] = _T('info_obligatoire');
	}

	if (!_request('pays') and !_request('types')) {
		$erreurs['types'] = _T('info_obligatoire');
		$erreurs['pays'] = ' ';
	}

	return $erreurs;
}

/**
 * Exécution du formulaire : les pays choisis sont soit vidés, soit chargés.
 *
 * @uses territoire_peupler()
 * @uses territoire_depeupler()
 * @uses formater_message()
  *
 * @return array
 *      Tableau retourné par le formulaire contenant toujours un message de bonne exécution ou
 *      d'erreur. L'indicateur editable est toujours à vrai.
 */
function formulaires_peupler_territoires_traiter() {

	// Initialisation des messages de retour
	$retour = array(
		'message_ok'     => '',
		'message_erreur' => ''
	);

	// Acquisition des saisies: comme elles sont obligatoires, il existe toujours une action et un territoire
	// à savoir soit un type région ou pays, soit un pays pour une subdivision.
	$action = _request('action_peuplement');
	$types = _request('types');
	$pays = _request('pays');
	if ($pays) {
		$types[] = 'subdivision';
	}

	// On peuple chaque type (ou type,pays pour les subdivisions).
	// (La fonction de chargement lance un vidage préalable si le pays demandé est déjà chargée)
	include_spip('inc/territoire');
	$actionner = "territoire_${action}";
	foreach ($types as $_type) {
		// Traitement du type en prenant en compte le cas particulier des subdivisions.
		if ($_type === 'subdivision') {
			foreach ($pays as $_pays) {
				$statut[] = $actionner($_type, $_pays);
			}
		} else {
			$statut[] = $actionner($_type);
		}

		// Formater le message correspondant au traitement du type
		$retour = message_formater($_type, $retour, $action, $statut);
	}

	// Renvoie au formulaire toujours éditable
	$retour['editable'] = true;

	return $retour;
}


/**
 * Formate les messages de succès et d'erreur résultant des actions de chargement ou de vidage
 * des territoires.
 *
 * @param string $type     Type de territoire.
 * @param array  $messages Tableau des messages ok et nok à compléter.
 * @param string $action   Action venant d'être appliquée à certains pays. Peut prendre les valeurs `peupler` et
 *                         `depeupler`.
 * @param array  $statuts  Tableau résultant de l'action sur le type choisi. Peut-êre un tableau de statut pour les
 *                         subdivisions (plusieurs pays).
 *                         - `ok`   : `true` si l'action a complètement réussi, `false` sinon (au moins une erreur).
 *                         - `sha`  : indique une sha identique donc pas chargement effectué.
 *                         - `arg`  : indique que le couple (type, pays) eest invalide (pas possible avec le formulaire).
 *                         - `type` : type de territoire.
 *                         - `pays` : code ISO alpha2 du pays si le type est subdivision.
 *
 * @return array
 *      Tableau des messages à afficher sur le formulaire:
 *      - `message_ok`     : message sur les types ayant été traités avec succès ou vide sinon.
 *      - `message_erreur` : message sur les types en erreur ou vide sinon.
 */
function message_formater($type, $messages, $action, $statuts) {

	$variables = array(
		'ok'  => array(),
		'nok' => array(),
		'sha' => array(),
	);
	$statut_global = array(
		'ok'  => false,
		'nok' => false,
		'sha' => false,
	);

	// On compile la liste des pays traités et un indicateur global pour chaque cas d'erreur.
	foreach ($statuts as $_statut) {
		// Traitement des succès
		if (!empty($_statut['sha'])) {
			if ($type === 'subdivision') {
				$variables['sha'][] = $_statut['pays'];
			}
			$statut_global['sha'] = true;
		} elseif (!$_statut['ok']) {
			if ($type === 'subdivision') {
				$variables['nok'][] = $_statut['pays'];
			}
			$statut_global['nok'] = true;
		} else {
			if ($type === 'subdivision') {
				$variables['ok'][] = $_statut['pays'];
			}
			$statut_global['ok'] = true;
		}
	}

	// Traitement des succès
	if ($statut_global['ok']) {
		$messages['message_ok'] .= _T("territoires:msg_${action}_${type}_succes", array('pays' => implode(', ', $variables['ok'])));
	}

	// Traitement des erreurs
	if ($statut_global['nok']) {
		$messages['message_erreur'] .= _T("territoires:msg_${action}_${type}_erreur", array('pays' => implode(', ', $variables['nok'])));
	}
	if ($statut_global['sha']) {
		$messages['message_erreur'] .= $messages['message_erreur'] ? '<br />' : '';
		$messages['message_erreur'] .= _T("territoires:msg_${action}_${type}_notice", array('pays' => implode(', ', $variables['sha'])));
	}

	return $messages;
}