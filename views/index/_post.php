<?
if (!is_array($highlight)) $highlight = array();
?>
<!-- Anker, um zu diesem Posting springen zu k�nnen -->
<a name="<?= $post['topic_id'] ?>"></a>

<form method="post" data-topicid="<?= $post['topic_id'] ?>">
    <?= CSRFProtection::tokenTag() ?>

<div class="posting <?=($zebra) ? 'bg1' : 'bg2'?>" style="position: relative;">
    <span class="corners-top"><span></span></span>

    <? if ($post['fav']) : ?>
    <div class="marked"></div>
    <? endif ?>

    <div class="postbody">
        <div class="title">

            <? if (isset($visitdate) && $post['mkdate'] >= $visitdate && $post['owner_id'] != $GLOBALS['user']->id) : ?>
            <span class="new_posting">
                <?= Assets::img('icons/16/red/new/forum.png', array(
                    'title' => _("Dieser Beitrag ist seit Ihrem letzten Besuch hinzugekommen.")
                )) ?>
            </span>
            <? endif ?>

            <? if (ForumPPEntry::hasEditPerms($post['topic_id'])) : ?>
            <span data-edit-topic="<?= $post['topic_id'] ?>" style="display: none">
                <input type="text" name="name" value="<?= htmlReady($post['name_raw']) ?>" style="width: 100%">
            </span>
            <? endif ?>
            
            <span data-show-topic="<?= $post['topic_id'] ?>">
                <a href="<?= PluginEngine::getLink('forumpp/index/index/' . $post['topic_id']) ?>#<?= $post['topic_id'] ?>">
                <? if ($show_full_path) : ?>
                    <? foreach (ForumPPEntry::getPathToPosting($post['topic_id']) as $pos => $path_part) : ?>
                        <? if ($pos > 1) : ?> &bullet; <? endif ?>
                        <?= ForumPPHelpers::highlight(htmlReady($path_part['name']), $highlight) ?>
                    <? endforeach ?>
                <? else : ?>
                <span data-topic-name="<?= $post['topic_id'] ?>">
                    <?= ($post['name']) ? ForumPPHelpers::highlight($post['name'], $highlight) : ''?>
                </span>
                <? endif ?>
                </a>
            </span>

            <p class="author">
                von <strong><a href="<?= URLHelper::getLink('about.php?username='. get_username($post['owner_id'])) ?>">
                    <?= ForumPPHelpers::highlight(htmlReady($post['author']), $highlight) ?>
                </a></strong>
                am <?= strftime($time_format_string, (int)$post['mkdate']) ?>
            </p>
        </div>

        <!-- Aktionsicons -->
        <span class="action-icons likes" id="like_<?= $post['topic_id'] ?>">
            <?= $this->render_partial('index/_like', array('topic_id' => $post['topic_id'])) ?>
        </span>

        <!-- Postinginhalt -->
        <p class="content">
            <? if (ForumPPEntry::hasEditPerms($post['topic_id'])) : ?>
            <span data-edit-topic="<?= $post['topic_id'] ?>" style="display: none">
                <textarea id="inhalt" name="content" class="add_toolbar"><?= htmlReady($post['content_raw']) ?></textarea>
            </span>
            <? endif ?>
            
            <span data-show-topic="<?= $post['topic_id'] ?>" data-topic-content="<?= $post['topic_id'] ?>">
                <?= ForumPPHelpers::highlight($post['content'], $highlight) ?>
            </span>
        </p>
    </div>

    <? if (ForumPPEntry::hasEditPerms($post['topic_id'])) : ?>
    <span data-edit-topic="<?= $post['topic_id'] ?>" style="display: none">
        <dl class="postprofile">
            <dt>
                <?= $this->render_partial('index/_smiley_favorites') ?>
            </dt>
        </dl>
    </span>
    <? endif ?>

    <!-- Infobox rechts neben jedem Posting -->
    <span data-show-topic="<?= $post['topic_id'] ?>">
        <dl class="postprofile">
            <dt>
                <a href="<?= URLHelper::getLink('about.php?username='. get_username($post['owner_id'])) ?>">
                    <?= Avatar::getAvatar($post['owner_id'])->getImageTag(Avatar::MEDIUM,
                        array('title' => get_username($post['owner_id']))) ?>
                    <br>
                    <strong><?= htmlReady(get_fullname($post['owner_id'])) ?></strong>
                </a>
            </dt>
            <dd>
                <?= ForumPPHelpers::translate_perm($GLOBALS['perm']->get_studip_perm($constraint['seminar_id'], $post['owner_id']))?>
            </dd>
            <dd class="online-status">
                <? switch(ForumPPHelpers::getOnlineStatus($post['owner_id'])) :
                    case 'available': ?>
                        <img src="<?= $picturepath ?>/community.png">
                        <?= _('Online') ?>
                    <? break; ?>

                    <? case 'offline': ?>
                        <?= Assets::img('icons/16/black/community.png') ?>
                        <?= _('Offline') ?>
                    <? break; ?>
                <? endswitch ?>
            </dd>
            <dd>
                Beitr�ge:
                <?= ForumPPEntry::countUserEntries($post['owner_id']) ?>
            </dd>
            <? foreach (PluginEngine::sendMessage('PostingApplet', 'getHTML', $post['name_raw'], $post['content_raw'],
                    PluginEngine::getLink('forumpp/index/index/' . $post['topic_id'] .'#'. $post['topic_id']),
                    $post['owner_id']) as $applet_data) : ?>
            <dd>
                <?= $applet_data ?>
            </dd>
            <? endforeach ?>
        </dl>
    </span>

    <!-- Buttons for this Posting -->
    <? if ($section == 'index') : ?>
    <div class="buttons">
        <div class="button-group">
    <? if (ForumPPEntry::hasEditPerms($post['topic_id'])) : ?>
    <span data-edit-topic="<?= $post['topic_id'] ?>" style="display: none">
        <!-- Buttons f�r den Bearbeitungsmodus -->
        <? Studip\Button::createAccept('�nderungen speichern') ?>
        <?= Studip\LinkButton::createAccept('�nderungen speichern', "javascript:STUDIP.ForumPP.saveEntry('". $post['topic_id'] ."')") ?>

        <? Studip\LinkButton::createCancel('Abbrechen', PluginEngine::getURL('forumpp/index/index/'. $post['topic_id'])) ?>
        <?= Studip\LinkButton::createCancel('Abbrechen', "javascript:STUDIP.ForumPP.cancelEditEntry('". $post['topic_id'] ."')") ?>
        
        <?= Studip\LinkButton::create('Vorschau', "javascript:STUDIP.ForumPP.preview('inhalt', 'preview_". $post['topic_id'] ."');") ?>
    </span>
    <? endif ?>
            
    <span data-show-topic="<?= $post['topic_id'] ?>">
        <!-- Aktions-Buttons f�r diesen Beitrag -->
        <? if (ForumPPEntry::hasEditPerms($post['topic_id'])) : ?>
            <? Studip\LinkButton::create('Beitrag bearbeiten', PluginEngine::getURL('forumpp/index/edit_entry/'. $post['topic_id'])) ?>
            <?= Studip\LinkButton::create('Beitrag bearbeiten', "javascript:STUDIP.ForumPP.editEntry('". $post['topic_id'] ."')") ?>
        <? endif ?>
            
        <? if (ForumPPPerm::has('add_entry', $seminar_id)) : ?>
        <?= Studip\LinkButton::create('Zitieren', PluginEngine::getURL('forumpp/index/cite/'. $post['topic_id'] .'/#create')) ?>
        <? endif ?>

        <? if (ForumPPEntry::hasEditPerms($post['topic_id']) || ForumPPPerm::has('remove_entry', $seminar_id)) : ?>
            <? if ($constraint['depth'] == $post['depth']) : /* this is not only a posting, but a thread */ ?>
                <?= Studip\LinkButton::create('Beitrag l�schen', PluginEngine::getURL('forumpp/index/delete_entry/' . $post['topic_id']),
                    array('onClick' => "return confirm('". _('Wenn Sie diesen Beitrag l�schen wird ebenfalls das gesamte Thema gel�scht.\n'
                            . ' Sind Sie sicher, dass Sie das tun m�chten?') ."')")) ?>
            <? else : ?>
                <?= Studip\LinkButton::create('Beitrag l�schen', PluginEngine::getURL('forumpp/index/delete_entry/' . $post['topic_id']),
                    array('onClick' => "return confirm('". _('M�chten Sie diesen Beitrag wirklich l�schen?') ."')")) ?>
            <? /* "javascript:STUDIP.ForumPP.deleteEntry('{$post['topic_id']}');" */ ?>
            <? endif ?>
        <? endif ?>

        <? if (!$post['fav']) : ?>
            <?= Studip\LinkButton::create('Beitrag merken', PluginEngine::getURL('forumpp/index/set_favorite/' . $post['topic_id'])) ?>
        <? else : ?>
            <?= Studip\LinkButton::create('Beitrag vernachl�ssigen', PluginEngine::getURL('forumpp/index/unset_favorite/' . $post['topic_id'])) ?>
        <? endif ?>
    </span>
        </div>
    </div>
    <? endif ?>

  <span class="corners-bottom"><span></span></span>
</div>
</form>

<?= $this->render_partial('index/_preview', array('preview_id' => 'preview_' . $post['topic_id'])) ?>