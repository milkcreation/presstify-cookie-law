<?php

use tiFy\Plugins\CookieLaw\CookieLaw;

if (!function_exists('cookie_law')) :
    /**
     * Récupération de l'instance.
     *
     * {@internal  Permet l'affichage de la bannière de régles de cookie si appelé en tant que string ou au travers
     * d'un echo.}
     *
     * @return CookieLaw
     */
    function cookie_law()
    {
        /** @var CookieLaw $manager */
        $manager = app(CookieLaw::class);

        return $manager;
    }
endif;