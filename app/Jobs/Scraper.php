<?php namespace Srsly\Jobs;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

use Srsly\Server;

class Scraper {

	function fire($job, $data)
	{
		Model::unguard();

		$cc = new \Gidlov\Copycat\Copycat;
		$cc->setCURL([
			CURLOPT_CONNECTTIMEOUT => 3,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTPHEADER, 'Content-Type: text/html; charset=iso-8859-1',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_USERAGENT => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36',
		])
		->match([
			'conn' => '/<td>(\d.+)<\/td>/',
		])
		->URLs('https://srsly.de/');

		$res = $cc->get()[0];

		if (! array_get($res, 'conn', false))
		{
			printf("failed to acquire connection details\n");
			$job->release();
		}

		list($ip, $port) = explode(':', $res['conn']);

		try
		{
			$server = Server::create([
				'ip' => $ip,
				'port' => $port,
			]);

			printf("found a new server: id: %s ip: %s port: %s\n", $server->id, $ip, $port);
		} catch (QueryException $e) {}

		$job->release();
	}

}
