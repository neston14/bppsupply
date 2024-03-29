<?php
/* Copyright (C) 2012      Charles-François BENKE <charles.fr@benke.fr>
 * Copyright (C) 2005-2017 Laurent Destailleur    <eldy@users.sourceforge.net>
 * Copyright (C) 2014-2015 Frederic France        <frederic.france@free.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *  \file       htdocs/core/boxes/box_lastlogin.php
 *  \ingroup    core
 *  \brief      Module to show box of bills, orders & propal of the current year
 */

include_once DOL_DOCUMENT_ROOT.'/core/boxes/modules_boxes.php';

/**
 * Class to manage the box of last login
 */
class box_lastlogin extends ModeleBoxes
{
    var $boxcode="lastlogin";
    var $boximg="object_user";
    var $boxlabel='BoxLoginInformation';
    var $depends = array("user");

    /**
     * @var DoliDB Database handler.
     */
    public $db;
    
    var $param;
    var $enabled = 1;

    var $info_box_head = array();
    var $info_box_contents = array();


    /**
     *  Constructor
     *
     *  @param  DoliDB  $db         Database handler
     *  @param  string  $param      More parameters
     */
    function __construct($db,$param)
    {
        global $conf;

        $this->db=$db;
    }

    /**
     *  Charge les donnees en memoire pour affichage ulterieur
     *
     *  @param  int     $max        Maximum number of records to load
     *  @return void
     */
    function loadBox($max=5)
    {
        global $conf, $user, $langs, $db;

        $textHead = $langs->trans("BoxLoginInformation");
        $this->info_box_head = array(
            'text' => $textHead,
            'limit'=> dol_strlen($textHead),
        );
        
        $line=0;
        $this->info_box_contents[$line][0] = array(
            'td' => '',
            'text' => $langs->trans("User"),
        );
        $this->info_box_contents[$line][1] = array(
            'td' => '',
            'text' => $user->getNomUrl(-1),
            'asis' => 1
        );
        
        $line=1;
        $this->info_box_contents[$line][0] = array(
            'td' => '',
            'text' => $langs->trans("PreviousConnexion"),
        );
        if ($user->datepreviouslogin) $tmp= dol_print_date($user->datepreviouslogin,"dayhour",'tzuser');
        else $tmp= $langs->trans("Unknown");
        $this->info_box_contents[$line][1] = array(
            'td' => '',
            'text' => $tmp,
        );
    }


	/**
	 *	Method to show box
	 *
	 *	@param	array	$head       Array with properties of box title
	 *	@param  array	$contents   Array with properties of box lines
	 *  @param	int		$nooutput	No print, only return string
	 *	@return	void
	 */
    function showBox($head = null, $contents = null, $nooutput=0)
    {
		parent::showBox($this->info_box_head, $this->info_box_contents, $nooutput);
	}
}
