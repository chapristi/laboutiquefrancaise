<?php
namespace App\Classes;
use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    private static  $apiKey = "5fa254dd87cf32f99fcf674d599d4c13";
    private static  $apiKeySecret = "";


    public function send($toEmail , $toUser , $subject , $content)
    {

        $mj = new Client(getenv(Mail::$apiKey), getenv(Mail::$apiKeySecret),true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => '',
                        'Name' => "La boutique Francaise"
                    ],
                    'To' => [
                        [
                            'Email' => $toEmail,
                            'Name' => $toUser,
                        ]
                    ],
                    'TemplateID' => 3303042,
                    'TemplateLanguage' => true,
                    'Subject' =>$subject,
                    "Variables" => [
                         "content" =>  $content,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
}
