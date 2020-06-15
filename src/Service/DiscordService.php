<?php

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;

class DiscordService
{
    public function getMembers()
    {
        try {
            $client = HttpClient::create([
                'headers' => [
                    'Authorization' => 'Bot NzA0NzcyNzg3NjE2ODc0NjE3.XuaJDA.FPcViF2bS0X4QfSBNwK50sHVWFI'
                ]
            ]);
            $response = $client->request('GET', 'https://discordapp.com/api/guilds/683627920366764046/members?limit=1000');
            // $content = $response->getContent();
            return $response->toArray();
        } catch (\Exception $ex) {
            return [];
        }
    }

    public function countMembers()
    {
        return count($this->getMembers());
    }
}
