<?php
namespace Tests\Feature;
use App\Validation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class ManageValidationsTest extends TestCase
{
    use RefreshDatabase;
    public function testUserCanCreateValidations()
    {
        $this->withoutExceptionHandling();
        $user = $this->signIn();
        $this->get('/validations/create')
            ->assertOk();
        $validation = [
            'name' => 'name',
            'validation' => 'required|numeric|between:0,20'
        ];
        $response = $this->post('/validations', $validation);
        $validation['user_id'] = $user->id;
        $this->assertDatabaseHas('validations', $validation);
        $response->assertRedirect('/validations/' . Validation::where($validation)->first()->id);
        $this->get('/validations')
            ->assertSee($validation['name'])
            ->assertSee($validation['validation']);
    }
    public function testCreateValidationRulesAreValidated()
    {
        $this->signIn();
        $validations = [
            'name' => '',
            'validation' => 'unique|exists|password|active_url',
        ];
        $response = $this->post('/validations', $validations);
        $response->assertSessionHasErrors([
            'name', 'validation'
        ]);
        $this->assertDatabaseMissing('validations', $validations);
    }
    public function testUserCanViewAValidation()
    {
        $this->withoutExceptionHandling();
        $validation = factory(Validation::class)->create();
        $this->actingAs($validation->user)
            ->get('/validations/' . $validation->id)
            ->assertOk()
            ->assertSee($validation->name)
            ->assertSee($validation->validation);
    }
    public function testGuestsCannotManageValidations()
    {
        $this->get('/validations')
            ->assertRedirect('/login');
        $this->get('/validations/create')
            ->assertRedirect('/login');
        $this->get('/validations/1')
            ->assertRedirect('/login');
        $this->delete('/validations/1')
            ->assertRedirect('/login');
        $this->post('/validations')
            ->assertRedirect('/login');
    }
    public function testUsersCannotViewOthersValidations()
    {
        $this->signIn();
        $ownValidation = factory(Validation::class)->create();
        $validation = factory(Validation::class)->create();
        $this->get('/validations/' . $validation->id)
            ->assertStatus(403)
            ->assertDontSee($validation->name);
        $this->actingAs($ownValidation->user)
            ->get('/validations')
            ->assertSee($ownValidation->name)
            ->assertDontSee($validation->name);
    }
    public function testUserCanDeleteHisValidations()
    {
        $this->withoutExceptionHandling();
        $validation = factory(Validation::class)->create();
        $this->actingAs($validation->user)
            ->delete('/validations/' . $validation->id)
            ->assertRedirect('/validations');
        $this->assertDatabaseMissing('validations', [
            'id' => $validation->id
        ]);
    }
    public function testUserCannotDeleteOthersValidations()
    {
        $this->signIn();
        $validation = factory(Validation::class)->create();
        $this
            ->delete('/validations/' . $validation->id)
            ->assertStatus(403);
        $this->assertDatabaseHas('validations', [
            'id' => $validation->id
        ]);
    }
    public function testUserCanUpdateHisValidations()
    {
        $this->withoutExceptionHandling();
        $validation = factory(Validation::class)->create();
        $changedValidation = [
            'name' => 'name',
            'validation' => 'required|numeric|between:0,20|ends_with:foo'
        ];
        $path = '/validations/' . $validation->id;
        $this->actingAs($validation->user)
            ->patch($path, $changedValidation)
            ->assertRedirect($path);
        $this->assertDatabaseHas('validations', $changedValidation);
    }
    public function testUpdateValidationsIsValidated()
    {
        $validation = factory(Validation::class)->create();
        $changedValidation = [
            'name' => '',
            'validation' => 'unique|exists|password|active_url',
        ];
        $path = '/validations/' . $validation->id;
        $this->actingAs($validation->user)
            ->patch($path, $changedValidation)
            ->assertSessionHasErrors([
                'name', 'validation'
            ]);
        $this->assertDatabaseHas('validations', [
            'name' => $validation->name,
            'validation' => $validation->validation
        ]);
        $this->assertDatabaseMissing('validations', $changedValidation);
    }
    public function testUserCannotUpdateOthersValidations()
    {
        $this->signIn();
        $validation = factory(Validation::class)->create();
        $changedValidation = [
            'name' => 'changed'
        ];
        $this
            ->patch('/validations/' . $validation->id, $changedValidation)
            ->assertStatus(403);
        $this->assertDatabaseHas('validations', $validation->toArray());
        $this->assertDatabaseMissing('validations', $changedValidation);
    }
}
