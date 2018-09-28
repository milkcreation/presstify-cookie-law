<?php
/**
 * @var tiFy\Contracts\Views\ViewInterface $this
 */
?>

<?php
echo partial(
    'cookie-notice',
    [
        'accept'        => false,
        'attrs'         => [
            'id'    => $this->get('id'),
            'class' => 'CookieLaw'
        ],
        'content'       => $this->fetch('notice', $this->all()),
        'cookie_name'   => 'CookieLaw',
        'cookie_expire' => YEAR_IN_SECONDS,
        'dismiss'       => false,
        'type'          => 'info'
    ]
);