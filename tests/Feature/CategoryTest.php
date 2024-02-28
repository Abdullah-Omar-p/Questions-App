<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Enum\Roles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    var $user = null;
    var $token = null;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->user->assignRole(Roles::DEFAULT);
        $this->token = $this->user->createToken('authToken')->plainTextToken;

    }

    //* Create a category by authenticated user
    public function testAuthenticatedUserCanCreateCategory()
    {   
        $this->withoutExceptionHandling();
        $category = Category::factory()->make([
            'user_id' => $this->user->id,
            'created_by' => $this->user->id,
        ]); 

        $image = UploadedFile::fake()->image('category.jpg');
        $category['image'] = $image;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->post(route('categories.store'), $category->toArray() );

        $response->assertStatus(Response::HTTP_CREATED);
    }

    //* Create a category by unauthenticated user
    public function testUnauthenticatedUserCannotCreateCategory()
    {   
        $this->withExceptionHandling();
        $category = Category::factory()->make([
            'user_id' => $this->user->id,
            'created_by' => $this->user->id,
        ]);

        $response = $this->post(route('categories.store'), $category->toArray());

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    //* Update a category by authenticated user
    public function testAuthenticatedUserCanUpdateCategory()
    {
        $category = Category::factory()->create([
                'user_id' => $this->user->id,
                'created_by' =>  $this->user->id,
            ]);


            $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->put(route('categories.update', $category->id), [
            'name' => 'Updated Category',
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }







}
