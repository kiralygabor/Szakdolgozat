<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\Test;

/**
 * Messaging System Tests
 *
 * Validates the real-time messaging system: viewing conversations,
 * sending messages, marking messages as read, deleting messages,
 * and polling for new messages. Also verifies authorization guards
 * that prevent users from accessing conversations they don't belong to.
 */
class MessagingSystemTest extends TestCase
{
    use RefreshDatabase;

    private User $userA;
    private User $userB;
    private User $outsider;
    private Conversation $conversation;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();

        $this->userA    = User::factory()->create();
        $this->userB    = User::factory()->create();
        $this->outsider = User::factory()->create();

        $this->conversation = Conversation::create([
            'user_one_id' => min($this->userA->id, $this->userB->id),
            'user_two_id' => max($this->userA->id, $this->userB->id),
        ]);
    }

    // ── CONVERSATION ACCESS ──────────────────────────────────

    #[Test]
    public function guest_cannot_access_messages(): void
    {
        $response = $this->get(route('messages'));

        $response->assertRedirect(route('login'));
    }

    #[Test]
    public function authenticated_user_can_view_messages_page(): void
    {
        $this->actingAs($this->userA);

        $response = $this->get(route('messages'));

        $response->assertStatus(200);
    }

    #[Test]
    public function participant_can_view_conversation_messages(): void
    {
        Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id'       => $this->userA->id,
            'body'            => 'Hello!',
        ]);

        $this->actingAs($this->userA);

        $response = $this->getJson(route('conversations.show', $this->conversation));

        $response->assertOk();
        $response->assertJsonCount(1, 'messages');
    }

    #[Test]
    public function outsider_cannot_view_conversation(): void
    {
        $this->actingAs($this->outsider);

        $response = $this->getJson(route('conversations.show', $this->conversation));

        $response->assertForbidden();
    }

    // ── SENDING MESSAGES ─────────────────────────────────────

    #[Test]
    public function participant_can_send_a_message(): void
    {
        $this->actingAs($this->userA);

        $response = $this->postJson(route('conversations.messages.store', $this->conversation), [
            'body' => 'When can you start?',
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('messages', [
            'conversation_id' => $this->conversation->id,
            'sender_id'       => $this->userA->id,
        ]);
    }

    #[Test]
    public function outsider_cannot_send_message_to_conversation(): void
    {
        $this->actingAs($this->outsider);

        $response = $this->postJson(route('conversations.messages.store', $this->conversation), [
            'body' => 'Hacked message',
        ]);

        $response->assertForbidden();
    }

    // ── READING MESSAGES ─────────────────────────────────────

    #[Test]
    public function viewing_conversation_marks_messages_as_read(): void
    {
        $message = Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id'       => $this->userA->id,
            'body'            => 'Unread message',
            'is_read'         => false,
        ]);

        // userB reads the conversation
        $this->actingAs($this->userB);
        $this->getJson(route('conversations.show', $this->conversation));

        $this->assertTrue($message->fresh()->is_read);
    }

    // ── DELETING MESSAGES ────────────────────────────────────

    #[Test]
    public function sender_can_delete_their_own_message(): void
    {
        $message = Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id'       => $this->userA->id,
            'body'            => 'Delete me',
        ]);

        $this->actingAs($this->userA);

        $response = $this->deleteJson(route('conversations.messages.destroy', [
            'conversation' => $this->conversation,
            'message'      => $message,
        ]));

        $response->assertOk();
        $this->assertTrue($message->fresh()->is_deleted);
    }

    // ── POLLING FOR NEW MESSAGES ─────────────────────────────

    #[Test]
    public function participant_can_poll_for_new_messages(): void
    {
        Message::create([
            'conversation_id' => $this->conversation->id,
            'sender_id'       => $this->userB->id,
            'body'            => 'Initial message',
        ]);

        $this->actingAs($this->userA);

        $response = $this->getJson(route('conversations.messages.check', [
            'conversation'    => $this->conversation,
            'last_message_id' => 0,
        ]));

        $response->assertOk();
        $response->assertJsonCount(1);
    }

    // ── CONVERSATION CREATION ────────────────────────────────

    #[Test]
    public function conversation_is_created_or_found_between_two_users(): void
    {
        $convo = Conversation::findOrCreateBetween($this->userA->id, $this->userB->id);

        $this->assertEquals($this->conversation->id, $convo->id);
        $this->assertEquals(1, Conversation::count());
    }
}
