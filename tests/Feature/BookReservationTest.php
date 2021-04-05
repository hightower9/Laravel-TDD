<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test to add a book.
     *
     * @return void
     */
    public function test_a_book_can_be_added_to_the_library()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/books',[
            'title' => 'Cool Book Title',
            'author' => 'John Doe'
        ]);

        // $response->assertStatus(200);
        $response->assertOk();
        $this->assertCount(1, Book::all());
    }

    /**
     * A basic test to add a book.
     *
     * @return void
     */
    public function test_a_title_is_required()
    {
        // $this->withoutExceptionHandling();

        $response = $this->post('/books',[
            'title' => '',
            'author' => 'John Doe'
        ]);

        $response->assertSessionHasErrors('title');
    }

    /**
     * A basic test to add a book.
     *
     * @return void
     */
    public function test_a_author_is_required()
    {
        $response = $this->post('/books',[
            'title' => 'Mr',
            'author' => ''
        ]);

        $response->assertSessionHasErrors('author');
    }

    /** @test */
    public function a_book_can_be_updated()
    {
        $this->withoutExceptionHandling();

        $this->post('/books',[
            'title' => 'Mr',
            'author' => 'John Doe'
        ]);

        $book = Book::first();

        $response = $this->patch('/books/'. $book->id, [
            'title' => 'new title',
            'author' => 'John Stains'
        ]);

        $this->assertEquals('new title', Book::first()->title);
        $this->assertEquals('John Stains', Book::first()->author);
    }
}
