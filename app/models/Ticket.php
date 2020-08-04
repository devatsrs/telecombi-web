<?php


class Ticket extends \Eloquent
{
    protected $guarded = array("ID");

    protected $table = 'tblHelpDeskTickets';

    protected $primaryKey = "ID";
	
}