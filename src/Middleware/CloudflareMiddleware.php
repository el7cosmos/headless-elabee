<?php

namespace Elabee\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
  public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = TRUE): Response {
    if ($request->headers->has('CF-Connecting-IP') && $connectingIp = $request->headers->get('CF-Connecting-IP')) {
      $request->headers->set('X-Forwarded-For', $connectingIp);
    }

    if ($request->headers->has('CF-Visitor') && $visitor = $request->headers->get('CF-Visitor')) {
      try {
        $visitor = json_decode($visitor, TRUE, 512, JSON_THROW_ON_ERROR);
        $request->headers->set('X-Forwarded-Proto', $visitor['scheme']);
        if ($visitor['scheme'] === 'https') {
          $request->headers->set('X-Forwarded-Port', '443');
        }
      }
      catch (\JsonException) {
      }
    }

    return $this->httpKernel->handle($request, $type, $catch);
  }

}
