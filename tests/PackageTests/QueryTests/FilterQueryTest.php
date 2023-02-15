<?php

namespace Bakgul\LaravelQueryHelper\Tests\PackageTests\QueryTests;

use App\Models\Ability;
use App\Models\Assignment;
use App\Models\Image;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Bakgul\LaravelHelpers\Helpers\Arr;
use Bakgul\LaravelHelpers\Helpers\Str;
use Bakgul\LaravelQueryHelper\Tests\TestCase;

class FilterQueryTest extends TestCase
{
    /** @test */
    public function it_will_filter_through_pipeline()
    {
        User::factory()->count(100)->create();

        $this->assertEquals(
            User::filter(['name' => '***a***', 'email' => '***gmail***'])->get(),
            User::where('name', 'LIKE', '%a%')->where('email', 'LIKE', '%gmail%')->get()
        );
    }

    /** @test */
    public function it_will_filter_to_relational_models_through_pipeline(): void
    {
        User::factory()->count(100)->create();

        $result = User::filter([
            'email' => ['***gmail***', '***yahoo***'],
            'with' => ['roles' => ['name' => ['***a***', '***e***']]]
        ])->with('roles')->get()->map(fn ($x) => [
            'roles' => $x->roles->pluck('name')->toArray(),
            'email' => $x->email
        ]);

        $result->each(function ($x) {
            array_reduce($x['roles'], fn ($p, $c) => $p && Str::containsAll($c, ['a', 'b'], true), true);
            $this->assertTrue(Str::hasSome($x['email'], ['gmail', 'yahoo']));
        });
    }

    /** @test */
    public function it_will_filter_to_polymorphic_relations(): void
    {
        $images = Image::inRandomOrder()->limit(3)->get()->pluck('id')->toArray();

        $this->assertEquals(
            Arr::order(Post::whereHas('images', function ($q) use ($images) {
                $q->whereIn('image_id', $images);
            })->get()->pluck('id')->toArray()),
            Arr::order(Post::filter([
                'morph_many' => ['to', 'image', 'image', ...$images]
            ])->get()->pluck('id')->toArray())
        );

        $posts = Post::inRandomOrder()->limit(5)->get()->pluck('id')->toArray();

        $this->assertEquals(
            Arr::order(Image::whereHas('posts', function ($query) use ($posts) {
                $query->whereIn('imageable_id', $posts)
                    ->where('imageable_type', Post::class);
            })->get()->pluck('name')->toArray()),
            Arr::order(Image::filter([
                'morph_many' => ['by', 'post', 'image', ...$posts]
            ])->get()->pluck('name')->toArray())
        );
    }

    /** @test */
    public function it_will_filter_to_deeply_nested_relations(): void
    {
        $ability = Ability::create(['name' => 'dummy']);

        $role = Role::first();
        $role->abilities()->attach($ability->id);

        $user = User::first();
        $user->roles()->attach($role->id);

        $this->assertEquals(
            User::filter(['with' => ['roles' => ['with' => ['abilities' => ['name' => ['dummy']]]]]])->get(),
            User::whereHas('roles.abilities', fn ($q) => $q->where('name', 'dummy'))->get()
        );
    }

    /** @test */
    public function it_will_filter_with_one_level_backward_query(): void
    {
        Assignment::get()->each(fn ($x) => $x->delete());

        $role = Role::first();
        [$user1, $user2, $user3] = User::inRandomOrder()->limit(3)->get();

        $user1->roles()->attach($role->id);
        $user2->roles()->attach($role->id);
        $user3->roles()->attach($role->id);

        $user2->update(['email' => 'xxx@yyy.zzz']);

        $this->assertEquals(
            Arr::order([$user1->id, $user2->id, $user3->id]),
            Arr::order(User::filter([
                'with' => ['roles' => [
                    'with' => ['users' => ['email' => '***.zzz']]
                ]]
            ])->get()->pluck('id')->toArray())
        );
    }
}
