<?php

namespace app\Libs;

use Google_Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request;


class HttpRequestAPI
{
	const SCOPES = ['https://www.googleapis.com/auth/classroom.course-work.readonly','https://www.googleapis.com/auth/classroom.courses','https://www.googleapis.com/auth/classroom.courses.readonly','https://www.googleapis.com/auth/classroom.coursework.me','https://www.googleapis.com/auth/classroom.coursework.me.readonly','https://www.googleapis.com/auth/classroom.coursework.students','https://www.googleapis.com/auth/classroom.coursework.students.readonly','https://www.googleapis.com/auth/classroom.profile.emails','https://www.googleapis.com/auth/classroom.profile.photos','https://www.googleapis.com/auth/classroom.rosters','https://www.googleapis.com/auth/classroom.rosters.readonly','https://www.googleapis.com/auth/classroom.student-submissions.me.readonly','https://www.googleapis.com/auth/classroom.student-submissions.students.readonly','https://www.googleapis.com/auth/classroom.guardianlinks.students','https://www.googleapis.com/auth/calendar','https://www.googleapis.com/auth/calendar.readonly'];
	const CREDENTIALS = 'config.json';
	
	function __construct($adminEmail)
	{
		$this->adminEmail = $adminEmail;
		$client = new Google_Client();
		$client->setAuthConfig(self::CREDENTIALS);
		$client->setScopes(self::SCOPES);
		$client->setSubject($adminEmail);
		$this->googleClient = $client;
		$this->client = $client->authorize();
	}
	
	function send($method, $uri, $data = null)
	{
		if($data != null)
		{
			$data = ['json'=>$data];
			$response = $this->client->request($method, $uri, $data);
		}else{
			$response = $this->client->request($method, $uri);
		};
		
		return $this->parseResponse($response);
	}
	
	function batch($method, $uri, $objs)
	{
		foreach($objects as $o)
		{
			$requests[] =  new Request($method, $uri, [], Psr7\stream_for(json_encode($o)));
		};
		
		$responses = Pool::batch($this->client, $requests);
		$results = [];
		foreach($responses as $r){ $results[] = $this->parseResponse($r);	};
		return $results;
	}
	
	function del($uris)
	{
		foreach($uris as $uri)
		{
			$requests[] =  new Request('DELETE', $uri);
		};
		Pool::batch($this->client, $requests);
	}
	
	private function parseResponse($data)
	{
		return json_decode($data->getBody()->getContents());
	}
	
	function getGoogleClient()
	{
		return $this->googleClient;
	}
	
	function buildQuery($uri, $data)
	{
		return $uri.'?'.http_build_query($data);
	}
}