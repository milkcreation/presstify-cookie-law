<?php
/**
 * @var tiFy\Contracts\Views\ViewInterface $this.
 * @var tiFy\Partial\Modal\Modal $modal.
 */
?>

<?php if ($privacy_policy_id = $this->get('privacy_policy_id')) : ?>
    <?php
    $modal = partial(
        'modal',
        [
            'options'   => ['show' => (request()->cookie('CookieLaw_' . COOKIEHASH) ? false : true)],
            'header'    => $this->fetch('modal-header', compact('privacy_policy_id')),
            'body'      => $this->fetch('modal-body', compact('privacy_policy_id')),
            'footer'    => $this->fetch('modal-footer'),
            'size'      => 'lg',
            'backdrop_close' => false,
            'in_footer' => false
        ]
    );

    echo $modal->trigger(
        [
            'attrs'   => [
                'class'  => 'CookieLaw-button CookieLaw-button--privacy_policy',
                'href'   => get_permalink($privacy_policy_id),
                'target' => '_blank'
            ],
            'content' => __('En savoir plus', 'theme')
        ]
    );

    echo $modal;
    ?>
<?php endif; ?>