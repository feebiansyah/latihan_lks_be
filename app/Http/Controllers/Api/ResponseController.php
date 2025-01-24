<?php

namespace App\Http\Controllers\Api;

use App\Models\Form;
use App\Models\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Answer;
use Illuminate\Support\Facades\Auth;

class ResponseController extends Controller
{
    public function store(Request $request, $slug){
        $validateData = $request->validate([
            'answers' => 'required|array',
            'answers.*.question_id' => 'required|integer|exists:questions,id',
            'answers.*.value' => 'required|string',
        ]);


        $form = Form::with(['allowedDomains','questions', 'creator' ])->where('slug', $slug)->first();
        if(!$form){
            return response()->json([
                'message' => 'Form not found'
            ], 404);
        }

        $user = Auth::user();
        $allowedDomains = $form->allowedDomains;
        $userDomain = substr(strrchr($user->email, "@"), 1);
        $isAllowed = $allowedDomains->contains('domain', $userDomain);
        if(!$isAllowed){
            return response()->json([
                'message' => "Forbidden access"
            ], 403);
        }
        

        if($form->limit_one_response == true){
            if(Response::where('form_id', $form->id)->where('user_id', Auth::user()->id)->exists()){
                return response()->json([
                    'message' => 'You can not submit form twice'
                ], 422);
            }
        }
        $response = Response::create([
            'form_id' => $form->id,
            'user_id' => Auth::user()->id,
            'date' => now()
        ]);

        foreach($validateData['answers'] as $answer){
            Answer::create([
                'response_id' => $response->id,
                'question_id' => $answer['question_id'],
                'value' => $answer['value']
            ]);
        }

        return response()->json([
            'message' => 'Submit response success'
        ], 200);
    }


    public function index($slug)
    {
        $form = Form::with(['responses', 'creator', 'answers'])->where('slug', $slug)->first();
        if(!$form){
            return response()->json([
                'message' => 'Form not found'
            ], 404);
        }
        if($form->creator_id != Auth::user()->id){
            return response()->json([
                'message' => 'Forbidden access'
            ], 403);
        }
        $response = Response::with(['user', 'answers', 'form.questions'])->where('form_id', $form->id)->get();
        $data = [];

       
        foreach($response as $res){
            
            $data[] = [
                'date' => $res->date,
                'user' => [
                    'id' => $res->user->id,
                    'name' => $res->user->name,
                    'email' => $res->user->email,
                    'email_verified_at' => $res->user->email_verified_at,
                ],
                'answers' => $res->answers->map(function($answer){
                    return [
                        'question' => $answer->questions->name,
                        'value' => $answer->value
                    ];
                })
            ];
        }
        

       return response()->json([
           'message' => 'Get responses success',
           'responses' => $data
         ], 200);

        return response()->json([
            'message' => 'Get responses success',
            'responses' => $data
        ], 200);
    }
}
