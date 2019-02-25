<?php
/**
 * @var tiFy\Contracts\View\ViewController $this
 */
?>
<div class="CookieLaw-notice">
    <?php $this->insert('notice-text', $this->all()); ?>

    <?php $this->insert('notice-accept', $this->all()); ?>

    <?php $this->insert('notice-read', $this->all()); ?>

    <?php $this->insert('notice-close', $this->all()); ?>
</div>

<div class="CookieLaw-backdrop"></div>
