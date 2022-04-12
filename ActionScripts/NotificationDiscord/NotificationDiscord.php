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

namespace ActionScripts\NotificationDiscord;

use Woeler\DiscordPhp\Message\DiscordTextMessage;
use Woeler\DiscordPhp\Webhook\DiscordWebhook;

class NotificationDiscord
{
  public function sendMessage($args)
  {
    // Validate the data format
    $dataFormat = [
      'actionscript.discord.configuration' => 'required|type:object',
      'actionscript.discord.username'      => 'present|type:string',
      'actionscript.discord.message'       => 'required|type:string',
    ];
    \App\v1\Common::validateData($args, $dataFormat);
    $dataFormat = [
      'webhooktoken' => 'required|type:string',
      'username'     => 'required|type:string',
    ];
    \App\v1\Common::validateData($args->{'actionscript.discord.configuration'}, $dataFormat);

    $username = $args->{'actionscript.discord.configuration'}->username;
    if (!empty($args->{'actionscript.discord.username'}))
    {
      $username = $args->{'actionscript.discord.username'};
    }
    $message = (new DiscordTextMessage())
      ->setContent($args->message)
      ->setUsername($username);

    $webhook = new DiscordWebhook('https://discordapp.com/api/webhooks/' .
               $args->{'actionscript.discord.configuration'}->webhooktoken);
    $webhook->send($message);
    return [];
  }
}
