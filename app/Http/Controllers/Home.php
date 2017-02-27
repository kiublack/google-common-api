<?php

namespace App\Http\Controllers;

use GoogleClassRoom;
use GoogleCalendar;


class Home extends Controller
{
	function index()
	{
		echo "<pre>";
		$api = new GoogleClassRoom('admin@duongvanba.com');
		
		
		
		print_r($api->get_classes());
	}
	

}