<?php

namespace Elabee\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Cloudflare Middleware.
 *
 * @package Elabee\Middleware
 */
class CloudflareMiddleware implements HttpKernelInterface {

  /**
   * CloudflareMiddleware constructor.
   *
   * @param \Symfony\Component\HttpKernel\HttpKernelInterface $httpKernel
   *   The decorated kernel.
   */
  public function __construct(private HttpKernelInterface $httpKernel) {
  }

  /**
   * {@inheritDoc}
   */
  public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = TRUE) {
    if ($request->headers->has('CF-Connecting-IP')) {
      $request->headers->set('X-Forwarded-For', $request->headers->get('CF-Connecting-IP'));
    }

    if ($request->headers->has('CF-Visitor')) {
      try {
        $visitor = json_decode($request->headers->get('CF-Visitor'), TRUE, 512, JSON_THROW_ON_ERROR);
        $request->headers->set('X-Forwarded-Proto', $visitor['scheme']);
        if ($visitor['scheme'] === 'https') {
          $request->headers->set('X-Forwarded-Port', 443);
        }
      }
      catch (\JsonException $e) {
      }
    }

    return $this->httpKernel->handle($request, $type, $catch);
  }

}
