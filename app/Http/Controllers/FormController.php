<?php

namespace App\Http\Controllers;

use App\Models\Form;
use Illuminate\Http\Request;
use App\Models\AllowedDomains;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FormController extends Controller
{

    //OK
    public function store(Request $request){
        $validateData = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:forms,slug|regex:/^[a-zA-Z0-9.-]+$/',
            'allowed_domains' => 'array',
            'description' => 'nullable',
        ]);

        if ($validateData->fails()) {
            return response()->json(
                [
                    'message' => 'Invalid Field',
                    'errors' => $validateData->errors()
                ],
                422
            );
        }

        $form = Form::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description ?? null,
            'limit_one_response' => $request->limit_one_response ?? false,
            'creator_id' => $request->user()->id,
        ]);

        if ($request->has('allowed_domains')) {
            foreach ($request->allowed_domains as $domain) {
                AllowedDomains::create([
                    'form_id' => $form->id,
                    'domain' => $domain,
                ]);
            }
        }

        return response()->json(
            [
                'message' => 'Create form success',
                'form' => [
                    'id' => $form->id,
                    'name' => $form->name,
                    'slug' => $form->slug,
                    'description' => $form->description,
                    'limit_one_response' => $form->limit_one_response,
                    'creator_id' => $form->creator_id,
                ]
            ],
            201
        );
    }


    //OK
    public function index(){
       $user_id = Auth::user()->id;
       $forms = Form::where('creator_id', $user_id)->select('id', 'name', 'slug', 'description', 'limit_one_response', 'creator_id')->get();
        return response()->json(
            [
                'message' => 'Get all forms success',
                'forms' => $forms
            ],
            200
        );
    }


    //OK
    public function show(Request $request, $slug){
        $form = Form::with(['allowedDomains', 'questions'])->where('slug', $slug)->first();
        if (!$form) {
            return response()->json(
                [
                    'message' => 'Form not found'
                ],
                404
            );
        }

        if ($form->creator_id != $request->user()->id) {
            return response()->json(
                [
                    'message' => 'Forbidden access'
                ],
                403
            );
        }
        

       return response()->json([
            'message' => 'Get form success',
            'form' => [
                'id' => $form->id,
                'name' => $form->name,
                'slug' => $form->slug,
                'description' => $form->description,
                'limit_one_response' => $form->limit_one_response,
                'creator_id' => $form->creator_id,
                'allowed_domains' => $form->allowedDomains->pluck('domain'),
                'questions' => [
                    $form->questions()->select('id','form_id', 'name', 'choice_type', 'choices', 'is_required')->get()
                ]
            ]
       ], 200);

        
    }


}

