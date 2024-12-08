<?php

namespace VivekDhumal\GupshupSmsChannel;

use Exception;
use Illuminate\Support\Facades\Http;

class GupshupChannel
{
    public function send($notifiable, $notification)
    {
        $message = $notification->toGupshup($notifiable);

        if (!$message->to || !$message->message) {
            throw new \Exception('Recipient and message are required.');
        }

        try {
            $response = Http::get('https://enterprise.smsgupshup.com/GatewayAPI/rest', [
                'method' => 'SendMessage',
                'send_to' => $message->to,
                'msg' => $message->message,
                'msg_type' => 'TEXT',
                'userid' => $message->userid,
                'auth_scheme' => 'plain',
                'password' => $message->password,
                'v' => '1.1',
                'format' => 'JSON',
                'mask' => $message->mask,
                'principalEntityId' => $message->entityId,
                'dltTemplateId' => $message->templateId,
            ]);

            if($response->status() >= 200) {
                $data = $response->json();

                if($data && isset($data['response'])) {
                    return new GupshupResponse($data['response']['status'], null, $data['response']['id'], $data['response']['details'], $data['response']['phone']);
                } else {
                    $arr = array_map('trim', explode('|', $response->body()));

                    if(isset($arr[2])) {
                        return new GupshupResponse('error', $arr[2]);
                    }
                }
                return new GupshupResponse('error', 'No response from api');
            } else {
                return new GupshupResponse('error', 'SMS is not sent, status code : '.$response->status());
            }
        } catch(Exception $ex) {
            return new GupshupResponse('error', 'SMS is not sent, error : '.$ex->getMessage());
        }
    }
}