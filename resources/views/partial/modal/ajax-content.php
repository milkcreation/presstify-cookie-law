<?php
/**
 * @var Pollen\Partial\PartialViewLoaderInterface $this
 */
?>
<div class="modal-header">
    <h3 class="modal-title"><?php echo $this->get('title'); ?></h3>
</div>

<div class="modal-body">
    <?php echo $this->get('content'); ?>
</div>