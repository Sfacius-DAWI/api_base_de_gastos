<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Expense;

class ExpenseControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        // Crear un usuario de prueba
        $this->user = User::factory()->create();
    }

    // Test para listar gastos filtrando por categoría
    public function testIndexReturnsExpenses()
    {
        // ...crear datos de prueba...
        Expense::factory()->create(['user_id' => $this->user->id, 'category' => 'comestibles']);
        
        $response = $this->actingAs($this->user, 'api')->getJson('/api/expenses?category=comestibles');
        
        $response->assertStatus(200);
        $response->assertJsonFragment(['category' => 'comestibles']);
    }

    // Test para crear un nuevo gasto
    public function testStoreCreatesExpense()
    {
        $data = [
            'amount' => 100.0,
            'category' => 'utilidades',
            'description' => 'Test expense',
        ];

        $response = $this->actingAs($this->user, 'api')->postJson('/api/expenses', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('expenses', array_merge($data, ['user_id' => $this->user->id]));
    }

    // Test para mostrar un gasto específico
    public function testShowExpense()
    {
        $expense = Expense::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'api')->getJson('/api/expenses/' . $expense->id);

        $response->assertStatus(200);
        $response->assertJson(['id' => $expense->id]);
    }

    // Test para actualizar un gasto existente
    public function testUpdateExpense()
    {
        $expense = Expense::factory()->create(['user_id' => $this->user->id, 'amount' => 50.0]);
        $data = ['amount' => 150.0];

        $response = $this->actingAs($this->user, 'api')->putJson('/api/expenses/' . $expense->id, $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('expenses', ['id' => $expense->id, 'amount' => 150.0]);
    }

    // Test para eliminar un gasto
    public function testDestroyExpense()
    {
        $expense = Expense::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user, 'api')->deleteJson('/api/expenses/' . $expense->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('expenses', ['id' => $expense->id]);
    }
}
