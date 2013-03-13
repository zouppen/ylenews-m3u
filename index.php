#EXTM3U
<?php
/*
YLE News broadcast M3U generator
Markus Vuorio <markus.vuorio@gmail.com>

Following example code from http://emmettbutler.com/threestegosaurusmoon/?p=955
*/

function getFeedURL($language, $channel)
{
	$channel = trim($channel);
	$language = trim($language);
	if ($channel == "") $channel = "yle-radio-suomi";
	if ($language == "") $language = "fin";
	$format = "http://areena.yle.fi/radio/uutiset/kaikki.rss?kieli=%s&kanava=%s&jarjestys=uusin";
	return sprintf($format, $language, $channel);
}

function getMedia($feed, $namefilter="Yle Uutiset:")
{
	$ret = array();

	// retrieve search results
	if($xml = simplexml_load_file($feed)) { //load xml file using simplexml
		$result[] = $xml->xpath("/rss/channel/item"); //divide feed into array elements
		
		foreach($result[0] as $items)
		{
			$items = (array)$items;
			if (strstr((string)$items["title"], $namefilter))
			{
				$enclosure = (array)$items["enclosure"];			
				$attributes = (array)$enclosure["@attributes"];
				$ret[0] = $items["title"];
				$ret[1] = $attributes["url"];
			}
			return $ret;
		}
	}
}

//TODO: Read channel and language info from GET parameters and pass them to getFeedURL
$feedurl = getFeedURL("", "");

//TODO: Get program filter string from GET parameters and pass it to getMedia
$media = getMedia($feedurl);

//TODO: See what the magical -1 is in the #EXTINF
printf("#EXTINF:-1,%s\n", $media[0]);
echo $media[1];
?>
