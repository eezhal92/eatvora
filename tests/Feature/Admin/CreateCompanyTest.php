<?php

namespace Tests\Feature\Admin;

use App\User;
use App\Office;
use App\Company;
use App\Employee;
use Tests\TestCase;
use App\Facades\RandomPassword;
use Illuminate\Support\Facades\Mail;
use App\Mail\CompanyRegistrationEmail;
use App\Mail\EmployeeRegistrationEmail;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
 * What to assert.
 */
class CreateCompanyTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp() {
        parent::setUp();

        Mail::fake();
    }

    private function validParams($overrides = [])
    {
        return array_merge([
            'company_name' => 'Traveloka',
            'company_address' => 'Wisma 77 Tower 1, 7th floor. Jl. S. Parman Kav. 77',
            'admin_name' => 'Jenny Lim',
            'admin_email' => 'jennylim@traveloka.com',
        ], $overrides);
    }

    /** @test */
    function guest_cannot_view_the_create_company_form()
    {
        $response = $this->get('/ap/companies/create');

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    /** @test */
    function non_eatvora_admin_cannot_view_the_create_company_form()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/ap/companies/create');

        $response->assertStatus(302);
        $response->assertRedirect('/');
    }

    /** @test */
    function eatvora_admin_can_view_the_create_company_form()
    {
        $eatvoraAdmin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($eatvoraAdmin)->get('/ap/companies/create');

        $response->assertStatus(200);
        $response->assertSee('Create New Company');
    }

    /** @test */
    public function create_new_company()
    {
        RandomPassword::shouldReceive('generate')->andReturn('tmp-scrt');

        $eatvoraAdmin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($eatvoraAdmin)->post('/ap/companies', [
            'company_name' => 'Traveloka',
            'company_address' => 'Wisma 77 Tower 1, 7th floor. Jl. S. Parman Kav. 77',
            'admin_name' => 'Jenny Lim',
            'admin_email' => 'jennylim@traveloka.com',
        ]);

        $company = Company::where('name', 'Traveloka')->first();
        $office = Office::where('name', 'Traveloka')->where('company_id', $company->id)->first();
        $companyAdmin = User::where('email', 'jennylim@traveloka.com')->first();

        $response->assertRedirect("/ap/companies/{$company->id}");

        $this->assertEquals('Traveloka', $company->name);
        $this->assertEquals('Traveloka', $office->name);
        $this->assertEquals('Wisma 77 Tower 1, 7th floor. Jl. S. Parman Kav. 77', $office->address);
        $this->assertTrue($office->is_main);
        $this->assertEquals('Jenny Lim', $companyAdmin->name);
        $this->assertEquals('jennylim@traveloka.com', $companyAdmin->email);
        $this->assertDatabaseHas('employees', [
            'user_id' => $companyAdmin->id,
            'office_id' => $office->id,
            'is_admin' => true,
        ]);

        Mail::assertSent(CompanyRegistrationEmail::class, function ($mail) use ($company) {
            return $mail->hasTo('jennylim@traveloka.com')
                && $mail->company->id == $company->id;
        });

        Mail::assertSent(EmployeeRegistrationEmail::class, function ($mail) use ($company, $companyAdmin) {
            return $mail->hasTo('jennylim@traveloka.com') // @fixme: Need to refactor
                && $mail->user->email == $companyAdmin->email
                && $mail->company->id === $company->id
                && $mail->password === 'tmp-scrt';
        });
    }

    /** @test */
    function company_name_is_required()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)
            ->from('/ap/companies/create')
            ->post('/ap/companies', $this->validParams([
                'company_name' => '',
            ]));

        $response->assertStatus(302);
        $response->assertRedirect('/ap/companies/create');
        $response->assertSessionHasErrors('company_name');

        $this->assertEquals(0, Company::count());
    }

    /** @test */
    function company_name_must_be_at_least_3()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)
            ->from('/ap/companies/create')
            ->post('/ap/companies', $this->validParams([
                'company_name' => 'ad',
            ]));

        $response->assertStatus(302);
        $response->assertRedirect('/ap/companies/create');
        $response->assertSessionHasErrors('company_name');

        $this->assertEquals(0, Company::count());
        $this->assertEquals(0, Office::count());
    }

    /** @test */
    function company_address_is_required()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)
            ->from('/ap/companies/create')
            ->post('/ap/companies', $this->validParams([
                'company_address' => '',
            ]));

        $response->assertStatus(302);
        $response->assertRedirect('/ap/companies/create');
        $response->assertSessionHasErrors('company_address');

        $this->assertEquals(0, Company::count());
        $this->assertEquals(0, Office::count());
    }

    /** @test */
    function company_address_must_be_at_least_6()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)
            ->from('/ap/companies/create')
            ->post('/ap/companies', $this->validParams([
                'company_address' => 'Jogja',
            ]));

        $response->assertStatus(302);
        $response->assertRedirect('/ap/companies/create');
        $response->assertSessionHasErrors('company_address');

        $this->assertEquals(0, Company::count());
        $this->assertEquals(0, Office::count());
    }

     /** @test */
    function admin_name_is_required()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)
            ->from('/ap/companies/create')
            ->post('/ap/companies', $this->validParams([
                'admin_name' => '',
            ]));

        $response->assertStatus(302);
        $response->assertRedirect('/ap/companies/create');
        $response->assertSessionHasErrors('admin_name');

        $this->assertEquals(0, Company::count());
        $this->assertEquals(0, Office::count());
        $this->assertEquals(0, User::where('is_admin', false)->count());
        $this->assertEquals(0, Employee::count());
    }

    /** @test */
    function admin_name_must_be_at_least_3()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)
            ->from('/ap/companies/create')
            ->post('/ap/companies', $this->validParams([
                'admin_name' => 'Dw',
            ]));

        $response->assertStatus(302);
        $response->assertRedirect('/ap/companies/create');
        $response->assertSessionHasErrors('admin_name');

        $this->assertEquals(0, Company::count());
        $this->assertEquals(0, Office::count());
        $this->assertEquals(0, User::where('is_admin', false)->count());
        $this->assertEquals(0, Employee::count());
    }

    /** @test */
    function admin_email_must_be_required()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)
            ->from('/ap/companies/create')
            ->post('/ap/companies', $this->validParams([
                'admin_email' => '',
            ]));

        $response->assertStatus(302);
        $response->assertRedirect('/ap/companies/create');
        $response->assertSessionHasErrors('admin_email');

        $this->assertEquals(0, Company::count());
        $this->assertEquals(0, Office::count());
        $this->assertEquals(0, User::where('is_admin', false)->count());
        $this->assertEquals(0, Employee::count());
    }

    /** @test */
    function admin_email_must_be_type_of_email()
    {
        $this->withExceptionHandling();

        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)
            ->from('/ap/companies/create')
            ->post('/ap/companies', $this->validParams([
                'admin_email' => 'not_valid_email',
            ]));

        $response->assertStatus(302);
        $response->assertRedirect('/ap/companies/create');
        $response->assertSessionHasErrors('admin_email');

        $this->assertEquals(0, Company::count());
        $this->assertEquals(0, Office::count());
        $this->assertEquals(0, User::where('is_admin', false)->count());
        $this->assertEquals(0, Employee::count());
    }
}
