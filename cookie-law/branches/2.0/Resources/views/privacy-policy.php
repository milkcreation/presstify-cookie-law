<?php
/**
 * @var tiFy\Contracts\View\ViewController $this
 * @var tiFy\Partial\Partials\Modal\Modal $modal .
 */
?>
<div class="CookieLaw-privacyPolicy">
    <?php if ($privacy_policy_id = $this->get('privacy_policy_id')) : ?>
        <?php
        echo partial(
            'modal',
            'cookieLaw-privacyPolicy',
            [
                'attrs'          => [
                    'id' => 'Modal-cookieLaw-privacyPolicy'
                ],
                'options'        => ['show' => false, 'backdrop' => false],
                'header'         => $this->fetch('modal-header', $this->all()),
                'body'           => $this->fetch('modal-body', $this->all()),
                'footer'         => $this->fetch('modal-footer', $this->all()),
                'size'           => 'lg',
                'backdrop_close' => false,
                'in_footer'      => false,
            ]
        );
        ?>
    <?php endif; ?>
</div>