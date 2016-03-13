<?php

/**
 * Email address update request
 * Send this message to the old email address with a link to complete the process of new email address
 * $entity = The account owner
 * $newEmailAddress = The New Email Address
 * $code = The Code
 *
 * /account/email/update-request?e=email&c=$code
 * zbase_url_from_route('account', ['action' => 'email','task' => 'update-request','e' => $newEmailAddress,'c' => $code])
 */