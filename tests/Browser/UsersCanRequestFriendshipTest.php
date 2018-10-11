<?php

namespace Tests\Browser;

use App\Models\Friendship;
use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanRequestFriendshipTest extends DuskTestCase
{

    use DatabaseMigrations;

    /**
     * @test
     * @throws \Throwable
     */
    public function senders_can_create_and_delete_friendship_request()
    {

        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($sender)
                    ->visit(route('users.show', $recipient))
                    ->press('@request-friendship')
                    ->waitForText('Cancel request')
                    ->assertSee('Cancel request')
                    ->visit(route('users.show', $recipient))
                    ->assertSee('Cancel request')
                    ->press('@request-friendship')
                    ->waitForText('Request friendship')
                    ->assertSee('Request friendship');
        });
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function recipient_can_accept_and_deny_friendship_request()
    {

        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($recipient)
                ->visit(route('accept-friendships.index'))
                ->assertSee($sender->name)
                ->press('@accept-friendship')
                ->waitForText('You are now friends')
                ->assertSee('You are now friends')
                ->visit(route('accept-friendships.index'))
                ->assertSee('You are now friends');
        });
    }
}
