<?php

namespace Drupal\elabee_next;

use Drupal\Core\Entity\EntityInterface;
use Drupal\next\NextEntityTypeManagerInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

/**
 * DeployHook service.
 */
class DeployHook {

  /**
   * Constructs a DeployHook object.
   *
   * @param \GuzzleHttp\ClientInterface $httpClient
   *   The HTTP client.
   * @param \Psr\Log\LoggerInterface $logger
   *   The Logger channel service.
   * @param \Drupal\next\NextEntityTypeManagerInterface $nextEntityTypeManager
   *   The Next Entity Type Manager service.
   */
  public function __construct(
    protected ClientInterface $httpClient,
    protected LoggerInterface $logger,
    protected NextEntityTypeManagerInterface $nextEntityTypeManager,
  ) {
  }

  /**
   * Method description.
   */
  public function trigger(EntityInterface $entity): void {
    $sites = $this->nextEntityTypeManager->getConfigForEntityType($entity->getEntityTypeId(), $entity->bundle())
        ?->getSiteResolver()
        ?->getSitesForEntity($entity)
      ?? [];

    foreach ($sites as $site) {
      if ($deployHook = $site->get('deploy_hook')) {
        try {
          $this->httpClient->request('POST', $deployHook);
        }
        catch (GuzzleException $e) {
          $this->logger->error($e->getMessage());
        }
      }
    }
  }

}
