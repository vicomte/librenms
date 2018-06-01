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
 * Stride API Transport
 * @author f0o <f0o@devilcode.org>
 * @copyright 2014 f0o, LibreNMS
 * @author Marc Hassman <marc@scorevision.com>
 * @copyright 2018 Marc Hassman, LibreNMS
 * @license GPL
 * @package LibreNMS
 * @subpackage Alerts
 */

foreach( $opts as $tmp_api ) {
    $host = $tmp_api['url'];
    $curl = curl_init();
    $stride_msg = strip_tags($obj['msg']);
    $color = ($obj['state'] == 0 ? '#00FF00' : '#FF0000');
    $data = array(
	'body' => array(
		'version' => 1,
		'type' => 'doc',
		'content' => array(
			array( 'type' => 'heading',
				'attrs' => array(
					'level' => 3
					),
				'content' => array(
					array(
						'type' => text,
						'text' => $obj['title']
					)
				)
			),
			array(
				'type' => 'paragraph',
				'content' => array(
					array(
						'type' => 'emoji',
						'attrs' => array(
							'shortName' => ($obj['state'] == 0 ? ':green_heart:' : ':octagonal_sign:')
						)
					),
					array(
						'type' => 'text',
						'text' => $stride_msg
					)
				)
			)
		)
	)
    );
    $alert_message = json_encode($data);
    $ary_header = array('Content-Type: application/json');
    if (array_key_exists('bearer',$tmp_api)) {
	array_push($ary_header, 'Authorization: Bearer ' . $tmp_api['bearer']);
    #    error_log("Have a bearer: " . $tmp_api['bearer']);
    }
    curl_setopt($curl, CURLOPT_HTTPHEADER, $ary_header);
    set_curl_proxy($curl);
    curl_setopt($curl, CURLOPT_URL, $host);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST,true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $alert_message );

    $ret = curl_exec($curl);
    $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if( $code != 200 ) {
        error_log("API '$host' returned Error"); //FIXME: propper debuging
        error_log("Params: ".$alert_message); //FIXME: propper debuging
        error_log("Return: ".$ret); //FIXME: propper debuging
        return 'HTTP Status code '.$code;
    }
}
return true;
