<br>
<div id="sortable_areas">
<? foreach ($list as $category_id => $entries) : ?>
<table cellspacing="0" cellpadding="2" border="0" width="100%" class="forum <?= $has_perms && $category_id != $seminar_id ? 'movable' : '' ?>" data-category-id="<?= $category_id ?>">
    <thead>
    <tr>
        <td class="forum_header <?= $has_perms && $category_id != $seminar_id ? 'handle' : '' ?>" colspan="3" width="65%">
            <span class="corners-top"></span>
            <span class="heading">
                <? if (!$category_id) : ?>
                <?= _('Themen') ?>
                <? else: ?>
                <span class="category_name">
                    <?= $categories[$category_id] ?>
                </span>
                <? endif ?>
            </span>
            <span class="heading_edit" style="display: none; margin-left: 5px;">
                <input type="text" name="name" size="40" value="<?= $categories[$category_id] ?>">

                <?= Studip\LinkButton::createAccept('Kategorie speichern', "javascript:STUDIP.ForumPP.saveCategoryName('". $category_id ."')") ?>
                <?= Studip\LinkButton::createCancel('Abbrechen', "javascript:STUDIP.ForumPP.cancelEditCategoryName('". $category_id ."')") ?>
            </span>
        </td>

        <td class="forum_header" width="5%">
            <span class="no-corner"></span>
            <span class="heading"><?= _("Beitr�ge") ?></span>
        </td>

        <td class="forum_header" width="30%" colspan="2">
            <span class="corners-top-right"></span>
            <span class="heading" style="float: left"><?= _("letzte Antwort") ?></span>
            <? if ($has_perms) : ?>
            <span style="float: right; padding-right: 5px;">
                <? if ($category_id == $seminar_id) : ?>
                <?= Assets::img('icons/16/blue/info.png', array(
                    'onClick' => "alert('" . _('Vordefinierte Kategorie, kann nicht bearbeitet oder gel�scht werden.' . '\n'
                        . 'F�r Nutzer/innen ohne Moderationsrechte taucht diese Kategorie nur auf, wenn sie Bereiche enth�lt.') . "')",
                    'style'   => 'cursor: pointer')) ?>
                <? else : ?>
                <a href="javascript:STUDIP.ForumPP.editCategoryName('<?= $category_id ?>')">
                    <?= Assets::img('icons/16/blue/edit.png', array('title' => 'Name der Kategorie �ndern')) ?>
                </a>
                <a href="javascript:STUDIP.ForumPP.deleteCategory('<?= $category_id ?>', '<?= $categories[$category_id] ?>')">
                    <?= Assets::img('icons/16/blue/trash.png', array('title' => 'Kategorie entfernen')) ?>
                </a>
                <? endif ?>
            </span>
            <? endif ?>
        </td>
    </tr>
    </thead>


    <tbody class="sortable">
    <!-- this row allows dropping on otherwise empty categories -->
    <tr class="sort-disabled">
        <td class="areaborder" style="height: 5px"colspan="7"> </td>
    </tr>

    <? if (!empty($entries)) foreach ($entries as $entry) :
        $jump_to_topic_id = $entry['topic_id']; ?>

    <tr data-area-id="<?= $entry['topic_id'] ?>" <?= ($has_perms) ? 'class="movable"' : '' ?>>

        <td class="areaborder"> </td>

        <td class="areaentry icon" width="1%" valign="top" align="center">
            <? if ($has_perms) : ?>
            <div style="height: 50px; float: left; margin-left: 10px;" class="handle">
                <img src="<?= $picturepath ?>/move.png">
            </div>
            <? endif ?>

            <? if (!ForumPPVisit::hasEntry($GLOBALS['user']->id, $entry['topic_id']) && $entry['owner_id'] != $GLOBALS['user']->id): ?>
                <?= Assets::img('icons/16/red/new/forum.png', array(
                    'title' => _('Dieser Eintrag ist neu!')
                )) ?>
            <? else : ?>
                <? $num_postings = ForumPPVisit::getCount($GLOBALS['user']->id, $entry['topic_id']) ?>
                <? $text = ForumPPHelpers::getVisitText($num_postings, $entry['topic_id'], $constraint['depth']) ?>
                <? if ($num_postings['abo'] > 0 || $num_postings['new'] > 0) : ?>
                    <?= Assets::img('icons/16/red/forum.png', array(
                        'title' => $text
                    )) ?>
                <? else : ?>
                    <?= Assets::img('icons/16/black/forum.png', array(
                        'title' => $text
                    )) ?>
                <? endif ?>
            <? endif ?>
        </td>
        <td class="areaentry" valign="top">
            <div style="position: relative;">
                <a href="<?= PluginEngine::getLink('forumpp/index/index/'. $jump_to_topic_id .'#'. $jump_to_topic_id) ?>">
                    <span class="areaname"><?= $entry['name'] ?></span>
                </a>

                <span class="areaname_edit" style="display: none;">
                    <input type="text" name="name" size="20" value="<?= $entry['name'] ?>" onClick="jQuery(this).focus()">

                    <?= Studip\LinkButton::createAccept('Titel speichern', "javascript:STUDIP.ForumPP.saveAreaName('". $entry['topic_id'] ."')") ?>
                    <?= Studip\LinkButton::createCancel('Abbrechen', "javascript:STUDIP.ForumPP.cancelEditAreaName('". $entry['topic_id'] ."')") ?>
                </span>

                <? if ($has_rights) : /* main areas */?>
                <span class="action-icons">
                    <a href="javascript:STUDIP.ForumPP.editAreaName('<?= $entry['topic_id'] ?>');">
                        <?= Assets::img('icons/16/blue/edit.png',
                            array('class' => 'edit-area', 'title' => 'Name des Bereichs �ndern')) ?>
                    </a>

                    <a href="javascript:STUDIP.ForumPP.deleteArea(this, '<?= $entry['topic_id'] ?>')">
                        <?= Assets::img('icons/16/blue/trash.png',
                            array('class' => 'delete-area', 'title' => 'Bereich mitsamt allen Eintr�gen l�schen!')) ?>
                    </a>
                </span>
                <? endif ?>

                <br/>

                <?= _("von") ?>
                <a href="<?= UrlHelper::getLink('about.php?username='. get_username($entry['owner_id'])) ?>">
                    <?= htmlReady($entry['author']) ?>
                </a>
                <?= _("am") ?> <?= strftime($time_format_string_short, (int)$entry['mkdate']) ?>
                <br>
            </div>
        </td>

        <td align="center" valign="top" class="areaentry2">
            <br>
            <?= ($entry['num_postings'] > 0) ? ($entry['num_postings'] - 1) : 0 ?>
        </td>

        <td align="left" valign="top" class="areaentry2">
            <? if (is_array($entry['last_posting'])) : ?>
            <?= _("von") ?>
            <a href="<?= UrlHelper::getLink('about.php?username='. $entry['last_posting']['username']) ?>">
                    <?= htmlReady($entry['last_posting']['user_fullname']) ?>
            </a><br>
            <?= _("am") ?> <?= strftime($time_format_string_short, (int)$entry['last_posting']['date']) ?>
            <a href="<?= PluginEngine::getLink('/forumpp/index/index/'. $entry['last_posting']['topic_id']) ?>#<?= $entry['last_posting']['topic_id'] ?>" alt="<?= $infotext ?>" title="<?= $infotext ?>">
                <?= Assets::img('icons/16/blue/link-intern.png', array('title' => $infotext = _("Direkt zum Beitrag..."))) ?>
            </a>
            <? else: ?>
            <br>
            <?= _('keine Beitr�ge') ?>
            <? endif; ?>
        </td>
        <td class="areaborder"> </td>
    </tr>
    <? endforeach; ?>
    </tbody>

    <tfoot>
    <? if ($category_id && $has_perms) : ?>
    <tr>
        <td class="areaborder" colspan="7">
            <div class="add_area" title="<?= _('Neuen Bereich zu dieser Kategorie hinzuf�gen.') ?>">
                <?= Assets::img('icons/16/black/plus.png') ?>
            </div>
            <form class="add_area_form" style="display: none" method="post" action="<?= PluginEngine::getLink('/forumpp/index/add_area/' . $category_id) ?>">
                <?= CSRFProtection::tokenTag() ?>
                <input type="text" name="name" size="50" placeholder="Name des neuen Bereiches" required>

                <?= Studip\Button::create('Bereich hinzuf�gen') ?>
                <?= Studip\LinkButton::createCancel('Abbrechen', "javascript:STUDIP.ForumPP.cancelAddArea()") ?>
            </form>
        </td>
    </tr>
    <? endif ?>


    <!-- bottom border -->
    <tr>
        <td class="areaborder" colspan="7">
            <span class="corners-bottom"><span></span></span>
        </td>
    </tr>
    <tr>
        <td colspan="6">&nbsp;</td>
    </tr>
    </tfoot>
</table>
<? endforeach ?>
</div>

<div id="question" style="display: none">
    <span id="question_delete_area" style="display: none"><?= _('Sind sie sicher, dass Sie den Bereich <%- area %> l�schen m�chten? '
         . 'Es werden auch alle Beitr�ge in diesem Bereich gel�scht!') ?></span>
    <span id="question_delete_category" style="display: none"><?= _('Sind sie sicher, dass Sie die Kategorie <%- category %> entfernen m�chten? '
         . 'Alle Bereiche werden dann nach "Allgemein" verschoben!') ?></span>
    <?= $GLOBALS['template_factory']->open('shared/question')->render(array(
        'question'        => '',
        'approvalLink'    => "javascript:STUDIP.ForumPP.approveDelete()",
        'disapprovalLink' => "javascript:STUDIP.ForumPP.disapproveDelete()"
    )) ?>
    <? /* createQuestion() */ ?>
</div>