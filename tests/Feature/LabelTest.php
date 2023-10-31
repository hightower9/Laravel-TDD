<?php

namespace Tests\Feature;

use App\Models\Label;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        
        $this->authUser();
    }

    public function test_fetch_all_labels(): void
    {
        $label = $this->createLabel();

        $response = $this->getJson(route('labels.index'))->assertOk()->json('data');

        $this->assertEquals(1, count($response));
        $this->assertEquals($response[0]['title'], $label->title);
        $this->assertEquals($response[0]['id'], $label->id);
    }

    public function test_user_can_create_a_new_label(): void
    {
        $label = Label::factory()->raw();
        $res = $this->postJson(route('labels.store'), $label)
            ->assertCreated()->json('data');

        $this->assertEquals($label['title'], $res['title']);

        $this->assertDatabaseHas('labels', [
            'title' => $label['title']
        ]);
    }

    public function test_user_can_update_a_label(): void
    {
        $label = $this->createLabel();
        $new_data = ['title' => 'updated title', 'color' => '#000'];
        $this->patchJson(route('labels.update', $label->id), $new_data)
            ->assertOk()->json('data');

        $this->assertDatabaseHas('labels', $new_data);
    }

    public function test_user_can_delete_a_label(): void
    {
        $label = $this->createLabel();

        $this->deleteJson(route('labels.destroy', $label->id))
            ->assertNoContent();

        $this->assertDatabaseMissing('labels', ['title' => $label->title]);
    }
}
