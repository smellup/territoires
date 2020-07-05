<?php
/**
 * Déclarations relatives à la base de données.
 *
 * @plugin     Territoires
 *
 * @copyright  2020
 * @author     Eric Lupinacci
 * @licence    GNU/GPL
 *
 * @package    SPIP\Territoires\Pipelines
 */
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}

/**
 * Déclaration des alias de tables et filtres automatiques de champs.
 *
 * @pipeline declarer_tables_interfaces
 *
 * @param array $interfaces
 *                          Déclarations d'interface pour le compilateur
 *
 * @return array
 *               Déclarations d'interface pour le compilateur
 */
function territoires_declarer_tables_interfaces($interfaces) {

	$interfaces['table_des_tables']['territoires'] = 'territoires';

	$interfaces['table_des_traitements']['NOM_USAGE']['territoires'] = _TRAITEMENT_TYPO;
	$interfaces['table_des_traitements']['ISO_TITRE']['territoires'] = _TRAITEMENT_TYPO;
	$interfaces['table_des_traitements']['DESCRIPTIF']['territoires'] = _TRAITEMENT_RACCOURCIS;

	return $interfaces;
}

/**
 * Déclaration des objets éditoriaux.
 *
 * @pipeline declarer_tables_objets_sql
 *
 * @param array $tables
 *                      Description des tables
 *
 * @return array
 *               Description complétée des tables
 */
function territoires_declarer_tables_objets_sql($tables) {
	$tables['spip_territoires'] = array(
		'type'       => 'territoire',
		'principale' => 'oui',
		'field'      => array(
			'id_territoire'  => 'bigint(21) NOT NULL',
			'iso_territoire' => 'varchar(10) NOT NULL DEFAULT ""',
			'iso_titre'      => 'text NOT NULL DEFAULT ""',
			'nom_usage'      => 'text NOT NULL DEFAULT ""',
			'descriptif'     => 'text NOT NULL DEFAULT ""',
			'type'           => 'varchar(12) DEFAULT "" NOT NULL',
			'categorie'      => 'varchar(64) NOT NULL DEFAULT ""',
			'iso_continent'  => 'varchar(3) DEFAULT "" NOT NULL',
			'iso_pays'       => 'varchar(2) NOT NULL DEFAULT ""',
			'iso_parent'     => 'varchar(10) NOT NULL DEFAULT ""',
			'edite'          => 'varchar(3) NOT NULL DEFAULT ""',
			'date'           => 'datetime NOT NULL DEFAULT "0000-00-00 00:00:00"',
			'maj'            => 'TIMESTAMP NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'
		),
		'key' => array(
			'PRIMARY KEY'        => 'id_territoire',
			'KEY iso_territoire' => 'iso_territoire',
			'KEY iso_parent'     => 'iso_parent',
			'KEY iso_pays'       => 'iso_pays',
			'KEY iso_continent'  => 'iso_continent',
		),
		'titre'             => 'nom_usage AS titre, "" AS lang',
		'date'              => 'date',
		'champs_editables'  => array('descriptif'),
		'champs_versionnes' => array('descriptif'),
		'rechercher_champs' => array('iso_territoire' => 10, 'iso_titre' => 5, 'nom_usage' => 5, 'descriptif' => 2),
		'tables_jointures'  => array('spip_territoires_liens'),

		'texte_retour'       => 'icone_retour',
		'texte_objets'       => 'territoire:titre_territoires',
		'texte_objet'        => 'territoire:titre_territoire',
		'texte_modifier'     => 'territoire:icone_modifier_territoire',
		'texte_creer'        => 'territoire:icone_creer_territoire',
		'info_aucun_objet'   => 'territoire:info_aucun_territoire',
		'info_1_objet'       => 'territoire:info_1_territoire',
		'info_nb_objets'     => 'territoire:info_nb_territoires',
		'texte_logo_objet'   => 'territoire:titre_logo_territoire',
		'texte_langue_objet' => 'territoire:titre_langue_territoire',
	);

	return $tables;
}

/**
 * Déclaration des tables secondaires (liaisons).
 *
 * @pipeline declarer_tables_auxiliaires
 *
 * @param array $tables
 *                      Description des tables
 *
 * @return array
 *               Description complétée des tables
 */
function territoires_declarer_tables_auxiliaires($tables) {
	$tables['spip_territoires_liens'] = array(
		'field' => array(
			'id_territoire' => 'bigint(21) DEFAULT "0" NOT NULL',
			'objet'         => 'VARCHAR(25) DEFAULT "" NOT NULL',
			'id_objet'      => 'bigint(21) DEFAULT "0" NOT NULL',
			'role'          => "varchar(30) NOT NULL DEFAULT ''",
			'vu'            => 'VARCHAR(6) DEFAULT "non" NOT NULL',
		),
		'key' => array(
			'PRIMARY KEY'       => 'id_territoire,id_objet,objet,role',
			'KEY id_territoire' => 'id_territoire',
			'KEY objet'         => 'objet',
			'KEY id_objet'      => 'id_objet',
			'KEY role'          => 'role'
		)
	);

	return $tables;
}
