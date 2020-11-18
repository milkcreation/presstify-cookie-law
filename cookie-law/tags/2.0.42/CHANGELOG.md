# Release Notes

## [v2.0.42 (2020-11-18)](https://svn.tigreblanc.fr/presstify-plugins/cookie-law/tags/2.0.42...v2.0.42)

### Added 

- Création de l'accesseur CookieLawAwareTrait
- Création du partial PrivacyLink


## [v2.0.41 (2020-11-17)](https://svn.tigreblanc.fr/presstify-plugins/cookie-law/tags/2.0.41...v2.0.41)

### Changed

- `config/cookie-law.php` : Encapsulation des éléments de config de Wordpress dans une clé wordpress
- `src/Adapter/WordpressAdapter.php` : Adaptation de l'adapteur Wordpress
- `src/CookieLaw.php` : Méthode parseConfig pour adapter le fonctionnement

## [v2.0.40 (2020-11-16)](https://svn.tigreblanc.fr/presstify-plugins/cookie-law/tags/2.0.40...v2.0.40)

### Changed

- Réécriture incluant la nouvelle structuration

## [v2.0.39 (2020-09-12)](https://svn.tigreblanc.fr/presstify-plugins/cookie-law/tags/2.0.39...v2.0.39)

### Fixed

- `Resources/views/modal/ajax-content.php` : Suppression du footer
