<?php
/**
 * @var tiFy\Partial\PartialView $this
 */
?>
<div class="modal-header">
    <h3 class="modal-title"><?php echo cookie_law()->get('privacy_policy.title'); ?></h3>
</div>

<div class="modal-body">
    <?php echo cookie_law()->get('privacy_policy.content'); ?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary button-secondary" data-control="modal.close">
        <?php _e('Fermer', 'tify'); ?>
    </button>
</div>