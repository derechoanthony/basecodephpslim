<?php

namespace App\Helper\Messages;

class CAuthMessages
{
    const ACCOUNT_INVALID = 'The email or password is incorrect';
    const ACCOUNT_ID = 'ID not found';
    const ACCOUNT_EXIST = 'Email already exist';
    const SUCCESS_REGISTER = 'Successfully Registered';
    const ACCOUNT_NOT_FOUND = 'Could not find your JFC Franchising Account';
    const EMAIL_NOT_SEND = 'Email not send';
    const SUCCESS_SEND = 'Email successfully sent';
    const SUCCESS_UPDATED = 'Successfully updated';
    const PASSWORD_EXIST = 'New password should be different from your last 3 passwords.';
    const INACTIVE_ACCOUNT = 'Account has not yet been verified!';
    const SUCCESS_ACTIVATED = 'Account Successfully Verified!';
    const REST_PASSWORD_LINK_EXPIRED = 'Change password link already expired';
    const REST_PASSWORD_LINK= 'Change password link still valid';
    const TOKENEXPIRED = 'Token has expired'; 
    const VERIFICATION_EXPIRED = 'Account Verification Link Expired';
    const USER_STATUS_ACTIVE = 'Active';
    const USER_STATUS_ARCHIVED = 'Archive';
    const USER_STATUS_ARCHIVED_MESSAGE = 'User does not exist';
}
