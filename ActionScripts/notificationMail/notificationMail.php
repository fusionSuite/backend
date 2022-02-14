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

namespace ActionScripts\notificationMail;

include __DIR__.'/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class notificationMail
{

  private $types = ['information','newItemITSM'];

  function simpleNotification($args)
  {

    // Validate the data format
    $dataFormat = [
      'action.notification.smtpconfig'    => 'required|type:object',
      'title'                             => 'required|type:string',
      'message'                           => 'required|type:string',
      'itemname'                          => 'required|type:string',
      'itemid'                            => 'required|type:integer',
      'fusionsuiteurl'                    => 'required|type:string',
      'action.notification.htmltemplate'  => 'required|type:string'
    ];
    \App\v1\Common::validateData($args, $dataFormat);

    $dataFormat = [
      'action.notification.host'          => 'required|type:string',
      'username'                          => 'present|type:string',
      'password'                          => 'present|type:string',
      'tcpport'                           => 'required|type:integer|between:1,65535',
      'action.notification.encryption'    => 'required|type:string',
      'action.notification.sender.name'   => 'required|type:string',
      'action.notification.sender.email'  => 'required|type:string'
    ];
    \App\v1\Common::validateData($args->{'action.notification.smtpconfig'}, $dataFormat);

    $smtpArgs = $args->{'action.notification.smtpconfig'};

    $mail = $this->_createMailer(
      $smtpArgs->{'action.notification.host'},
      $smtpArgs->username,
      $smtpArgs->password,
      $smtpArgs->{'action.notification.encryption'},
      $smtpArgs->tcpport
    );

    try {
      //Recipients
      $mail->setFrom($smtpArgs->{'action.notification.sender.email'}, $smtpArgs->{'action.notification.sender.name'});
      $mail->addAddress('joe@example.net', 'Joe User');
      // $mail->addReplyTo('info@example.com', 'Information');
  
      //Attachments
      // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
      // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
  
      //Content
      $mail->isHTML(true);                                  //Set email format to HTML
      $mail->Subject = $args->title;

      // Load template
      $html = file_get_contents(__DIR__.'/templates/'.$args->{'action.notification.htmltemplate'}.'.html');

      // convert with mustache
      $mustache = new \Mustache_Engine;
      $html = $mustache->render($html, [
        'title'          => $args->{'title'},
        'content'        => $args->{'message'},
        'itemname'       => $args->{'itemname'},
        'itemid'         => $args->{'itemid'},
        'fusionsuiteurl' => $args->{'fusionsuiteurl'}
      ]);
      $mail->Body    = $html;

      $html2TextConverter = new \Html2Text\Html2Text($html);
      $mail->AltBody = $html2TextConverter->getText();
  
      $mail->send();
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    return [];
  }

  function newItemITSM()
  {

  }

  /********************
   * Private functions
   ********************/

  private function _createMailer($host, $username, $password, $encryption, $port)
  {
    $mail = new PHPMailer(true);

    //Server settings
    // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host = $host;
    if (!empty($username))
    {
      $mail->SMTPAuth = true;
      $mail->Username = $username;
      $mail->Password = $password;
    }
    if ($encryption == 'tls')
    {
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    }
    else if ($encryption == 'ssl')
    {
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    }
    else
    {
      // disabled
      $mail->SMTPSecure = false;
      $mail->SMTPAutoTLS = false;
    }
    $mail->Port = $port;

    return $mail;
  }
}
