<?php

use tiFy\Plugins\CookieLaw\CookieLaw;

if (!function_exists('cookie_law')) {
    /**
     * Récupération de l'instance.
     *
     * {@internal  Permet l'affichage de la bannière de régles de cookie si appelé en tant que string ou au travers
     * d'un echo.}
     *
     * @return CookieLaw|null
     */
    function cookie_law(): ?CookieLaw
    {
        return app(CookieLaw::class);
    }
}