<?php

namespace App\Http\Controllers\Api;

use App\Models\Form;
use App\Models\Questions;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Console\Question\Question;

class QuestionController extends Controller
{
    //OK
    public function store(Request $request, $slug) {
       $validateData =  $request->validate([
            'name' => 'required|string',
            'choice_type' => 'required|in:sort answer,paragraph,multiple choice,dropdown,checkbox',
            'choices' => 'nullable',
            'is_required' => 'nullable|boolean',
        ]);
        if(in_array($validateData['choice_type'], ['multiple choice', 'dropdown', 'checkbox']) && empty($validateData['choices'])) {
            return response()->json([
                'message' => 'Invalid Field',
                'errors' => [
                    'choices' => 'Choices is required for multiple_choice, dropdown, and checkbox choice types'
                ]
            ], 422);
            
        }
        $form = Form::where('slug', $slug)->first();
        if(!$form) {
            return response()->json([
                'message' => 'Form not found'
            ], 404);
        }
        if($form->creator_id != Auth::user()->id) {
            return response()->json([
                'message' => 'Forbidden access'
            ], 403);
        }

        $question = Questions::create([
            'form_id' => $form->id,
            'name' => $validateData['name'],
            'choice_type' => $validateData['choice_type'],
            'choices' => $validateData['choices'] ?? null,
            'is_required' => $validateData['is_required'],
        ]);

        return response()->json([
            'message' => 'Add question success',
            'question' => [
                'id' => $question->id,
                'name' => $question->name,
                'choice_type' => $question->choice_type,
                'is_required' => $question->is_required,
                'choices' => $question->choices,
                'form_id' => $question->form_id,
            ]
        ], 200);
        

    }


    //OK
    public function destroy($slug, $question_id){
        $form = Form::where('slug', $slug)->first();
        if(!$form) {
            return response()->json([
                'message' => 'Form not found'
            ], 404);
        }
        if($form->creator_id != Auth::user()->id) {
            return response()->json([
                'message' => 'Forbidden access'
            ], 403);
        }

        $question = Questions::where('id', $question_id)->where('form_id', $form->id)->first();
        if(!$question) {
            return response()->json([
                'message' => 'Question not found'
            ], 404);
        }

        $question->delete();
        return response()->json([
            'message' => 'Remove question success'
        ], 200);
    }
}
