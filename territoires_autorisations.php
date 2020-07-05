<?php
/**
 * Définit les autorisations du plugin Territoires
 *
 * @plugin     Territoires
 * @copyright  2020
 * @author     Eric Lupinacci
 * @licence    GNU/GPL
 * @package    SPIP\Territoires\Autorisations
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}


/**
 * Fonction d'appel pour le pipeline
 * @pipeline autoriser */
function territoires_autoriser() {
}


// -----------------
// Objet territoires


/**
 * Autorisation de voir un élément de menu (territoires)
 *
 * @param  string $faire Action demandée
 * @param  string $type  Type d'objet sur lequel appliquer l'action
 * @param  int    $id    Identifiant de l'objet
 * @param  array  $qui   Description de l'auteur demandant l'autorisation
 * @param  array  $opt   Options de cette autorisation
 * @return bool          true s'il a le droit, false sinon
**/
function autoriser_territoires_menu_dist($faire, $type, $id, $qui, $opt) {
	return autoriser('voir', '_territoires');
}


/**
* Autorisation de voir (territoires)
*
* @param  string $faire Action demandée
* @param  string $type  Type d'objet sur lequel appliquer l'action
* @param  int    $id    Identifiant de l'objet
* @param  array  $qui   Description de l'auteur demandant l'autorisation
* @param  array  $opt   Options de cette autorisation
* @return bool          true s'il a le droit, false sinon
**/
function autoriser_territoires_voir_dist($faire, $type, $id, $qui, $opt) {
	return true;
}


/**
* Autorisation de configurer (territoires)
*
* @param  string $faire Action demandée
* @param  string $type  Type d'objet sur lequel appliquer l'action
* @param  int    $id    Identifiant de l'objet
* @param  array  $qui   Description de l'auteur demandant l'autorisation
* @param  array  $opt   Options de cette autorisation
* @return bool          true s'il a le droit, false sinon
**/
function autoriser_territoires_configurer_dist($faire, $type, $id, $qui, $opt) {
	return autoriser('configurer');
}

/**
* Autorisation de voir (territoire)
*
* @param  string $faire Action demandée
* @param  string $type  Type d'objet sur lequel appliquer l'action
* @param  int    $id    Identifiant de l'objet
* @param  array  $qui   Description de l'auteur demandant l'autorisation
* @param  array  $opt   Options de cette autorisation
* @return bool          true s'il a le droit, false sinon
**/
function autoriser_territoire_voir_dist($faire, $type, $id, $qui, $opt) {
	return true;
}

/**
 * Autorisation de créer (territoire)
 *
 * @param  string $faire Action demandée
 * @param  string $type  Type d'objet sur lequel appliquer l'action
 * @param  int    $id    Identifiant de l'objet
 * @param  array  $qui   Description de l'auteur demandant l'autorisation
 * @param  array  $opt   Options de cette autorisation
 * @return bool          true s'il a le droit, false sinon
**/
function autoriser_territoire_creer_dist($faire, $type, $id, $qui, $opt) {
	return false;
}

/**
 * Autorisation de modifier (territoire)
 *
 * @param  string $faire Action demandée
 * @param  string $type  Type d'objet sur lequel appliquer l'action
 * @param  int    $id    Identifiant de l'objet
 * @param  array  $qui   Description de l'auteur demandant l'autorisation
 * @param  array  $opt   Options de cette autorisation
 * @return bool          true s'il a le droit, false sinon
**/
function autoriser_territoire_modifier_dist($faire, $type, $id, $qui, $opt) {
	return in_array($qui['statut'], array('0minirezo', '1comite'));
}

/**
 * Autorisation de supprimer (territoire)
 *
 * @param  string $faire Action demandée
 * @param  string $type  Type d'objet sur lequel appliquer l'action
 * @param  int    $id    Identifiant de l'objet
 * @param  array  $qui   Description de l'auteur demandant l'autorisation
 * @param  array  $opt   Options de cette autorisation
 * @return bool          true s'il a le droit, false sinon
**/
function autoriser_territoire_supprimer_dist($faire, $type, $id, $qui, $opt) {
	return false;
}



/**
 * Autorisation de lier/délier l'élément (territoires)
 *
 * @param  string $faire Action demandée
 * @param  string $type  Type d'objet sur lequel appliquer l'action
 * @param  int    $id    Identifiant de l'objet
 * @param  array  $qui   Description de l'auteur demandant l'autorisation
 * @param  array  $opt   Options de cette autorisation
 * @return bool          true s'il a le droit, false sinon
**/
function autoriser_associerterritoires_dist($faire, $type, $id, $qui, $opt) {
	return $qui['statut'] == '0minirezo' and !$qui['restreint'];
}
