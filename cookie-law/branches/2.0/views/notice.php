<?php
/**
 * Cookie Law - Notification.
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * @var tiFy\Plugins\CookieLaw\CookieLawView $this
 */
?>
<div class="CookieLaw-notice">
    <?php $this->insert('notice-text', $this->all()); ?>

    <div class="CookieLaw-buttons">
        <?php $this->insert('notice-accept', $this->all()); ?>

        <?php $this->insert('notice-read', $this->all()); ?>
    </div>

    <?php $this->insert('notice-close', $this->all()); ?>
</div>
<div class="CookieLaw-backdrop"></div>