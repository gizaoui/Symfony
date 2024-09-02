<?php
use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

return function (array $context)
{
    # dd(['DATABASE_URL' => $context['DATABASE_URL'], 'MAILER_DSN' => $context['MAILER_DSN']]);
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
