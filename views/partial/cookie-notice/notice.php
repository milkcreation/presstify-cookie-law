<?php
/**
 * @var tiFy\Plugins\CookieLaw\CookieLawView $this
 */
?>
<div class="CookieLaw-notice">
    <?php $this->insert('partial/cookie-notice/notice-text', $this->all()); ?>

    <div class="CookieLaw-buttons">
        <?php $this->insert('partial/cookie-notice/notice-accept', $this->all()); ?>

        <?php $this->insert('partial/cookie-notice/notice-read', $this->all()); ?>
    </div>

    <?php $this->insert('partial/cookie-notice/notice-close', $this->all()); ?>
</div>
<div class="CookieLaw-backdrop"></div>