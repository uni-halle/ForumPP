<?php
// use old smiley-management, if Stud.IP is 2.2 or less
if (version_compare($GLOBALS['SOFTWARE_VERSION'], '2.3', '<')) : ?>
   <?= $this->render_partial('index/_old_smileys.php'); return; ?>
<? endif;

require_once('app/models/smiley.php');
$sm = new SmileyFavorites($GLOBALS['user']->id);
?>
<div class="smiley_favorites">
    <? $smileys = Smiley::getByIds($sm->get()) ?>
    <? if (!empty($smileys)) : ?>
        <? foreach ($smileys as $smiley) : ?>
            <img src="<?= $smiley->getUrl() ?>" data-smiley=" :<?= $smiley->name ?>: "
                style="cursor: pointer;" onClick="STUDIP.ForumPP.insertSmiley('<?= $textarea_id ?>', this)">
        <? endforeach ?>
    <? endif ?>
    <br/>
    <a href="<?= URLHelper::getLink('dispatch.php/smileys') ?>" target="new"><?= _("Smileys") ?></a> |
    <a href="<?= format_help_url("Basis.VerschiedenesFormat") ?>" target="new"><?= _("Formatierungshilfen") ?></a>
    <br>
</div>
