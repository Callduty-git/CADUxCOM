<?php

return [
    // Límite máximo de reglas de descuento por empresa
    // Puedes sobrescribirlo con la variable de entorno DISCOUNT_RULES_LIMIT
    'rules_limit' => env('DISCOUNT_RULES_LIMIT', 10),
];