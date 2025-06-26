<?php

namespace LuFiipe\InseeSierene\Parameters;

use DateTime;
use LuFiipe\InseeSierene\Exception\SireneException;
use LuFiipe\InseeSierene\Parameters\Contracts\FacetInterface;
use LuFiipe\InseeSierene\Parameters\Traits\FacetableTrait;

/**
 * Facet parameters
 */
class Facet implements FacetInterface
{
    use FacetableTrait;

    public const FACET_SETTING_WITHOUT_FIELD = '__WITHOUT_FIELD__';

    // Facets on field
    public const FACET_SETTING_FIELD = 'champ';
    public const FACET_SETTING_FIELD_DEBUT = 'debut';
    public const FACET_SETTING_FIELD_NOMBRE = 'nombre';
    public const FACET_SETTING_FIELD_MIN = 'min';
    public const FACET_SETTING_FIELD_TRI = 'tri';
    public const FACET_SETTING_FIELD_MANQUANT = 'manquant';
    public const FACET_SETTING_FIELD_TOTAL = 'total';
    public const FACET_SETTING_FIELD_MODALITE = 'modalite';
    public const FACET_SETTING_FIELD_PREFIXE = 'prefixe';

    // Facets on field with Sorting
    public const FACET_SETTING_FIELD_TRI_COUNT_DESC = 'count:desc';
    public const FACET_SETTING_FIELD_TRI_COUNT_ASC = 'count:asc';
    public const FACET_SETTING_FIELD_TRI_INDEX_DESC = 'index:desc';
    public const FACET_SETTING_FIELD_TRI_INDEX_ASC = 'index:asc';

    // Query facets
    public const FACET_SETTING_REQUETE = 'requete';

    // Facets based on range
    public const FACET_SETTING_INTERVALLE = 'intervalle';
    public const FACET_SETTING_INTERVAL_MIN = 'min';
    public const FACET_SETTING_INTERVAL_AUTRE = 'autre';
    public const FACET_SETTING_INTERVAL_AUTRE_AVANT = 'avant';
    public const FACET_SETTING_INTERVAL_AUTRE_APRES = 'apres';
    public const FACET_SETTING_INTERVAL_AUTRE_ENTRE = 'entre';
    public const FACET_SETTING_INTERVAL_AUTRE_TOUT = 'tout';
    public const FACET_SETTING_INTERVAL_INCLUSION = 'inclusion';
    public const FACET_SETTING_INTERVAL_INCLUSION_INFERIEUR = 'inferieur';
    public const FACET_SETTING_INTERVAL_INCLUSION_SUPERIEUR = 'superieur';
    public const FACET_SETTING_INTERVAL_INCLUSION_BORD = 'bord';
    public const FACET_SETTING_INTERVAL_INCLUSION_EXTERIEUR = 'exterieur';
    public const FACET_SETTING_INTERVAL_INCLUSION_TOUT = 'tout';

    /**
     * Facets on field
     * 
     * @var array<string>
     */
    private array $facetFields = [];

    /**
     * Facet settings
     *
     * @var array<string, array<string, mixed>|mixed>
     */
    private array $facetFieldsSettings = [];

    /**
     * Labels for facet names in the request
     *
     * @var array<string>
     */
    private array $facetQueriesLabels = [];

    /**
     * Query facets
     *
     * @var array<string, string>
     */
    private array $facetQueries = [];

    /**
     * Interval based Facet Name Labels
     *
     * @var array<string>
     */
    private array $facetIntervalsLabels = [];

    /**
     * Facets based on range
     *
     * @var array<mixed>
     */
    private array $facetIntervals = [];

    /**
     * Settings for range Facets
     *
     * @var array<string, array<string, int|string>>
     */
    private array $facetIntervalSettings = [];

    /**
     * Gets facets on field
     *
     * @return array<string>
     */
    public function getFacetFields(): array
    {
        return $this->facetFields;
    }

    /**
     * Add the field to "facette.champ" if it does not already exist
     *
     * @param string $field
     * @return void
     */
    private function addUniqueField(string $field): void
    {
        if (!in_array($field, $this->facetFields)) {
            $this->facetFields[] = $field;
        }
    }

    /**
     * Add facet setting
     *
     * @param string $setting Facet setting
     * @param bool|float|int|string $value Value
     * @param string|null $field Field name
     * @return self
     */
    public function addSetting(string $setting, $value, ?string $field = null): self
    {
        // Add the field to "facette.champ" if it does not already exist
        if ($field !== null) {
            $this->addUniqueField($field);
        }

        // Add setting
        switch ($setting) {
            case self::FACET_SETTING_FIELD:
                // Adds a facet field directly.
                $this->addUniqueField((string) $value);
                break;
            case self::FACET_SETTING_FIELD_DEBUT:
                $value = (int) $value;
                $this->facetFieldsSettings[$setting] = $value;
                break;
            case self::FACET_SETTING_FIELD_NOMBRE:
                $value = (int) $value;
                if ($field !== null) {
                    if (!isset($this->facetFieldsSettings[$field]) || !is_array($this->facetFieldsSettings[$field])) {
                        $this->facetFieldsSettings[$field] = [];
                    }
                    $this->facetFieldsSettings[$field][$setting] = $value;
                } else {
                    $this->facetFieldsSettings[$setting] = $value;
                }
                break;
            case self::FACET_SETTING_FIELD_MIN:
                if ($field !== null) {
                    if (!isset($this->facetFieldsSettings[$field]) || !is_array($this->facetFieldsSettings[$field])) {
                        $this->facetFieldsSettings[$field] = [];
                    }
                    $this->facetFieldsSettings[$field][$setting] = $value;
                } else {
                    $this->facetFieldsSettings[$setting] = $value;
                }
                $value = (int) $value;
                break;
            case self::FACET_SETTING_FIELD_TRI:
                switch ($value) {
                    case self::FACET_SETTING_FIELD_TRI_COUNT_DESC:
                    case self::FACET_SETTING_FIELD_TRI_COUNT_ASC:
                    case self::FACET_SETTING_FIELD_TRI_INDEX_DESC:
                    case self::FACET_SETTING_FIELD_TRI_INDEX_ASC:
                        break;
                    default:
                        throw new SireneException(sprintf(
                            'Invalid value \'%s\' provided for the facet setting \'%s\'.',
                            $value,
                            self::FACET_SETTING_FIELD_TRI
                        ));
                }
                if ($field !== null) {
                    if (!isset($this->facetFieldsSettings[$field]) || !is_array($this->facetFieldsSettings[$field])) {
                        $this->facetFieldsSettings[$field] = [];
                    }
                    $this->facetFieldsSettings[$field][$setting] = $value;
                } else {
                    $this->facetFieldsSettings[$setting] = $value;
                }
                break;
            case self::FACET_SETTING_FIELD_MANQUANT:
                $value = (bool) $value;
                if ($value === true) {
                    $this->facetFieldsSettings[$setting] = 'true';
                }
                break;
            case self::FACET_SETTING_FIELD_TOTAL:
                $value = (bool) $value;
                if ($value === true) {
                    $this->facetFieldsSettings[$setting] = 'true';
                }
                break;
            case self::FACET_SETTING_FIELD_MODALITE:
                $value = (bool) $value;
                if ($value === true) {
                    $this->facetFieldsSettings[$setting] = 'true';
                }
                break;
            case self::FACET_SETTING_FIELD_PREFIXE:
                $value = (string) $value;
                if (!empty($value)) {
                    $this->facetFieldsSettings[$setting] = $value;
                }
                break;
            default:
                throw new SireneException("The specified facet setting '{$setting}' does not exist.");
        }

        return $this;
    }

    /**
     * Add a query facet
     *
     * @param string $label Facet label
     * @param string $query Query
     * @return self
     */
    public function addQuery(string $label, string $query): self
    {
        // Adds the facet label if it does not already exist in "facette.requete"
        if (!in_array($label, $this->facetQueriesLabels)) {
            $this->facetQueriesLabels[] = $label;
        }

        // Sanitizes incorrect request input
        $query = str_replace(['facette.' . $label . '.q=', 'facette.' . $label . '.q', 'facette.' . $label], '', $query);

        // Searches for and optionally adds the facet field
        $output = preg_split('/ ( AND|OR) /', urldecode($query));
        if (is_array($output)) {
            foreach ($output as $sentence) {
                $s = trim($sentence);
                $pos = strpos($s, ':');
                if ($pos > 0) {
                    $field = substr($s, 0, $pos);
                    // Add the field to "facette.champ" if it does not already exist
                    $this->addUniqueField($field);
                }
            }

            // Add a query facet
            $this->facetQueries[$label] = $query;
        }

        return $this;
    }
    /**
     * Add a facet range
     *
     * @param string $label Facet label
     * @param integer|string $start Start value
     * @param integer|string $end End value
     * @param integer|string $step Step size
     * @return self
     */
    public function addInterval(string $label, $start, $end, $step): self
    {
        // Adds the facet label if it does not already exist in "facette.intervalle"
        if (!in_array($label, $this->facetIntervalsLabels)) {
            $this->facetIntervalsLabels[] = $label;
        }

        $this->facetIntervals[$label] = [
            'demarrage' => $start,
            'fin' => $end,
            'pas' => $step,
        ];

        return $this;
    }

    /**
     * Add a facet range setting
     *
     * @param string $setting Facet setting
     * @param int|string $value Value
     * @param string|null $field Field name
     * @return self
     */
    public function addIntervalSetting(string $setting, $value, ?string $field = null): self
    {
        // Add the field to "facette.champ" if it does not already exist
        if ($field !== null) {
            $this->addUniqueField($field);
        }

        switch ($setting) {
            case self::FACET_SETTING_INTERVAL_MIN:
                $value = (int) $value;
                break;
            case self::FACET_SETTING_INTERVAL_AUTRE:
                switch ($value) {
                    case self::FACET_SETTING_INTERVAL_AUTRE_AVANT:
                    case self::FACET_SETTING_INTERVAL_AUTRE_APRES:
                    case self::FACET_SETTING_INTERVAL_AUTRE_ENTRE:
                    case self::FACET_SETTING_INTERVAL_AUTRE_TOUT:
                        break;
                    default:
                        throw new SireneException(sprintf(
                            'The value \'%s\' for the interval facet setting \'%s\' is invalid.',
                            $value,
                            self::FACET_SETTING_INTERVAL_AUTRE
                        ));
                }
                break;
            case self::FACET_SETTING_INTERVAL_INCLUSION:
                switch ($value) {
                    case self::FACET_SETTING_INTERVAL_INCLUSION_INFERIEUR:
                    case self::FACET_SETTING_INTERVAL_INCLUSION_SUPERIEUR:
                    case self::FACET_SETTING_INTERVAL_INCLUSION_BORD:
                    case self::FACET_SETTING_INTERVAL_INCLUSION_EXTERIEUR:
                    case self::FACET_SETTING_INTERVAL_INCLUSION_TOUT:
                        break;
                    default:
                        throw new SireneException(sprintf(
                            'Invalid value \'%s\' provided for interval facet setting \'%s\'.',
                            $value,
                            self::FACET_SETTING_INTERVAL_INCLUSION
                        ));
                }
                break;
            default:
                throw new SireneException("The interval facet setting '{$setting}' does not exist.");
        }

        if ($field !== null) {
            $this->facetIntervalSettings[$field][$setting] = $value;
        } else {
            $this->facetIntervalSettings[self::FACET_SETTING_WITHOUT_FIELD][$setting] = $value;
        }

        return $this;
    }

    /**
     * Get the array representation of the facet
     * 
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $requestBody = [];

        //
        // Facets on field
        //
        if (!empty($this->facetFields)) {
            $requestBody['facette.' . self::FACET_SETTING_FIELD] = implode(',', $this->facetFields);
        }

        //
        // Facet settings
        //
        $facetKeys = [
            self::FACET_SETTING_FIELD,
            self::FACET_SETTING_FIELD_DEBUT,
            self::FACET_SETTING_FIELD_NOMBRE,
            self::FACET_SETTING_FIELD_MIN,
            self::FACET_SETTING_FIELD_TRI,
            self::FACET_SETTING_FIELD_MANQUANT,
            self::FACET_SETTING_FIELD_TOTAL,
            self::FACET_SETTING_FIELD_MODALITE,
            self::FACET_SETTING_FIELD_PREFIXE,
        ];
        foreach ($this->facetFieldsSettings as $facet => $settings) {
            if (in_array($facet, $facetKeys)) {
                $requestBody["facette.$facet"] = $settings;
            } else {
                if (is_array($settings)) {
                    foreach ($settings as $f => $v) {
                        $requestBody["facette.$facet.$f"] = $v;
                    }
                }
            }
        }

        //
        // Query Facets
        //
        if (!empty($this->facetQueriesLabels)) {
            // Facet Name Labels for query
            $requestBody['facette.' . self::FACET_SETTING_REQUETE] = implode(',', $this->facetQueriesLabels);

            foreach ($this->facetQueriesLabels as $label) {
                if (isset($this->facetQueries[$label])) {
                    $requestBody["facette.$label.q"] = $this->facetQueries[$label];
                }
            }
        }

        //
        // Facets based on range
        //
        if (!empty($this->facetIntervalsLabels)) {
            // Facet Name Labels for range
            $requestBody['facette.' . self::FACET_SETTING_INTERVALLE] = implode(',', $this->facetIntervalsLabels);

            foreach ($this->facetIntervalsLabels as $label) {
                $intervalValues = $this->facetIntervals[$label];
                if (is_array($intervalValues)) {
                    foreach ($intervalValues as $param => $value) {
                        if ($value instanceof DateTime) {
                            $value = $value->format('Y-m-d\TH:i:s\Z');
                        }
                        $requestBody["facette.$label.$param"] = $value;
                    }
                }
            }
        }

        //
        // Settings for range Facets
        //
        $facetKeys = [
            self::FACET_SETTING_INTERVAL_MIN,
            self::FACET_SETTING_INTERVAL_AUTRE,
            self::FACET_SETTING_INTERVAL_INCLUSION,
        ];
        foreach ($this->facetIntervalSettings as $facet => $settings) {
            if (in_array($facet, $facetKeys)) {
                $requestBody["facette.$facet"] = $settings;
            } else {
                foreach ($settings as $f => $v) {
                    if ($facet == self::FACET_SETTING_WITHOUT_FIELD) {
                        $requestBody["facette.$f"] = $v;
                    } else {
                        $requestBody["facette.$facet.$f"] = $v;
                    }
                }
            }
        }

        return $requestBody;
    }
}
