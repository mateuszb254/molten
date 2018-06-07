<?php

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

class MaintenanceException extends ServiceUnavailableHttpException
{

}