<?php
/**
 * Fichier gérant l'installation et désinstallation du plugin Territoires
 *
 * @plugin     Territoires
 * @copyright  2020
 * @author     Eric Lupinacci
 * @licence    GNU/GPL
 * @package    SPIP\Territoires\Installation
 */

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}


/**
 * Fonction d'installation et de mise à jour du plugin Territoires.
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @param string $version_cible
 *     Version du schéma de données dans ce plugin (déclaré dans paquet.xml)
 * @return void
**/
function territoires_upgrade($nom_meta_base_version, $version_cible) {
	$maj = array();

	$maj['create'] = array(
		array('maj_tables', array('spip_territoires', 'spip_territoires_liens'))
	);

	include_spip('base/upgrade');
	maj_plugin($nom_meta_base_version, $version_cible, $maj);
}


/**
 * Fonction de désinstallation du plugin Territoires.
 *
 * @param string $nom_meta_base_version
 *     Nom de la meta informant de la version du schéma de données du plugin installé dans SPIP
 * @return void
**/
function territoires_vider_tables($nom_meta_base_version) {

	sql_drop_table('spip_territoires');
	sql_drop_table('spip_territoires_liens');

	# Nettoyer les liens courants (le génie optimiser_base_disparus se chargera de nettoyer toutes les tables de liens)
	sql_delete('spip_documents_liens', sql_in('objet', array('territoire')));
	sql_delete('spip_mots_liens', sql_in('objet', array('territoire')));
	sql_delete('spip_auteurs_liens', sql_in('objet', array('territoire')));
	// Nettoyer les versionnages
	sql_delete('spip_versions', sql_in('objet', array('territoire')));
	sql_delete('spip_versions_fragments', sql_in('objet', array('territoire')));
	sql_delete('spip_forum', sql_in('objet', array('territoire')));

	// Effacer les meta du plugin
	effacer_meta('territoires');
	effacer_meta('territoires_peuplement');
	effacer_meta($nom_meta_base_version);
}
