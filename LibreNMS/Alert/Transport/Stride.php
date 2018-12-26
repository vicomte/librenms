<?php
/* Copyright (C) 2014 Daniel Preussker <f0o@devilcode.org>
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>. */

/**
 * API Transport
 * @author f0o <f0o@devilcode.org>
 * @copyright 2014 f0o, LibreNMS
 * @license GPL
 * @package LibreNMS
 * @subpackage Alerts
 */

namespace LibreNMS\Alert\Transport;

use LibreNMS\Interfaces\Alert\Transport;

class Stride implements Transport 
{
    public function deliverAlert($obj, $opts)
    {
  
        foreach( $opts as $tmp_api ) 
	{
		error_log("msg: " . print_r($obj));
		$xml = simplexml_load_string($obj['msg']);
		$host = $tmp_api['url'];
		$pType = ($obj['state'] == 0 ? 'success' : 'error');
		$data = $this->makeAry();
		$data['body']['content'][0]['attrs']['panelType'] = $pType;
           	if ($xml !== false) 
		{
#			$data['body']['content'][0]['content'][0]['content'][] = array(
#				'type' => 'text',
#				'text' => $this->makeMsgFromXML($xml)
#			);
			$data['body']['content'][0]['content'][0]['content'][] = array(
				'type' => 'text',
				'text' => (string)$xml->title
			);
			
			if ($xml->hostname !== false) {
				$data['body']['content'][0]['content'][0]['content'][] = array(
					'type' => 'hardBreak'
				);
				$hostString = $xml->hostname;
				if ($xml->location !== false) {
					$hostString .= ' (' . $xml->location . ')';
				}
				$data['body']['content'][0]['content'][0]['content'][] = array(
					'type' => 'text',
					'text' => $hostString
				);
			}
			if ($xml->faults !== false) {
				foreach($xml->faults->fault as $fault) {
					$data['body']['content'][0]['content'][0]['content'][] = array(
						'type' => 'hardBreak'
					);
					$data['body']['content'][0]['content'][0]['content'][] = array(
						'type' => 'text',
						'text' => (string)$fault->interface . ' (' . (string)$fault->alias . ')'
					);
				}
			}
			if ($xml->url !== false) 
			{
				$data['body']['content'][0]['content'][0]['content'][] = array(
					'type' => 'hardBreak'
				);
				$data['body']['content'][0]['content'][0]['content'][] = array(
					'type' => 'text',
					'text' => (string)$xml->url
				);
#				$data['body']['content'][0]['content'][0]['content'][0]['marks'] = array(
#					array(
#						'type' => 'link', 
#						'attrs' => array(
#							'href' => $xml->url,
#							'title' => $xml->title
#						)
#					)
#				);
			}
		} 
		else 
		{
			error_log("Problem!: " . print_r(libxml_get_last_error));
       	    		$stride_msg = strip_tags($obj['msg']);
			$data['body']['content'][0]['content'][0]['content'][0]['text'] = $stride_msg;
		}
		$alert_message = json_encode($data);
		$ary_header = array('Content-Type: application/json');
   	        if (array_key_exists('bearer',$tmp_api)) 
		{
			array_push($ary_header, 'Authorization: Bearer ' . $tmp_api['bearer']);
            	}
	   	$curl = curl_init();
       		curl_setopt($curl, CURLOPT_HTTPHEADER, $ary_header);
	       	set_curl_proxy($curl);
		curl_setopt($curl, CURLOPT_URL, $host);
   	      	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
       	    	curl_setopt($curl, CURLOPT_POST,true);
           	curl_setopt($curl, CURLOPT_POSTFIELDS, $alert_message );
            	$ret = curl_exec($curl);
   	        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
       	    	if( $code != 200 ) 
		{
           		error_log("API '$host' returned Error"); //FIXME: propper debuging
	               	error_log("Params: ".$alert_message); //FIXME: propper debuging
        	        error_log("Return: ".$ret); //FIXME: propper debuging
			return 'HTTP Status code '.$code;
	    	}
	}
	return true;
    }
	function makeMsgFromXML($xml) 
	{
		$data = '';
		if ($xml->title !== false) 
		{
			$data .= $xml->title . ' - ';
		}
		if ($xml->hostname !== false || $xml->location !== false) 
		{
			if ($xml->hostname !== false)
			{
				$data .= $xml->hostname . ' ';
			}
			if ($xml->location!== false)
			{
				$data .= '(' . $xml->location . ')';
			}
		}	
		return $data;	
	}

	function makeAry()
	{
		$data = array(
			'body' => array(
				    'version' => 1,
				    'type' => 'doc',
				    'content' => array(
						array(
						    	'type' => 'panel',
							'attrs' => array(
								'panelType' => ''
				    			),
						    	content => array(
								array(
									'type' => 'paragraph',
									'content' => array(
									)
								)
					    		)	
						)		
					)
				)
			);
		return $data;
	}
}
