<?php
/**
 * Cookie Law - Notification | Validation.
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * @var tiFy\Plugins\CookieLaw\CookieLawView $this
 */
?>
<?php
echo partial('tag', [
    'tag'     => 'a',
    'attrs'   => [
        'class'       => 'CookieLaw-button CookieLaw-button--accept',
        'href'        => "#{$this->get('id')}",
        'data-toggle' => 'notice.accept'
    ],
    'content' => __('Accepter', 'tify')
]);