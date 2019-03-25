<?php
/**
 * Cookie Law - Politique de confidentialité.
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * @var tiFy\Plugins\CookieLaw\CookieLawView $this
 */
?>
<?php if ($this->privacyPolicy() && $this->modal()) : ?>
    <div class="CookieLaw-privacyPolicy">
        <?php echo $this->modal(); ?>
    </div>
<?php endif;