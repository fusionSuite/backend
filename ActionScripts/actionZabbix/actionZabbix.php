<?php
/**
 * FusionSuite - Backend
 * Copyright (C) 2022 FusionSuite
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace ActionScripts\actionZabbix;

include __DIR__.'/vendor/autoload.php';

use IntelliTrend\Zabbix\ZabbixApi;

// See https://github.com/intellitrend/zabbixapi-php

class actionZabbix
{

  static function addHost($args)
  {

    // Validate the data format
    $dataFormat = [
      'action.zabbix.apiconfiguration' => 'required|type:object',
      'hostname'                       => 'required|type:string',
      'action.zabbix.groupid'          => 'required|type:integer',
      // 'action.zabbix.templates' => 'required|type:object'
    ];
    \App\v1\Common::validateData($args, $dataFormat);
    $dataFormat = [
      'url'              => 'required|type:string|url',
      'username'         => 'required|type:string',
      'password'         => 'required|type:string',
    ];
    \App\v1\Common::validateData($args->{'action.zabbix.apiconfiguration'}, $dataFormat);

    // Code to add host into Zabbix
    $zbx = new ZabbixApi();

    $zbx->login(
      $args->{'action.zabbix.apiconfiguration'}->url,
      $args->{'action.zabbix.apiconfiguration'}->username,
      $args->{'action.zabbix.apiconfiguration'}->password);

    // groupid must be mandatory and id exists

    $hostData = [
      "host" => $args->hostname,
      "interfaces" => [
        [
          "type"  => 1,
          "main"  => 1,
          "useip" => 1,
          "ip"    => "192.168.3.1",
          "dns"   => "",
          "port"  => "10050"
        ]
      ],
      "groups" => [
        [
          "groupid" => $args->{'action.zabbix.groupid'}
        ]
      ],
      "tags" => [
        [
          "tag"   => "Host name",
          "value" => $args->hostname
        ]
      ],
      // "templates" => [
      //   [
      //     "templateid" => $args->zabbixTemplateId
      //   ]
      // ],
      // "macros" => [
      //   [
      //     "macro" => "{$USER_ID}",
      //     "value" => "123321"
      //   ],
      //   [
      //     "macro" => "{$USER_LOCATION}",
      //     "value" => "0:0:0",
      //     "description" => "latitude,longitude and altitude coordinates"
      //   ]
      // ],
      "inventory_mode" => 0,
      "inventory" => [
        "macaddress_a" => "01234",
        "macaddress_b" => "56768"
      ]
    ];
    $result = $zbx->call('host.create', $hostData);

    // we return the id of the host in Zabbix
    return ["value" => $result['hostids'][0]];
  }
}
