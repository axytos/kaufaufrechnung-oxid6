<?php

namespace Axytos\KaufAufRechnung_OXID6\Adapter\HashCalculation;

class SHA256HashAlgorithm implements HashAlgorithmInterface
{
    /**
     * @param string $input
     *
     * @return string
     */
    public function compute($input)
    {
        $input = (string) $input;

        return hash('sha256', $input);
    }
}
