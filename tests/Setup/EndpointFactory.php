<?php
namespace Tests\Setup;
use App\Credential;
use App\Endpoint;
use App\Receiver;
use App\User;
class EndpointFactory
{
    protected $receiverCount = 0;
    protected $user;
    protected Credential $credential;
    public function withReceivers(int $count)
    {
        $this->receiverCount = $count;
        return $this;
    }
    public function withUser(User $user)
    {
        $this->user = $user;
        return $this;
    }
    public function withCredential(Credential $credential)
    {
        $this->credential = $credential;
        return $this;
    }
    public function create()
    {
        $endpoint = factory(Endpoint::class)->create([
            'user_id' => $this->user ?? factory(User::class),
            'credential_id' => $this->credential ?? factory(Credential::class)->create([
                    'user_id' => $this->user ?? factory(User::class)
                ])
        ]);
        $receivers = factory(Receiver::class, $this->receiverCount)->create([
            'user_id' => $this->user ?? factory(User::class)
        ]);
        $endpoint->receivers()->attach($receivers);
        return $endpoint;
    }
}
