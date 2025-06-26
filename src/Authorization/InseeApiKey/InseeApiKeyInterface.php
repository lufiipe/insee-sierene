<?php

namespace LuFiipe\InseeSierene\Authorization\InseeApiKey;

/**
 * HTTP Authorization request header interface
 */
interface InseeApiKeyInterface
{
  /**
   * Set the INSEE Sirene API authentication key
   *
   * @param string $apiKey
   * @return void
   */
  public function setApiKey(string $apiKey): void;

  /**
   * Returns the INSEE Sirene API authentication key
   *
   * @return string|null
   */
  public function getApiKey(): ?string;
}
