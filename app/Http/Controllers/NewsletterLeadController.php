<?php

namespace App\Http\Controllers;

use App\Models\NewsletterLead;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * NewsletterLeadController
 *
 * Handles newsletter subscription lead form submissions.
 */
class NewsletterLeadController extends Controller
{
    /**
     * Store a new newsletter lead.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Por favor, insira um e-mail válido.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Check if email already exists
            $existingLead = NewsletterLead::where('email', $request->email)->first();

            if ($existingLead) {
                return response()->json([
                    'success' => false,
                    'message' => 'Este e-mail já está cadastrado!',
                ], 409);
            }

            // Create new lead
            NewsletterLead::create([
                'email' => $request->email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'E-mail cadastrado com sucesso! Você receberá as melhores ofertas em breve.',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ocorreu um erro ao cadastrar o e-mail. Por favor, tente novamente.',
            ], 500);
        }
    }
}
