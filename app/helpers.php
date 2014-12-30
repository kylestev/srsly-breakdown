<?php

use MaxMind\Db\Reader;

use Srsly\Server;

function get_details($reader, $ip)
{
	if (! (ip2long($ip) !== false)) throw new \Exception('not an ip');
	return Cache::rememberForever('ip:details:' . $ip, function () use ($reader, $ip)
	{
		$details = $reader->get($ip);

		return [
			'continent' => [
				'code' => array_get($details, 'continent.code'),
				'name' => array_get($details, 'continent.names.en'),
			],
			'country' => [
				'code' => array_get($details, 'country.code'),
				'name' => array_get($details, 'country.names.en'),
			]
		];
	});
}

function get_summary()
{
	return Cache::rememberForever('aggregate-summary', function ()
	{
		$details = [];

		$servers = Server::all();
		$reader = new Reader(storage_path() . '/maxmind.mmdb');

		foreach ($servers as $server)
		{
			try
			{
				$info = get_details($reader, $server->ip);

				$country = array_get($info, 'country.name');
				$continent = array_get($info, 'continent.name');

				if (strlen($country) === 0 || strlen($continent) === 0)
				{
					continue;
				}

				$continent_count = sprintf('%s.count', $continent);

				if (!array_key_exists($continent, $details))
				{
					array_set($details, $continent, [
						'count' => 0,
						'countries' => [],
					]);
				}

				array_set($details, $continent_count, array_get($details, $continent_count) + 1);

				$country_key = sprintf('%s.countries.%s', $continent, $country);
				array_set($details, $country_key, array_get($details, $country_key, 0) + 1);
			}
			catch (\Exception $e)
			{
				continue;
			}
		}

		$reader->close();

		sort_details($details);

		return $details;
	});
}

function sort_details(&$details)
{
	function cmp($a, $b) {
		if ($a == $b) return 0;
		return -(($a < $b) ? -1 : 1);
	}

	function ccmp($a, $b) {
		return cmp($a['count'], $b['count']);
	}

	uasort($details, 'ccmp');

	foreach ($details as $cont => $value)
	{
		uasort($value['countries'], 'cmp');
		$details[$cont] = $value;
	}
}
