<?php

declare(strict_types=1);

namespace App\Http\Requests\System\SystemNotification;

use App\Http\Requests\BaseFormRequest;
use App\Support\Traits\ChecksFilePermission;

/**
 * Base form request for system notification operations.
 */
abstract class SystemNotificationRequest extends BaseFormRequest
{
    use ChecksFilePermission;
}
