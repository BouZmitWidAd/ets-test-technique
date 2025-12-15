<?php

// Charge l'autoloader de Composer
require 'vendor/autoload.php';

$classExists = class_exists('App\Document\Session');

if ($classExists) {
    echo "✅ SUCCÈS : La classe App\\Document\\Session est trouvée par l'autoloader de Composer.\n";
} else {
    echo "❌ ÉCHEC : Composer ne trouve PAS la classe App\\Document\\Session. Problème dans 'composer.json' ou le nom du fichier/namespace.\n";
}