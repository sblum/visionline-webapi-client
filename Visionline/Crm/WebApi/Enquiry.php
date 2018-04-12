<?php
/**
 * Defines the class Enquiry.
 */

namespace Visionline\Crm\WebApi;

/**
 * Describes an enquiry to create in CRM-VISIONLINE.
 */
class Enquiry
{
    /**
     * Anrede des Kontakts.
     *
     * @var string
     */
    public $anrede;

    /**
     * Titel des Kontakts.
     *
     * @var string
     */
    public $titel;

    /**
     * Nachname des Kontakts.
     *
     * @var string
     */
    public $nachname;

    /**
     * Vorname des Kontakts.
     *
     * @var string
     */
    public $vorname;

    /**
     * Firma des Kontakts.
     *
     * @var string
     */
    public $firma;

    /**
     * Geschlecht des Kontakts.
     *
     * @var string
     */
    public $geschlecht;

    /**
     * Straße (Adresse).
     *
     * @var string
     */
    public $strasse;

    /**
     * Postfach (Adresse).
     *
     * @var string
     */
    public $postfach;

    /**
     * Stiege (Adresse).
     *
     * @var string
     */
    public $stiege;

    /**
     * Stock (Adresse).
     *
     * @var string
     */
    public $stock;

    /**
     * Top (Adresse).
     *
     * @var string
     */
    public $top;

    /**
     * Postleitzahl (Adresse).
     *
     * @var string
     */
    public $plz;

    /**
     * Bezeichnung des Ortes (Adresse).
     *
     * @var string
     */
    public $ort;

    /**
     * 2-stelliges ISO-Länderkürzel (Adresse).
     *
     * @var string
     */
    public $landISO;

    /**
     * Telefonnummer des Kontakts.
     *
     * @var string
     */
    public $telefon;

    /**
     * Telefonnummer (privat) des Kontakts.
     *
     * @var string
     */
    public $telefonPrivat;

    /**
     * Faxnummer (geschäftlich) des Kontakts.
     *
     * @var string
     */
    public $faxGeschaeftlich;

    /**
     * Mobil-Nummer des Kontakts.
     *
     * @var string
     */
    public $mobiltelefon;

    /**
     * E-Mail-Adresse des Kontakts.
     *
     * @var string
     */
    public $eMail;

    /**
     * Webseite des Kontakts.
     *
     * @var string
     */
    public $webseite;

    /**
     * Datum, ab dem Werbung erlaubt ist. Null, wenn keine Werbung erlaubt ist.
     *
     * @var int
     */
    public $werbungAb;

    /**
     * Gibt an, woher die Anfrage stammt (z.b. "Kontaktformular auf Website"). Die angegebene Anfragequelle
     * muss im System angelegt sein, damit der Wert übernommen werden kann.
     *
     * @var string
     */
    public $anfragequelle;

    /**
     * Datum der Anfrage als Unix Timestamp. Wird kein Datum angegeben, wird das aktuelle Datum verwendet.
     *
     * @var int
     */
    public $datum;

    /**
     * Objektnummer des angefragten Objekts.
     *
     * @var string
     */
    public $objektNr;

    /**
     * CRM-VISIONLINE-interne Objekt-ID des angefragten Objekts.
     * Es wird empfohlen, die Zuordnung zum angefragten Objekt über die ObjektNr durchzuführen.
     *
     * @var int
     */
    public $objektId;

    /**
     * Kundenspezifische Projektnummer des angefragten Projekts.
     *
     * @var string
     */
    public $projektNr;

    /**
     * CRM-VISIONLINE-interne Projekt-ID des angefragten Projekts.
     * Es wird empfohlen, die Zuordnung zum angefragten Projekt über die ProjektNr durchzuführen.
     *
     * @var string
     */
    public $projektId;

    /**
     * Untergrenze der gesuchten Gesamtfläche.
     *
     * @var int
     */
    public $gesamtflaecheVon;

    /**
     * Obergrenze der gesuchten Gesamtfläche.
     *
     * @var int
     */
    public $gesamtflaecheBis;

    /**
     * Untergrenze der gesuchten Gesamtbelastung/Miete.
     *
     * @var int
     */
    public $gesamtbelastungMieteVon;

    /**
     * Obergrenze der gesuchten Gesamtbelastung/Miete.
     *
     * @var int
     */
    public $gesamtbelastungMieteBis;

    /**
     * Untergrenze des gesuchten Kaufpreises.
     *
     * @var int
     */
    public $kaufpreisVon;

    /**
     * Obergrenze des gesuchten Kaufpreises.
     *
     * @var int
     */
    public $kaufpreisBis;

    /**
     * Untergrenze der gesuchten Grundfläche.
     *
     * @var int
     */
    public $grundflaecheVon;

    /**
     * Obergrenze der gesuchten Grundfläche.
     *
     * @var int
     */
    public $grundflaecheBis;

    /**
     * Untergrenze der gesuchten Wohnfläche.
     *
     * @var int
     */
    public $wohnflaecheVon;

    /**
     * Obergrenze der gesuchten Wohnfläche.
     *
     * @var int
     */
    public $wohnflaecheBis;

    /**
     * Untergrenze der gesuchten Zimmeranzahl.
     *
     * @var float
     */
    public $zimmerVon;

    /**
     * Obergrenze der gesuchten Zimmeranzahl.
     *
     * @var float
     */
    public $zimmerBis;

    /**
     * OpenImmo-Bezeichnungen der gesuchten Vermarktungsarten.
     *
     * @var array of string
     */
    public $vermarktungsarten;

    /**
     * OpenImmo-Bezeichnungen der gesuchten Objektarten.
     *
     * @var array of string
     */
    public $objektarten;

    /**
     * OpenImmo-Bezeichnungen der gesuchten Objektarttypen.
     *
     * @var array of string
     */
    public $objekttypen;

    /**
     * Bezeichnungen der gesuchten Regionen.
     *
     * @var array of string
     */
    public $regionen;

    /**
     * Notiz zur Anfrage.
     *
     * @var string
     */
    public $anmerkung;

    /**
     * Bezeichnungen der Erwerbsarten.
     *
     * @var array of string
     */
    public $erwerbsarten;
}
