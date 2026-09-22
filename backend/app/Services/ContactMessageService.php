<?php
namespace App\Services;
use App\Models\ContactMessage;
class ContactMessageService {
    public function submitMessage(array $data): ContactMessage{
       return ContactMessage::create([
           'name' => $data['name'],
           'email' => $data['email'],
           'subject' => $data['subject'],
           'message' => $data['message'],
       ]);
    }
}
