<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use App\Models\Friendship;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersCanRequestFriendshipTest extends DuskTestCase
{

    use DatabaseMigrations;

    /**
     * @test
     * @throws \Throwable
     */
    public function guests_cannot_create_friendship_request()
    {

        $recipient = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($recipient) {
            $browser->visit(route('users.show', $recipient))
                ->press('@request-friendship')
                ->assertPathIs('/login');
        });
    }

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
    public function a_user_cannot_send_friendship_request_to_itself()
    {

        $user = factory(User::class)->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit(route('users.show', $user))
                    ->assertMissing('@request-friendship')
                    ->assertSee('This is me');
        });
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function senders_can_delete_accepted_friendship_request()
    {

        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'accepted'
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($sender)
                    ->visit(route('users.show', $recipient))
                    ->assertSee('Delete friend')
                    ->press('@request-friendship')
                    ->waitForText('Request friendship')
                    ->assertSee('Request friendship')
                    ->visit(route('users.show', $recipient))
                    ->assertSee('Request friendship');
        });
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function senders_cannot_delete_denied_friendship_request()
    {

        $sender = factory(User::class)->create();
        $recipient = factory(User::class)->create();

        Friendship::create([
            'sender_id' => $sender->id,
            'recipient_id' => $recipient->id,
            'status' => 'denied'
        ]);

        $this->browse(function (Browser $browser) use ($sender, $recipient) {
            $browser->loginAs($sender)
                    ->visit(route('users.show', $recipient))
                    ->assertSee('Denied request')
                    ->press('@request-friendship')
                    ->waitForText('Denied request')
                    ->assertSee('Denied request')
                    ->visit(route('users.show', $recipient))
                    ->assertSee('Denied request');
        });
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function recipient_can_accept_friendship_request()
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
                ->waitForText('You are now friends', 7)
                ->assertSee('You are now friends')
                ->visit(route('accept-friendships.index'))
                ->assertSee('You are now friends');
        });
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function recipient_can_delete_friendship_request()
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
                ->press('@deny-friendship')
                ->waitForText('Request denied')
                ->assertSee('Request denied')
                ->visit(route('accept-friendships.index'))
                ->assertSee('Request denied');
        });
    }

    /**
     * @test
     * @throws \Throwable
     */
    public function recipient_can_deny_friendship_request()
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
                ->press('@delete-friendship')
                ->waitForText('Request deleted')
                ->assertSee('Request deleted')
                ->visit(route('accept-friendships.index'))
                ->assertDontSee('Request deleted')
                ->assertDontSee($sender->name);
        });
    }






}
