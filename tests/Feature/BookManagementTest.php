<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test to add a book.
     *
     * @return void
     */
    public function test_a_book_can_be_added_to_the_library()
    {
        // $this->withoutExceptionHandling();

        $response = $this->post('/books',[
            'title' => 'Cool Book Title',
            'author' => 'John Doe'
        ]);

        $book = Book::first();

        // $response->assertStatus(200);
        // $response->assertOk();
        $this->assertCount(1, Book::all());

        $response->assertRedirect($book->path());
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
        // $this->withoutExceptionHandling();

        $this->post('/books',[
            'title' => 'Mr',
            'author' => 'John Doe'
        ]);

        $book = Book::first();

        $response = $this->patch($book->path(), [
            'title' => 'new title',
            'author' => 'John Stains'
        ]);

        $this->assertEquals('new title', Book::first()->title);
        $this->assertEquals('John Stains', Book::first()->author);

        $response->assertRedirect($book->fresh()->path());
    }

    /** @test */
    public function a_book_can_be_deleted()
    {
        $this->post('/books',[
            'title' => 'Mr',
            'author' => 'John Doe'
        ]);

        $book = Book::first();
        $this->assertCount(1, Book::all());

        $response = $this->delete($book->path());

        $this->assertCount(0, Book::all());
        $response->assertRedirect('/books');
    }
}
