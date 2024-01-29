<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class MailContact
{
  private $api_key = 'your api key here';
  private $api_key_secret = 'your api key here';

  public function send($from_email, $from_name, $subject, $content)
  {
    $mj = new Client($this->api_key, $this->api_key_secret, true, ['version' => 'v3.1']);

    $body = [
      'Messages' => [
        [
          'From' => [
            'Email' => $from_email,
            'Name' => $from_name
          ],
          'To' => [
            [
              'Email' => "meneghettimarcos@outlook.com",
              'Name' => "LabouTik"
            ]
          ],
          'TemplateID' => 5593534,
          'TemplateLanguage' => true,
          'Subject' => $subject,
          'Variables' => [
            'content' => $content,
          ]
        ]
      ]
    ];
    $response = $mj->post(Resources::$Email, ['body' => $body]);
    $response->success();
  }
}
