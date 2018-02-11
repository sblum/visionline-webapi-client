<?php

namespace Tests\Visionline\Crm\WebApi;

use PHPUnit\Framework\TestCase;
use Tests\AssertAttributesTrait;
use Visionline\Crm\WebApi\Interest;

class InterestTest extends TestCase
{
    use AssertAttributesTrait;

    public function testPublicAttributes()
    {
        $interest = new Interest();

        $this->assertPublicAttributes(
            $interest,
            [
                'id',
                'kontaktId',
                'anfragequelle',
                'datumAbschluss',
                'datumAnlage',
                'erwerbsarten',
                'flaecheVon',
                'flaecheBis',
                'geografischeRegionen',
                'grundstuecksflaecheVon',
                'grundstuecksflaecheBis',
                'vermarktungsarten',
                'mieteVon',
                'mieteBis',
                'notiz',
                'objektarten',
                'objekttypen',
                'politischeRegionen',
                'preisVon',
                'preisBis',
                'status',
                'wohnflaecheVon',
                'wohnflaecheBis',
                'zimmerVon',
                'zimmerBis',
            ]
        );
    }
}
