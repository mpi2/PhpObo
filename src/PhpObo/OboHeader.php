<?php

namespace PhpObo;

class OboHeader extends OboTVPair
{
    const FORMAT_VERSION_1_0 = '1.0';
    const FORMAT_VERSION_1_2 = '1.2';

    public function setFormatVersion($version)
    {
        $this->tvPairs['format-version'] = $version;
    }
}
