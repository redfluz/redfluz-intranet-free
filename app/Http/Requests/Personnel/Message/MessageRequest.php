<?php

declare(strict_types=1);

namespace App\Http\Requests\Personnel\Message;

use App\Http\Requests\BaseFormRequest;
use App\Support\Traits\ChecksFilePermission;

/**
 * The fields of a self-service message, named.
 *
 * Sending a message asks for the message body, an optional attachment, and an
 * optional parent message for threading. The concrete request states its
 * rules in full so it can be read independently.
 */
abstract class MessageRequest extends BaseFormRequest
{
    use ChecksFilePermission;
}
