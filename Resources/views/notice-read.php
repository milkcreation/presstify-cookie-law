<?php
/**
 * Cookie Law - Notification | Lecture de la politique de confidentialité.
 * ---------------------------------------------------------------------------------------------------------------------
 *
 * @var tiFy\Plugins\CookieLaw\CookieLawView $this
 */
?>
<?php
if ($policy = $this->privacyPolicy()) :
    if ($this->modal()) :
        echo $this->modal()->trigger([
            'attrs'   => [
                'class'  => 'CookieLaw-button CookieLaw-button--accept',
            ],
            'content' => __('En savoir plus', 'tify')
        ]);
    else :
        echo partial('tag', [
            'tag'     => 'a',
            'attrs'   => [
                'class'  => 'CookieLaw-button CookieLaw-button--accept',
                'href'   => $policy->getPermalink(),
                'target' => '_blank'
            ],
            'content' => __('En savoir plus', 'tify')
        ]);
    endif;
endif;