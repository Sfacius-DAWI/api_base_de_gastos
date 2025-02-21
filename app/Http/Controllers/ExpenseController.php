<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExpenseController extends Controller
{
    // Listar y filtrar gastos
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Expense::where('user_id', $user->id);

        if ($request->has('category')) {
            $query->where('category', $request->get('category'));
        }

        $expenses = $query->get();

        return response()->json($expenses);
    }

    // Crear un nuevo gasto
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'category' => 'required|in:comestibles,ocio,electronica,utilidades,ropa,salud,otros',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $expense = Expense::create([
            'user_id' => Auth::id(),
            'amount' => $request->get('amount'),
            'category' => $request->get('category'),
            'description' => $request->get('description'),
        ]);

        return response()->json($expense, 201);
    }

    // Mostrar un gasto específico
    public function show($id)
    {
        $expense = Expense::find($id);

        if (!$expense || $expense->user_id != Auth::id()){
            return response()->json(['error' => 'Gasto no encontrado'], 404);
        }

        return response()->json($expense);
    }

    // Actualizar un gasto
    public function update(Request $request, $id)
    {
        $expense = Expense::find($id);

        if (!$expense || $expense->user_id != Auth::id()){
            return response()->json(['error' => 'Gasto no encontrado'], 404);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'sometimes|required|numeric',
            'category' => 'sometimes|required|in:comestibles,ocio,electronica,utilidades,ropa,salud,otros',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(), 400);
        }

        $expense->update($request->only(['amount', 'category', 'description']));

        return response()->json($expense);
    }

    // Eliminar un gasto
    public function destroy($id)
    {
        $expense = Expense::find($id);

        if (!$expense || $expense->user_id != Auth::id()){
            return response()->json(['error' => 'Gasto no encontrado'], 404);
        }

        $expense->delete();

        return response()->json(['message' => 'Gasto eliminado correctamente']);
    }
}
