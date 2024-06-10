<?php

namespace Axytos\KaufAufRechnung_OXID6\Adapter\HashCalculation;

interface HashAlgorithmInterface
{
    /**
     * @param string $input
     * @return string
     */
    public function compute($input);
}
