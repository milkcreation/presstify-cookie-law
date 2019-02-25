<?php
/**
 * @var tiFy\Contracts\View\ViewController $this
 */
?>
<?php if ($privacy_policy_id = $this->get('privacy_policy_id')) :
    $modal = partial('modal', 'cookieLaw-privacyPolicy')->trigger([
        'attrs'   => [
            'href'   => get_permalink($privacy_policy_id),
            'target' => '_blank',
        ],
        'content' => __('Lire', 'tify'),
    ]);
    echo $modal;
endif;