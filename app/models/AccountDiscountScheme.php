<?php
class AccountDiscountScheme extends \Eloquent
{
    protected $guarded = array("AccountDiscountSchemeID");

    protected $table = 'tblAccountDiscountScheme';

    protected $primaryKey = "AccountDiscountSchemeID";

    public static function checkForeignKeyById($id) {


        /** todo implement this function   */
        return false;
    }

}