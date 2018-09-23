<?php
/**
 * @var tiFy\Contracts\Views\ViewInterface $this
 */
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