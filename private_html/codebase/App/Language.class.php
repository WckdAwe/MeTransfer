<?php
/**
 * Created by PhpStorm.
 * User.class: WICKED
 * Date: 8/12/2018
 * Time: 6:09 μμ
 */

namespace codebase\App;


class Language
{
    const TOS_ACCEPT = 'You must accept the Terms of Use to continue.';
    const ERR_USERNAME = 'Username must be between 5 and 32 characters.';
    const ERR_USERNAME_EXISTS = 'This username already exists in our database.';
    const ERR_USERNAME_NOT_EXIST = 'This username doesn\'t exist.';
    const ERR_PASSWORD = 'Password must be between 5 and 32 characters.';
    const ERR_PASSWORD_CHECK = 'Password and password check are not the same.';
    const ERR_PASSWORD_INCORRECT = 'Incorrect password entered.';
    const ERR_PASSWORD_SAME = 'Old and new password can\'t be the same.';
    const ERR_EMAIL = 'Email is not valid.';
    const ERR_EMAIL_EXISTS = 'This email already exists in our database.';
}