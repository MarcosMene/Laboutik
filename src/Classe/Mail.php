<?php

namespace App\Classe;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
  private $api_key = 'your api key';
  private $api_key_secret = 'your api key';

  public function send($to_email, $to_name, $subject, $content)
  {
    $mj = new Client($this->api_key, $this->api_key_secret, true, ['version' => 'v3.1']);

    $body = [
      'Messages' => [
        [
          'From' => [
            'Email' => "your email here",
            'Name' => "LabouTik"
          ],
          'To' => [
            [
              'Email' => $to_email,
              'Name' => $to_name
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
