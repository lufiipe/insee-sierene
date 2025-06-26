<?php

namespace LuFiipe\InseeSierene;

use LuFiipe\InseeSierene\Exception\SireneException;
use LuFiipe\InseeSierene\Parameters\SearchParameters;
use LuFiipe\InseeSierene\Parameters\SuccessionLinksParameters;
use LuFiipe\InseeSierene\Parameters\UnitParameters;
use LuFiipe\InseeSierene\Request\Request;
use LuFiipe\InseeSierene\Response\Collection;
use LuFiipe\InseeSierene\Response\Response;
use LuFiipe\InseeSierene\Utils\Insee;

/**
 * Insee Sirene Client
 */
class Sirene extends InseeAbstract
{
    // Sirene API version
    public const SIRENE_API_VERSION = '3.11';

    // Sirene API url
    public const URL_SIRENE_API = 'https://api.insee.fr/api-sirene/';

    /**
     * Returns the API version number
     *
     * @return string
     */
    public function getVersion(): string
    {
        return self::SIRENE_API_VERSION;
    }

    /**
     * Returns the API sirene base url
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return self::URL_SIRENE_API . $this->getVersion();
    }

    /**
     * Search for a legal entity by its SIREN number
     *
     * @param string $siren SIREN number
     * @param UnitParameters|null $parameters Unit parameters
     * @return Response
     */
    public function siren(string $siren, ?UnitParameters $parameters = null): Response
    {
        if (!Insee::isValidSiren($siren)) {
            throw new SireneException("Invalid SIREN number: '{$siren}'.");
        }

        // Send the API request
        return $this->requestElement(
            new Request(
                Request::METHOD_GET,
                $this->getBaseUrl(),
                '/siren/' . trim($siren),
                $parameters
            )
        );
    }

    /**
     * Advanced search for legal units
     *
     * @param SearchParameters $parameters Advanced search parameters
     * @return Collection INSEE Sirene response collection
     */
    public function searchLegalUnits(SearchParameters $parameters): Collection
    {
        // Send the API request
        return $this->requestCollection(
            new Request(
                Request::METHOD_POST,
                $this->getBaseUrl(),
                '/siren',
                $parameters
            )
        );
    }

    /**
     * Search for an establishment by its SIRET number
     *
     * @param string $siret Establishment number
     * @param UnitParameters|null $parameters Unit parameters
     * @return Response Insee Siren response
     */
    public function siret(string $siret, ?UnitParameters $parameters = null): Response
    {
        if (!Insee::isValidSiret($siret)) {
            throw new SireneException("Invalid SIRET number: '{$siret}'.");
        }

        // Send the API request
        return $this->requestElement(
            new Request(
                Request::METHOD_GET,
                $this->getBaseUrl(),
                '/siret/' . trim($siret),
                $parameters
            )
        );
    }

    /**
     * Advanced search of establishments
     *
     * @param SearchParameters $parameters Advanced search parameters
     * @return Collection INSEE Sirene response collection
     */
    public function searchEstablishments(SearchParameters $parameters): Collection
    {
        // Send the API request
        return $this->requestCollection(
            new Request(
                Request::METHOD_POST,
                $this->getBaseUrl(),
                '/siret',
                $parameters
            )
        );
    }

    /**
     * Advanced search on succession links
     *
     * @param SuccessionLinksParameters $parameters Succession links parameters
     * @return Collection INSEE Sirene response collection
     */
    public function searchEstablishmentsSuccessions(SuccessionLinksParameters $parameters): Collection
    {
        // Send the API request
        return $this->requestCollection(
            new Request(
                Request::METHOD_GET,
                $this->getBaseUrl(),
                '/siret/liensSuccession',
                $parameters
            )
        );
    }

    /**
     * Service status, update dates, and version number
     *
     * @return Response Insee Siren response
     */
    public function informations(): Response
    {
        // Send the API request
        return $this->requestElement(
            new Request(
                Request::METHOD_GET,
                $this->getBaseUrl(),
                '/informations'
            )
        );
    }
}
