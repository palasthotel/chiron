<?php

class publicController {

	public function actionnewsstream()
	{
		global $chiron;
		$chiron->feeds_get_all();
		$items=$chiron->items_get_by_feed('203',$chiron->feeds);
		$myfeeds=array();
		foreach($chiron->feeds as $key=>$feed)
		{
			unset($feed[0]);
			unset($feed[1]);
			unset($feed[2]);
			unset($feed[3]);
			unset($feed[4]);
			$feed['favicon']=$home.'favicon.php?url='.urlencode($feed['url']);
			$myfeeds[$feed['id']]=$feed;
		}
		$mytems=array();
		$number=1;
		foreach($items as $item) {
			unset($item[0]);
			unset($item[1]);
			unset($item[2]);
			unset($item[3]);
			unset($item[4]);
			unset($item[5]);
			unset($item[6]);
			$item['image']="http://exmachina.ws/reader/images/symbol-".$number.".jpg";
			$item['source']=$myfeeds[$item['source']];
			$mytems[]=$item;
			$number++;
			if($number==4) {
				$number=1;
			}
		}
		echo json_encode($mytems);
	}

	public function actioncron()
	{
		global $chiron;
		$chiron->perform_cron();
	}
}