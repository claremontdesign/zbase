<?php

/**
 * Email address verification code
 * Send this message to verify the new email address
 * $entity = The account owner
 * $newEmailAddress = The New Email Address
 * $code = The Code
 *
 * /account/email/verify?e=email&c=$code
 * zbase_url_from_route('account', ['action' => 'email','task' => 'verify','e' => $newEmailAddress,'c' => $code])
 */