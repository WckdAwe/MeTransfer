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
    const ERR_DB_ITEM_EXIST = 'This %1$s already exists in our database.';
    const ERR_DB_ITEM_NO_EXIST = 'This %1$s doesn\'t exist in your database.';
    const ERR_ITEM_NOT_SAME = '%1$s and %2$s are not the same.';
    const ERR_ITEM_CANT_BE_SAME = '%1$s and %2$s can\'t be the same.';
    const ERR_INCORRECT = '%1$s is incorrect.';
    const ERR_INVALID = '%1$s is invalid.';
    const ERR_LENGTH_BETWEEN = '%1$s must be between %2$d and %3$d characters.';
    const ERR_STRING_SPECIFIC = '%1$s must be one of these: %2$s.';
}