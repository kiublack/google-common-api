<?php


namespace app\Libs;

use App\Libs\HttpRequestAPI;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;


class GoogleCalendar
{
	function __construct($adminEmail)
	{
		$this->rq = new HttpRequestAPI($adminEmail);
		$this->api = new Google_Service_Calendar($this->rq->getGoogleClient());
	}
	
	function get_calendars($opts=[])
	{
		return $this->api->calendarList->listCalendarList($opts);
	}
	
	function get_events($calendar_id, $opts= [])
	{
		return $this->api->events->listEvents($calendar_id, $opts);
	}
	
	function get_event($calendar_id, $event_id, $opts=[])
	{
		return $this->api->events->get($calendar_id, $event_id, $opts);
	}
	
	function add_event($calendar_id, $data, $opts = [])
	{
		$event = new Google_Service_Calendar_Event($data);
		$event = $this->api->events->insert($calendar_id, $event, $opts);
		return $event;
	}
	
	function update_event($calendarId, $eventId, $data, $optParams = [])
	{
		$event = new Google_Service_Calendar_Event($data);
		return $this->api->events->patch($calendarId, $eventId, $event, $optParams);
	}
	
	function delete_event($calendarId, $eventId)
	{
		return $this->api->events->delete($calendarId, $eventId);
	}
	
	function event_grand_users($calendarId, $eventId, $emails, $optParams = [])
	{
		
		foreach($emails as $email)
		{
			$attendees[] = ['email'=>$email];
		};
		$data = ['attendees' => $attendees];
		$event = new Google_Service_Calendar_Event($data);
		return $this->api->events->patch($calendarId, $eventId, $event, $optParams);
	}
	
}