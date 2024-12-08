<?php

namespace VivekDhumal\GupshupSmsChannel;

use Illuminate\Support\Facades\Http;

class GupshupChannel
{
    public function send($notifiable, $notification)
    {
        $message = $notification->toGupshup($notifiable);

        if (!$message->to || !$message->message) {
            throw new \Exception('Recipient and message are required.');
        }

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

        return $response;
    }
}