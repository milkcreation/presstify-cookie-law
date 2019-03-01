<?php
/**
 * Cookie Law - Notification | Validation.
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Contracts\View\ViewController $this
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