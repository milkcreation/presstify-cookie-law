<?php
/**
 * @var tiFy\Contracts\Views\ViewInterface
 */
?>

<div class="CookieLaw-text">
    <?php
    _e(
        'En poursuivant votre navigation sur ce site, vous acceptez l’utilisation de cookies pour vous proposer ' .
        'des services et offres adaptés à vos centres d’intérêts.',
        'theme'
    );
    ?>
</div>

<?php
echo partial('tag', [
    'tag'     => 'a',
    'attrs'   => [
        'class'        => 'CookieLaw-button CookieLaw-button--accept',
        'href'         => "#{$this->get('id')}",
        'aria-toggle'  => 'accept'
    ],
    'content' => __('Accepter','theme')
]);
?>

<?php if ($privacy_policy_url = $this->get('privacy_policy_url', '')) : ?>
    <?php
    echo partial('tag', [
        'tag'     => 'a',
        'attrs'   => [
            'class'  => 'CookieLaw-button CookieLaw-button--privacy_policy',
            'href'   => $privacy_policy_url,
            'target' => '_blank'
        ],
        'content' => __('En savoir plus', 'theme')
    ]);
    ?>
<?php endif; ?>

<?php
echo partial('tag', [
    'tag'     => 'a',
    'attrs'   => [
        'class'        => 'CookieLaw-close',
        'href'         => "#{$this->get('id')}",
        'aria-toggle' => 'dismiss'
    ],
    'content' =>  __('Fermer', 'theme')
]);