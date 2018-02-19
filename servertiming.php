<?php

/**
 * @package     GZip.HTML
 * @subpackage  Server.Timing
 *
 * @copyright   Copyright (C) 2013 Thierry Bela
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

class PlgSystemServerTiming extends JPlugin {

    public function onAfterRender() {

        if (!$this->params->get('servertiming')) {

            return;
        }

		$data = $this->getTimingData();

		$header = [];

		foreach ($data['marks'] as $k => $mark) {

			$header[] = substr('00'.($k + 1), -3).'-'.preg_replace('#[^A-Za-z0-9]#', '', $mark->tip).';dur='.$mark->time; //.';memory='.$mark->memory;
		}

		$header[] = 'total;dur='.$data['totalTime']; //.';memory='.$data['totalMemory'];
		header('Server-Timing: '.implode(',', $header));        
    }
	
	/**
	 * Display profile information.
	 *
	 * @return  string
	 *
	 * @since   2.5
	 */
	protected function getTimingData()
	{
		$totalTime = 0;
	//	$totalMem  = 0;
		$marks     = array();

		foreach (JProfiler::getInstance('Application')->getMarks() as $mark)
		{
			$totalTime += $mark->time;
		//	$totalMem  += (float) $mark->memory;

			$marks[] = (object) array(
				'time'   => $mark->time,
			//	'memory' => $mark->memory,
				'tip'    => $mark->label,
			);
		}

        return [
		'totalTime' => $totalTime, 
		//'totalMemory' => $totalMem, 
		'marks' => $marks
		];
    }

}
