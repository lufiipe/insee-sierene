<?php

namespace LuFiipe\InseeSierene\Authorization\InseeApiKey;

use LuFiipe\InseeSierene\Authorization\AuthorizationRequestHeaderInterface;

/**
 * Insee HTTP Authorization request header
 */
class InseeApiKey implements AuthorizationRequestHeaderInterface, InseeApiKeyInterface
{
	/**
	 * INSEE Sirene API authentication key
	 *
	 * @var string
	 */
	private string $apiKey;

	/**
	 * Construct Insee HTTP Authorization
	 *
	 * @param string $apiKey INSEE Sirene API authentication key
	 */
	public function __construct(string $apiKey = '')
	{
		$this->setApiKey((string) $apiKey);
	}

	/**
	 * Return the HTTP Authorization request header
	 *
	 * @return array<string, ?string>
	 */
	public function getHeaderAuthorization(): array
	{
		return ['X-INSEE-Api-Key-Integration' => $this->getApiKey()];
	}

	/**
	 * Set the INSEE Sirene API authentication key
	 *
	 * @param string $apiKey INSEE Sirene API authentication key
	 * @return void
	 */
	public function setApiKey(string $apiKey): void
	{
		$this->apiKey = $apiKey;
	}

	/**
	 * Returns the INSEE Sirene API authentication key
	 *
	 * @return string|null
	 */
	public function getApiKey(): ?string
	{
		return $this->apiKey;
	}
}
