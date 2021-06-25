<?php

namespace Elabee\EventSubscriber;

use Drupal\jsonapi\ResourceType\ResourceTypeBuildEvent;
use Drupal\jsonapi\ResourceType\ResourceTypeBuildEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ResourceTypeBuildSubscriber.
 *
 * @package Elabee\EventSubscriber
 */
class ResourceTypeBuildSubscriber implements EventSubscriberInterface {

  /**
   * Constructs \Elabee\EventSubscriber\ResourceTypeBuildSubscriber object.
   *
   * @param array $enabledResourceTypes
   *   Enabled resource types.
   */
  public function __construct(protected array $enabledResourceTypes) {
  }

  /**
   * {@inheritDoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      ResourceTypeBuildEvents::BUILD => ['onResourceTypeBuild'],
    ];
  }

  /**
   * Subscribes to \Drupal\jsonapi\ResourceType\ResourceTypeBuildEvent event.
   *
   * @param \Drupal\jsonapi\ResourceType\ResourceTypeBuildEvent $event
   *   The subscribed event.
   */
  public function onResourceTypeBuild(ResourceTypeBuildEvent $event): void {
    if (!in_array($event->getResourceTypeName(), $this->enabledResourceTypes, TRUE)) {
      $event->disableResourceType();
    }
  }

}
