<?php

declare(strict_types=1);

namespace Axytos\KaufAufRechnung_OXID6\ErrorReporting;

use Axytos\ECommerce\Clients\ErrorReporting\ErrorReportingClientInterface;
use Throwable;

class ErrorHandler
{
    private ErrorReportingClientInterface $errorReportingClient;

    public function __construct(ErrorReportingClientInterface $errorReportingClient)
    {
        $this->errorReportingClient = $errorReportingClient;
    }

    public function handle(Throwable $throwable): void
    {
        $this->errorReportingClient->reportError($throwable);
    }
}
