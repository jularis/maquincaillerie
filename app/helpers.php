<?php

if (!function_exists('fcfa')) {
    function fcfa(float $amount): string
    {
        return number_format($amount, 0, ',', ' ') . ' F CFA';
    }
}
