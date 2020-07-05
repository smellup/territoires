<?php
/**
 * Utilisation de l'action supprimer pour l'objet territoire
 *
 * @plugin     Territoires
 * @copyright  2020
 * @author     Eric Lupinacci
 * @licence    GNU/GPL
 * @package    SPIP\Territoires\Action
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}



/**
 * Action pour supprimer un·e territoire
 *
 * Vérifier l'autorisation avant d'appeler l'action.
 *
 * @param null|int $arg
 *     Identifiant à supprimer.
 *     En absence de id utilise l'argument de l'action sécurisée.
**/
function action_supprimer_territoire_dist($arg=null) {
	if (is_null($arg)){
		$securiser_action = charger_fonction('securiser_action', 'inc');
		$arg = $securiser_action();
	}
	$arg = intval($arg);

	// cas suppression
	if (autoriser('supprimer', 'territoire', $arg)) {
		if ($arg) {
			sql_delete('spip_territoires',  'id_territoire=' . sql_quote($arg));
		}
		else {
			spip_log("action_supprimer_territoire_dist $arg pas compris");
		}
	}
}
