<?php
/**
 * Defines the class Interest.
 */

namespace Visionline\Crm\WebApi;

/**
 * Describes an interest of a contact in CRM-VISIONLINE.
 */
class Interest
{
    /**
     * ID des Interesses.
     *
     *@var int
     */
    public $id;

    /**
     * ID des Kontakts.
     *
     * @var int
     */
    public $kontaktId;

    /**
     * Anfragequelle.
     *
     * @var string
     */
    public $anfragequelle;

    /**
     * Datum, an dem das Interesse abgeschlossen wurde.
     *
     * @var DateTime
     */
    public $datumAbschluss;

    /**
     * Datum, an dem das Interesse angelegt wurde.
     *
     * @var DateTime
     */
    public $datumAnlage;

    /**
     * Erwerbsarten.
     *
     * @var array of string
     */
    public $erwerbsarten;

    /**
     * Untergrenze der gesuchten Fläche.
     *
     * @var float
     */
    public $flaecheVon;

    /**
     * Obergrenze der gesuchten Fläche.
     *
     * @var float
     */
    public $flaecheBis;

    /**
     * Geografische Regionen.
     *
     * @var array of string
     */
    public $geografischeRegionen;

    /**
     * Untergrenze der gesuchten Grundstücksfläche.
     *
     * @var float
     */
    public $grundstuecksflaecheVon;

    /**
     * Obergrenze der gesuchten Grundstücksfläche.
     *
     * @var float
     */
    public $grundstuecksflaecheBis;

    /**
     * Vermarktungsarten.
     *
     * @var array of string
     */
    public $vermarktungsarten;

    /**
     * Untergrenze der Miete.
     *
     * @var float
     */
    public $mieteVon;

    /**
     * Obergrenze der Miete.
     *
     * @var float
     */
    public $mieteBis;

    /**
     * Notiz.
     *
     * @var string
     */
    public $notiz;

    /**
     * Gesuchte Objektarten.
     *
     * @var array of string
     */
    public $objektarten;

    /**
     * Gesuchte Objekttypen.
     *
     * @var array of string
     */
    public $objekttypen;

    /**
     * Politische Regionen.
     *
     * @var array of string
     */
    public $politischeRegionen;

    /**
     * Untergrenze des Kaufpreises.
     *
     * @var float
     */
    public $preisVon;

    /**
     * Obergrenze des Kaufpreises.
     *
     * @var float
     */
    public $preisBis;

    /**
     * Status des Interesses.
     *
     * @var string
     */
    public $status;

    /**
     * Untergrenze der Wohnfläche.
     *
     * @var float
     */
    public $wohnflaecheVon;

    /**
     * Obergrenze der Wohnfläche.
     *
     * @var float
     */
    public $wohnflaecheBis;

    /**
     * Untergrenze der Zimmeranzahl.
     *
     * @var float
     */
    public $zimmerVon;

    /**
     * Obergrenze der Zimmeranzahl.
     *
     * @var float
     */
    public $zimmerBis;
}
