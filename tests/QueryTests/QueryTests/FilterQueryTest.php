<?php

namespace Bakgul\LaravelQueryHelper\Tests\QueryTests\QueryTests;

use Bakgul\LaravelHelpers\Helpers\Arr;
use BookHub\Bridge\Employees\Facades\Employees;
use BookHub\Bridge\Users\Facades\Users;
use BookHub\Pool\Abilities\Models\Ability;
use Tests\TestCase;

class FilterQueryTest extends TestCase
{
    /** @test */
    public function filters_will_be_applied_through_pipeline()
    {
        $this->createEmployees(Employees::model('Employee'));

        $this->assertEquals(
            Users::query('User')->filter(['name' => '***a***', 'email' => '***gmail***'])->get(),
            Users::query('User')->where('name', 'LIKE', '%a%')->where('email', 'LIKE', '%gmail%')->get()
        );
    }

    /** @test */
    public function filters_will_be_applied_to_relational_models_through_pipeline(): void
    {
        $this->createEmployees(Employees::model('Employee'));

        $result = Employees::query('Employee')->filter([
            'with' => ['user' => ['name' => '***a***', 'email' => ['***gmail***', '***yahoo***']]]
        ])->with('user')->get()->map(fn ($x) => [$x->user->name, $x->user->email]);

        $result->each(function ($x) {
            $this->assertTrue(str_contains($x[0], 'a') || str_contains($x[0], 'A'));
            $this->assertTrue(str_contains($x[1], 'gmail') || str_contains($x[1], 'yahoo'));
        });
    }

    /** @test */
    public function filters_will_be_applied_to_polymorphic_relations(): void
    {
        $this->createEmployees();

        $abilities = Ability::get();

        $employeeQuery = Employees::query('Employee');

        foreach ($employeeQuery->get() as $employee) {
            $employee->permissions()->attach(
                $abilities->random(10)->pluck('id')->toArray()
            );
        }

        $employees = $employeeQuery->get()->random(2)->pluck('id')->toArray();
        $abilities = $abilities->random(3)->pluck('id')->toArray();

        $this->assertEquals(
            $employeeQuery->whereHas('permissions', function ($q) use ($abilities) {
                $q->whereIn('ability_id', $abilities);
            })->get(),
            $employeeQuery->filter([
                'morph_many' => ['to', 'permission', 'ability', ...$abilities]
            ])->get()
        );

        $this->assertEquals(
            Ability::whereHas('employees', function ($query) use ($employees) {
                $query->whereIn('permissionable_id', $employees)
                    ->where('permissionable_type', Employees::model('Employee'));
            })->get(),
            Ability::filter([
                'morph_many' => ['by', 'employee', 'permission', ...$employees]
            ])->get()
        );
    }

    /** @test */
    public function filters_will_be_applied_to_deeply_nested_relations(): void
    {
        $this->createEmployees(Employees::model('Employee'));

        Users::query('User')->get()->each(function ($u) {
            $u->data()->create(['data' => [
                'city' => ['istanbul', 'ankara'][rand(0, 1)],
                'str' => [35, 36][rand(0, 1)]
            ]]);
        });

        $actual = Employees::query('Employee')
            ->whereHas('user', function ($q) {
                $q->where(
                    fn ($q) => $q->where('name', 'LIKE', '%a%')->orWhere('name', 'LIKE', '%A%')
                )->where(
                    fn ($q) => $q->where('email', 'LIKE', '%gmail%')->orWhere('email', 'LIKE', '%yahoo%')
                )->whereHas('data', function ($q) {
                    $q->where('data->city', 'istanbul')->where('data->str', 35);
                });
            })->with('user.data')->get()->map(fn ($x) => $this->convert($x));

        $expected = Employees::query('Employee')->filter([
            'with' => [
                'user' => [
                    'name' => '***a***',
                    'email' => ['***gmail***', '***yahoo***'],
                    'with' => ['data' => ['data' => ['city' => 'istanbul', 'str' => 35]]]
                ]
            ]
        ])->with('user.data')->get()->map(fn ($x) => $this->convert($x));

        $this->assertEquals($actual, $expected);
    }

    private function convert($employee): array
    {
        return [
            'name' => $employee->user->name,
            'email' => $employee->user->email,
            'city' => Arr::get($employee->user->data?->data ?? [], 'city'),
            'str' => Arr::get($employee->user->data?->data ?? [], 'str'),
        ];
    }
}
