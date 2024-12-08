<?php

namespace VivekDhumal\GupshupSmsChannel;

class GupshupMessage
{
    public $message;
    public $to;
    public $userid;
    public $password;
    public $mask;
    public $entityId;
    public $templateId;

    public function __construct()
    {
        $this->userid = config('gupshup.userid');
        $this->password = config('gupshup.password');
        $this->mask = config('gupshup.mask');
        $this->entityId = config('gupshup.principalEntityId');
        $this->templateId = config('gupshup.dltTemplateId');
    }

    public function setTemplateId($templateId)
    {
        $this->templateId = $templateId;

        return $this;
    }

    public function to($to)
    {
        $this->to = $to;

        return $this;   
    }

    public function message($message)
    {
        $this->message = $message;

        return $this;
    }
}