# PHP 4 road-test note

The PHP 4 reproduction intentionally includes only behavior supported by the recovered lesson evidence.

A manual browser road test performed after PR #9 exposed that the reproduction showed three sidebar links labelled `Categoria 1`, `Categoria 2`, and `Categoria 3` with `href="#"`. Those links were not backed by any implemented category/genere filtering behavior and were not required by the documented evidence boundary.

They were therefore removed in the follow-up correction rather than inventing a genre-filtering capability that the recovered PHP 4 evidence does not establish.

The static genre options in `adminprodotti.php` remain part of the reproduced lesson boundary.
