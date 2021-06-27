<?php

namespace Symfony\Component\EventDispatcher;

/**
 * Stubs for \Symfony\Component\EventDispatcher\EventSubscriberInterface.
 *
 * @see \Symfony\Component\EventDispatcher\EventSubscriberInterface
 */
interface EventSubscriberInterface {

  /**
   * Returns an array of event names this subscriber wants to listen to.
   *
   * @return string[]|array[]
   *   The event names to listen to
   */
  public static function getSubscribedEvents(): array;

}
