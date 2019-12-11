<?php
/**
 * Cookie Law - Gabarit principal
 * ---------------------------------------------------------------------------------------------------------------------
 * @var tiFy\Plugins\CookieLaw\CookieLawView $this
 */
?>
<?php $this->insert('privacy-policy', $this->all()); ?>

<?php echo partial('cookie-notice', [
    'attrs'   => [
        'id' => $this->get('id'),
        'class' => '%s CookieLaw'
    ],
    'content' => $this->fetch('notice', $this->all()),
    'cookie'  => [
        'name'   => 'CookieLaw',
        'expire' => YEAR_IN_SECONDS,
    ],
    'dismiss' => false,
    'type'    => 'info',
    'trigger' => false,
]);