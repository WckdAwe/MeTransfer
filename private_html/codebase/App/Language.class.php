<?php
/**
 * Created by PhpStorm.
 * User: WICKED
 * Date: 8/12/2018
 * Time: 6:09 μμ
 */

namespace codebase\App;


class Language
{
    const TOS_ACCEPT = 'You must accept the Terms of Use to continue.';
    const ERR_USERNAME = 'Username must be between 5 and 32 characters.';
    const ERR_USERNAME_EXISTS = 'Field `username` already exists!';
    const ERR_USERNAME_NOT_EXIST = 'This `username` doesn\'t exist!';
    const ERR_PASSWORD = 'Password must be between 5 and 32 characters.';
    const ERR_PASSWORD_INCORRECT = 'Incorrect password entered.';
    const ERR_EMAIL = 'Email is not valid.';
    const ERR_EMAIL_EXISTS = 'Field `email` already exists';
}