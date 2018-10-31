<?php
/**
 * @var tiFy\Contracts\View\ViewController $this
 */
?>

<?php $wp_query = new WP_Query(['page_id' => $this->get('privacy_policy_id')]); ?>

<div class="modal-body">
<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
    <?php the_content(); ?>
<?php endwhile; wp_reset_query(); ?>
</div>
