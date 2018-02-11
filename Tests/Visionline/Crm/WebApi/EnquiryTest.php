<?php

namespace Tests\Visionline\Crm\WebApi;

use PHPUnit\Framework\TestCase;
use Tests\AssertAttributesTrait;
use Visionline\Crm\WebApi\Enquiry;

class EnquiryTest extends TestCase
{
    use AssertAttributesTrait;

    public function testPublicAttributes()
    {
        $enquiry = new Enquiry();

        $this->assertPublicAttributes(
            $enquiry,
            [
                'anrede',
                'titel',
                'nachname',
                'vorname',
                'firma',
                'geschlecht',
                'strasse',
                'postfach',
                'stiege',
                'stock',
                'top',
                'plz',
                'ort',
                'landISO',
                'telefon',
                'telefonPrivat',
                'faxGeschaeftlich',
                'mobiltelefon',
                'eMail',
                'webseite',
                'werbungAb',
                'anfragequelle',
                'datum',
                'objektNr',
                'objektId',
                'projektNr',
                'projektId',
                'gesamtflaecheVon',
                'gesamtflaecheBis',
                'gesamtbelastungMieteVon',
                'gesamtbelastungMieteBis',
                'kaufpreisVon',
                'kaufpreisBis',
                'grundflaecheVon',
                'grundflaecheBis',
                'wohnflaecheVon',
                'wohnflaecheBis',
                'zimmerVon',
                'zimmerBis',
                'vermarktungsarten',
                'objektarten',
                'objekttypen',
                'regionen',
                'anmerkung',
                'erwerbsarten',
            ]
        );
    }
}
