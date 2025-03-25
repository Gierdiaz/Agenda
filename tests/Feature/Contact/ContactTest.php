<?php

namespace Tests\Feature;

use App\Integrations\ViaCepIntegration;
use App\Models\{Lead, User};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Mockery;

use function Pest\Laravel\{deleteJson, getJson, postJson, putJson};

uses(RefreshDatabase::class);

describe('Managing Lead Records', function () {
    beforeEach(function () {
        $this->user = User::factory()->create([
            'name'     => 'Gierdiaz',
            'email'    => 'gierdiaz@hotmail.com',
            'password' => bcrypt('password'),
        ]);

        Sanctum::actingAs($this->user);
    });

    it('can list leads', function () {
        Lead::factory()->create([
            'name'  => 'Állison Luis',
            'phone' => '21997651914',
            'email' => 'gierdiaz@hotmail.com',
            'cep'   => '23017-130',
        ]);

        getJson(route('leads.index'))
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [['id', 'name', 'phone', 'email', 'cep']],
            ]);
    });

    it('can show a lead', function () {
        $lead = Lead::factory()->create([
            'name'  => 'Állison Luis',
            'phone' => '21997651914',
            'email' => 'gierdiaz@hotmail.com',
            'cep'   => '23017-130',
        ]);

        getJson(route('leads.show', $lead->id))
            ->assertStatus(200)
            ->assertJsonFragment([
                'id'    => $lead->id,
                'name'  => $lead->name,
                'phone' => $lead->phone,
                'email' => $lead->email,
                'cep'   => $lead->cep,
            ]);

    });

    it('can search leads by cep', function () {
        Lead::factory()->create([
            'name'  => 'Állison Luis',
            'cep'   => '23017-130',
            'email' => 'gierdiaz@hotmail.com',
            'phone' => '21997651914',
        ]);

        getJson(route('leads.search', ['cep' => '23017-130']))
            ->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Állison Luis',
                'cep'  => '23017-130',
            ]);
    });

    it('can store a lead', function () {
        $data = [
            'name'  => 'Állison Luis',
            'phone' => '21997651914',
            'email' => 'gierdiaz@hotmail.com',
            'cep'   => '23017-130',
        ];

        $mock = Mockery::mock(ViaCepIntegration::class);
        $mock->shouldReceive('getAddressByCep')->andReturn((object) [
            'cep'         => '23017-130',
            'logradouro'  => 'Rua Olinto da Gama Botelho',
            'numero'      => '43',
            'complemento' => 'casa frente',
            'unidade'     => 'RJ-1001',
            'bairro'      => 'Bairro RJ',
            'localidade'  => 'Rio de Janeiro',
            'uf'          => 'RJ',
            'estado'      => 'Rio de Janeiro',
            'regiao'      => 'Sudeste',
            'ibge'        => '3304557',
            'gia'         => '456',
            'ddd'         => '21',
            'siafi'       => '6001',
        ]);

        app()->instance(ViaCepIntegration::class, $mock);

        postJson(route('leads.store'), $data)
            ->assertStatus(201)
            ->assertJson(['message' => 'Contato registrado com sucesso.']);

        $this->assertDatabaseHas('leads', $data);
    });

    it('can update a lead', function () {
        $lead = Lead::factory()->create();

        $data = [
            'name'  => 'Pâmela Barbosa',
            'phone' => '21992912611',
            'email' => 'schzimmyd@gmail.com',
            'cep'   => '21220-380',
        ];

        putJson(route('leads.update', $lead->id), $data)
            ->assertStatus(200)
            ->assertJson(['message' => 'Contato atualizado com sucesso.']);

        $this->assertDatabaseHas('leads', array_merge(['id' => $lead->id], $data));
    });

    it('can delete a lead', function () {
        $lead = Lead::factory()->create();

        deleteJson(route('leads.destroy', $lead->id))
            ->assertStatus(200)
            ->assertJson(['message' => 'Contato removido com sucesso.']);

        $this->assertSoftDeleted('leads', ['id' => $lead->id]);
    });
});
