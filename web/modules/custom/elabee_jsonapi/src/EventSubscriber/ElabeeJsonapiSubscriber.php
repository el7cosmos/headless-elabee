<?php

namespace Drupal\elabee_jsonapi\EventSubscriber;

use Drupal\jsonapi\ResourceType\ResourceTypeBuildEvent;
use Drupal\jsonapi\ResourceType\ResourceTypeBuildEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event subscriber for eL Abee JSON:API module.
 */
class ElabeeJsonapiSubscriber implements EventSubscriberInterface {

  /**
   * Constructs event subscriber.
   *
   * @param string[] $enabledResourceTypes
   *   The enabled resource types.
   */
  public function __construct(protected array $enabledResourceTypes) {
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      ResourceTypeBuildEvents::BUILD => ['onResourceTypeBuild'],
    ];
  }

  /**
   * JSON:API resource type construction event handler.
   *
   * @param \Drupal\jsonapi\ResourceType\ResourceTypeBuildEvent $event
   *   Resource type construction event.
   */
  public function onResourceTypeBuild(ResourceTypeBuildEvent $event): void {
    if (!in_array($event->getResourceTypeName(), $this->enabledResourceTypes, TRUE)) {
      $event->disableResourceType();
    }
  }

}
