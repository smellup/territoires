<?php
// This is a SPIP language file  --  Ceci est un fichier langue de SPIP

if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

$GLOBALS[$GLOBALS['idx_lang']] = array(
	// C
	'cfg_titre_parametrages'    => 'Paramétrages',
	'cfg_label_objets_associes' => 'Permettre l\'association de territoire à des objets',

	// E
	'explication_peupler_form' => 'Si les territoires sont déjà chargées ils seront supprimés avant le rechargement. Néanmoins, les éventuelles modifications manuelles faites après le chargement initial et les liens avec les logos et les autres objets seront conservés.
	Le vidage lui ne conserve ni les territoires ni les liens.',
	'explication_zone_pays_territoire'    => 'Régions & Pays',
	'explication_subdivision_territoire'  => 'Subdivisions',

	// I
	'info_territoire_peuple' => 'chargé',
	'info_aucun_parent'      => 'aucune',

	// L
	'label_peupler_action'          => 'Choisissez une action',
	'label_peupler_territoire'      => 'Choisissez les territoires sur lesquels appliquer l\'action sélectionnée',

	// M
	'menu_peupler'    => 'Peupler les territoires',
	'menu_configurer' => 'Configurer le plugin',
	'menu_lister'     => 'Lister les territoires',

	// O
	'option_depeupler_action'    => 'Vider',
	'option_peupler_action'      => 'Peupler',
	'option_zone_territoire'     => 'Toutes les régions du monde',
	'option_country_territoire'  => 'Tous les pays',
	'onglet_tous'                => 'Tous',
	'onglet_zone'                => 'Zones',
	'onglet_country'             => 'Pays',
	'onglet_subdivision'         => 'Subdivisions',

	// S
	'msg_depeupler_zone_erreur'           => 'Une erreur s\'est produite lors du vidage des régions du monde.',
	'msg_depeupler_zone_notice'           => 'Aucun vidage n\'est nécessaire pour les régions du monde.',
	'msg_depeupler_zone_succes'           => 'Les régions du monde ont bien été vidées.',
	'msg_peupler_zone_erreur'             => 'Une erreur s\'est produite lors du peuplement des régions du monde.',
	'msg_peupler_zone_notice'             => 'Aucune mise à jour n\'est nécessaire pour les régions du monde.',
	'msg_peupler_zone_succes'             => 'Les régions du monde ont bien été chargées.',
	'msg_depeupler_country_erreur'        => 'Une erreur s\'est produite lors du vidage des pays.',
	'msg_depeupler_country_notice'        => 'Aucun vidage n\'est nécessaire pour les pays.',
	'msg_depeupler_country_succes'        => 'Les pays ont bien été vidés.',
	'msg_peupler_country_erreur'          => 'Une erreur s\'est produite lors du peuplement des pays.',
	'msg_peupler_country_notice'          => 'Aucune mise à jour n\'est nécessaire pour les pays.',
	'msg_peupler_country_succes'          => 'Les pays ont bien été chargés.',
	'msg_depeupler_subdivision_erreur'    => 'Une erreur s\'est produite lors du vidage des subdivisions du ou des pays « @pays@ ».',
	'msg_depeupler_subdivision_notice'    => 'Aucun vidage n\'est nécessaire pour les subdivisions du ou des pays « @pays@ ».',
	'msg_depeupler_subdivision_succes'    => 'Les subdivisions du ou des pays « @pays@ » ont bien été vidées.',
	'msg_peupler_subdivision_erreur'      => 'Une erreur s\'est produite lors du peuplement des subdivisions du ou des pays « @pays@ ».',
	'msg_peupler_subdivision_notice'      => 'Aucune mise à jour n\'est nécessaire pour les subdivisions du ou des pays « @pays@ ».',
	'msg_peupler_subdivision_succes'      => 'Les subdivisions du ou des pays « @pays@ » ont bien été chargées.',

	// T
	'territoires_titre'     => 'Territoires',
	'titre_page_configurer' => 'Configurer Territoires',
	'titre_page_peupler'    => 'Peupler Territoires',
);
