<?php
/**
 * Ce fichier contient les fonctions d'API du plugin Taxonomie utilisées comme filtre dans les squelettes.
 * Les autres fonctions de l'API sont dans le fichier `inc/taxonomie`.
 *
 */
if (!defined('_ECRIRE_INC_VERSION')) {
	return;
}


/**
 * Fournit l'ascendance géogrpahique d'un territoire, par consultation dans la base de données.
 *
 * @package SPIP\TERRITOIRE\API
 *
 * @api
 * @filtre
 *
 * @param string $iso_territoire
 *        Id du taxon pour lequel il faut fournir l'ascendance.
 * @param string $iso_parent
 *        TSN du parent correspondant au taxon id_taxon. Ce paramètre permet d'optimiser le traitement
 *        mais n'est pas obligatoire. Si il n'est pas connu lors de l'appel il faut passer `null`.
 * @param string $ordre
 *        Classement de la liste des taxons : `descendant`(défaut) ou `ascendant`.
 *
 * @return array
 *        Liste des taxons ascendants. Chaque taxon est un tableau associatif contenant les informations
 *        suivantes : `id_taxon`, `tsn_parent`, `nom_scientifique`, `nom_commun`, `rang`, `statut` et l'indicateur
 *        d'espèce `espèce`.
 */
function territoire_informer_ascendance($iso_territoire, $iso_parent = null, $ordre = 'descendant') {

	$ascendance = array();

	// Si on ne passe pas le tsn du parent correspondant au taxon pour lequel on cherche l'ascendance
	// alors on le cherche en base de données.
	// Le fait de passer ce tsn parent est uniquement une optimisation.
	if (\is_null($iso_parent)) {
		$iso_parent = sql_getfetsel('iso_parent', 'spip_territoires', 'iso_territoire=' . sql_quote($iso_territoire));
	}

	while ($iso_parent) {
		$select = array('id_territoire', 'iso_parent', 'nom_usage', 'type', 'categorie');
		$where = array('iso_territoire=' . sql_quote($iso_parent));
		$territoire = sql_fetsel($select, 'spip_territoires', $where);
		if ($territoire) {
			$ascendance[] = $territoire;
			$iso_parent = $territoire['iso_parent'];
		}
	}

	if ($ascendance	and ($ordre == 'descendant')) {
		$ascendance = array_reverse($ascendance);
	}

	return $ascendance;
}
