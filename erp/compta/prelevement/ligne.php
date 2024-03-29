<?php
/* Copyright (C) 2005       Rodolphe Quiedeville    <rodolphe@quiedeville.org>
 * Copyright (C) 2005-2012  Laurent Destailleur     <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2009  Regis Houssin           <regis.houssin@inodbox.com>
 * Copyright (C) 2010-2013  Juanjo Menent           <jmenent@2byte.es>
 * Copyright (C) 2018       Frédéric France         <frederic.france@netlogic.fr>
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
 *	\file       htdocs/compta/prelevement/ligne.php
 *	\ingroup    prelevement
 *	\brief      card of withdraw line
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/compta/prelevement/class/bonprelevement.class.php';
require_once DOL_DOCUMENT_ROOT.'/compta/prelevement/class/ligneprelevement.class.php';
require_once DOL_DOCUMENT_ROOT.'/compta/prelevement/class/rejetprelevement.class.php';
require_once DOL_DOCUMENT_ROOT.'/compta/paiement/class/paiement.class.php';
require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';

// Load translation files required by the page
$langs->loadlangs(array('banks', 'categories', 'bills', 'withdrawals'));

// Security check
if ($user->societe_id > 0) accessforbidden();

// Get supervariables
$action = GETPOST('action','alpha');
$id = GETPOST('id','int');
$socid = GETPOST('socid','int');

$page = GETPOST('page','int');
$sortorder = GETPOST('sortorder','alpha');
$sortfield = GETPOST('sortfield','alpha');

if ($action == 'confirm_rejet')
{
	if ( GETPOST("confirm") == 'yes')
	{
		if (GETPOST('remonth','int'))
		{
			$daterej = mktime(2, 0, 0, GETPOST('remonth','int'), GETPOST('reday','int'), GETPOST('reyear','int'));
		}

		if (empty($daterej))
		{
			$error++;
			setEventMessages($langs->trans("ErrorFieldRequired", $langs->trans("Date")), null, 'errors');
		}

		elseif ($daterej > dol_now())
		{
			$error++;
			$langs->load("error");
			setEventMessages($langs->transnoentities("ErrorDateMustBeBeforeToday"), null, 'errors');
		}

		if (GETPOST('motif','alpha') == 0)
		{
			$error++;
			setEventMessages($langs->trans("ErrorFieldRequired", $langs->transnoentities("RefusedReason")), null, 'errors');
		}

		if ( ! $error )
		{
			$lipre = new LignePrelevement($db, $user);

			if ($lipre->fetch($id) == 0)

			{
				$rej = new RejetPrelevement($db, $user);

				$rej->create($user, $id, GETPOST('motif','alpha'), $daterej, $lipre->bon_rowid, GETPOST('facturer','int'));

				header("Location: ligne.php?id=".$id);
				exit;
			}
		}
		else
		{
			$action="rejet";
		}
	}
	else
	{
		header("Location: ligne.php?id=".$id);
		exit;
	}
}


/*
 * View
 */

$invoicestatic=new Facture($db);

llxHeader('',$langs->trans("StandingOrder"));

$h = 0;
$head[$h][0] = DOL_URL_ROOT.'/compta/prelevement/ligne.php?id='.$id;
$head[$h][1] = $langs->trans("Card");
$hselected = $h;
$h++;

if ($id)
{
	$lipre = new LignePrelevement($db, $user);

	if ($lipre->fetch($id) == 0)
	{
		$bon = new BonPrelevement($db);
		$bon->fetch($lipre->bon_rowid);

		dol_fiche_head($head, $hselected, $langs->trans("StandingOrder"));

		print '<table class="border" width="100%">';

		print '<tr><td width="20%">'.$langs->trans("WithdrawalsReceipts").'</td><td>';
		print $bon->getNomUrl(1).'</td></tr>';
		print '<tr><td width="20%">'.$langs->trans("Date").'</td><td>'.dol_print_date($bon->datec,'day').'</td></tr>';
		print '<tr><td width="20%">'.$langs->trans("Amount").'</td><td>'.price($lipre->amount).'</td></tr>';
		print '<tr><td width="20%">'.$langs->trans("Status").'</td><td>'.$lipre->LibStatut($lipre->statut,1).'</td></tr>';

		if ($lipre->statut == 3)
		{
			$rej = new RejetPrelevement($db, $user);
			$resf = $rej->fetch($lipre->id);
			if ($resf == 0)
			{
				print '<tr><td width="20%">'.$langs->trans("RefusedReason").'</td><td>'.$rej->motif.'</td></tr>';
				print '<tr><td width="20%">'.$langs->trans("RefusedData").'</td><td>';
				if ($rej->date_rejet == 0)
				{
					/* Historique pour certaines install */
					print $langs->trans("Unknown");
				}
				else
				{
					print dol_print_date($rej->date_rejet,'day');
				}
				print '</td></tr>';
				print '<tr><td width="20%">'.$langs->trans("RefusedInvoicing").'</td><td>'.$rej->invoicing.'</td></tr>';
			}
			else
			{
				print '<tr><td width="20%">'.$resf.'</td></tr>';
			}
		}

		print '</table>';
		dol_fiche_end();
	}
	else
	{
		dol_print_error($db);
	}

	if ($action == 'rejet' && $user->rights->prelevement->bons->credit)
	{
		$form = new Form($db);

		$soc = new Societe($db);
		$soc->fetch($lipre->socid);

		$rej = new RejetPrelevement($db, $user);

		print '<form name="confirm_rejet" method="post" action="ligne.php?id='.$id.'">';
		print '<input type="hidden" name="token" value="'.$_SESSION['newtoken'].'">';
		print '<input type="hidden" name="action" value="confirm_rejet">';
		print '<table class="border" width="100%">';

		print '<tr class="liste_titre">';
		print '<td colspan="3">'.$langs->trans("WithdrawalRefused").'</td></tr>';

		//Select yes/no
		print '<tr><td class="valid">'.$langs->trans("WithdrawalRefusedConfirm").' '.$soc->name.' ?</td>';
		print '<td colspan="2" class="valid">';
		print $form->selectyesno("confirm",1,0);
		print '</td></tr>';

		//Date
		print '<tr><td class="fieldrequired valid">'.$langs->trans("RefusedData").'</td>';
		print '<td colspan="2" class="valid">';
		print $form->selectDate('', '', '', '', '', "confirm_rejet");
		print '</td></tr>';

		//Reason
		print '<tr><td class="fieldrequired valid">'.$langs->trans("RefusedReason").'</td>';
		print '<td class="valid">';
		print $form->selectarray("motif", $rej->motifs);
		print '</td></tr>';

		//Facturer
		print '<tr><td class="valid">'.$langs->trans("RefusedInvoicing").'</td>';
		print '<td class="valid" colspan="2">';
		print $form->selectarray("facturer", $rej->facturer);
		print '</td></tr>';
		print '</table><br>';

		//Confirm Button
		print '<div class="center"><input type="submit" class="button" value='.$langs->trans("Confirm").'></div>';
		print '</form>';
	}

	/* ************************************************************************** */
	/*                                                                            */
	/* Barre d'action                                                             */
	/*                                                                            */
	/* ************************************************************************** */

	print "<div class=\"tabsAction\">";

	if ($action == '')
	{
		if ($bon->statut == 2 && $lipre->statut == 2)
		{
			if ($user->rights->prelevement->bons->credit)
			{
	  			print "<a class=\"butAction\" href=\"ligne.php?action=rejet&amp;id=$lipre->id\">".$langs->trans("StandingOrderReject")."</a>";
			}
			else
			{
				print "<a class=\"butActionRefused\" href=\"#\" title=\"".$langs->trans("NotAllowed")."\">".$langs->trans("StandingOrderReject")."</a>";
			}
		}
		else
		{
			print "<a class=\"butActionRefused\" href=\"#\" title=\"".$langs->trans("NotPossibleForThisStatusOfWithdrawReceiptORLine")."\">".$langs->trans("StandingOrderReject")."</a>";
		}
	}

	print "</div>";



	if ($page == -1 || $page == null) { $page = 0 ; }

	$offset = $conf->liste_limit * $page ;
	$pageprev = $page - 1;
	$pagenext = $page + 1;

	if ($sortorder == "") $sortorder="DESC";
	if ($sortfield == "") $sortfield="pl.fk_soc";

	/*
	 * List of invoices
	 */
	$sql = "SELECT pf.rowid";
	$sql.= " ,f.rowid as facid, f.facnumber as ref, f.total_ttc, f.paye, f.fk_statut";
	$sql.= " , s.rowid as socid, s.nom as name";
	$sql.= " FROM ".MAIN_DB_PREFIX."prelevement_bons as p";
	$sql.= " , ".MAIN_DB_PREFIX."prelevement_lignes as pl";
	$sql.= " , ".MAIN_DB_PREFIX."prelevement_facture as pf";
	$sql.= " , ".MAIN_DB_PREFIX."facture as f";
	$sql.= " , ".MAIN_DB_PREFIX."societe as s";
	$sql.= " WHERE pf.fk_prelevement_lignes = pl.rowid";
	$sql.= " AND pl.fk_prelevement_bons = p.rowid";
	$sql.= " AND f.fk_soc = s.rowid";
	$sql.= " AND pf.fk_facture = f.rowid";
	$sql.= " AND f.entity = ".$conf->entity;
	$sql.= " AND pl.rowid=".$id;
	if ($socid)	$sql.= " AND s.rowid = ".$socid;
	$sql.= " ORDER BY $sortfield $sortorder ";
	$sql.= $db->plimit($conf->liste_limit+1, $offset);

	$result = $db->query($sql);

	if ($result)
	{
		$num = $db->num_rows($result);
		$i = 0;

		$urladd = "&amp;id=".$id;

		print_barre_liste($langs->trans("Bills"), $page, "factures.php", $urladd, $sortfield, $sortorder, '', $num, 0, '');

		print"\n<!-- debut table -->\n";
		print '<table class="noborder" width="100%" cellspacing="0" cellpadding="4">';
		print '<tr class="liste_titre">';
		print '<td>'.$langs->trans("Invoice").'</td><td>'.$langs->trans("ThirdParty").'</td><td align="right">'.$langs->trans("Amount").'</td><td align="right">'.$langs->trans("Status").'</td>';
		print '</tr>';

		$total = 0;

		while ($i < min($num,$conf->liste_limit))
		{
			$obj = $db->fetch_object($result);

			print '<tr class="oddeven"><td>';

			print '<a href="'.DOL_URL_ROOT.'/compta/facture/card.php?facid='.$obj->facid.'">';
			print img_object($langs->trans("ShowBill"),"bill");
			print '</a>&nbsp;';

			print '<a href="'.DOL_URL_ROOT.'/compta/facture/card.php?facid='.$obj->facid.'">'.$obj->ref."</a></td>\n";

			print '<td><a href="'.DOL_URL_ROOT.'/comm/card.php?socid='.$obj->socid.'">';
			print img_object($langs->trans("ShowCompany"),"company"). ' '.$obj->name."</a></td>\n";

			print '<td align="right">'.price($obj->total_ttc)."</td>\n";

			print '<td align="right">';
			$invoicestatic->fetch($obj->facid);
			print $invoicestatic->getLibStatut(5);
			print "</td>\n";

			print "</tr>\n";

			$i++;
		}

		print "</table>";

		$db->free($result);
	}
	else
	{
		dol_print_error($db);
	}
}

// End of page
llxFooter();
$db->close();
