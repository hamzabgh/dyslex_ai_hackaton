<?php

namespace Modules\Auth\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Support\Renderable;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;


class QuestionController extends Controller
{

    protected $max_litters = 10;

    protected function generate_random_litters($correct, $numLetters)
    {
        $litters = array();
        for ($i = 1; $i < $numLetters;) {
            $litter = chr(rand(97, 122));
            if ($litter != $correct && !in_array($litter, $litters)) {
                array_push($litters, $litter);
                $i++;
            }
        }
        array_push($litters, $correct);
        shuffle($litters);
        return $litters;
    }



    protected function removeAt($word, $index)
    {
        $characters = str_split($word);

        // Replace character at index 1 with a space
        $characters[$index] = '_';
        // Convert array back to string
        $word = implode('', $characters);
        return ($word);
    }

    public function question(Request $request)
    {
        // Replace 'manayel' with the desired username
        $id = 2;

        // Retrieve the user by username
        $user = User::where('id', $id)->first();

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        Log::info('Authenticated User: ' . json_encode($user));

        try {
            // Proceed with your logic
            $questions = Question::where('level', 1)->get();
            // User is authenticated, proceed with your logic
            $preferredLetters = User::join('preferred_letters', 'users.id', '=', 'preferred_letters.user_id')
                ->join('letters', 'letters.id', '=', 'preferred_letters.letter_id')
                ->where('users.id', $user->id)
                ->pluck('letters.letter')
                ->toArray(); // Convert to array

            $final_list = [];

            if (count($preferredLetters) > 0) {
                foreach ($questions as $question) {
                    // Split the word into an array of letters
                    $wordLetters = str_split($question->world);

                    // Check if any letter of the question is in the preferred letters
                    foreach ($wordLetters as $letter) {
                        if (in_array($letter, $preferredLetters)) {
                            array_push($final_list, $question);
                            break;
                        }
                    }
                }
            } else {
                $final_list = $questions->toArray();
            }

            $question = $final_list[rand(0, count($final_list) - 1)];
            $randomindex = rand(0, strlen($question->world) - 1);
            $correct = $question->world[$randomindex];

            if ($user->level == 1) {
                $numLetters = 3;
            } elseif ($user->level == 0) {
                $numLetters = 1;
            } elseif ($user->level == 2) {
                $numLetters = 4;
            } else {
                $numLetters = 5;
            }
            $list = $this->generate_random_litters($correct, $numLetters);
            $data = [
                'word'           => strtoupper($this->removeAt($question->world, $randomindex)),
                'answer'         => $list,
                'correct_answer' => $correct,
                'audioFile'      => $question->world . '.mp3',
                'letter_index'   => $randomindex
            ];

            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }



    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function answer(Request $request)
    {
        try {
            // Retrieve the JSON data from the request body
            $requestData = $request->json()->all();

            // Authenticate the user
            $user = Auth::user();

            // Check if the user is authenticated
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            Log::info('Authenticated User: ' . json_encode($user));

            // Access the data from the request body
            $questionId = '1';
            $answerLetters = 'a,b,c,d';
            $correctAnswer = 'a';
            $user_answer = 'a';

            // Perform any operations you need with the retrieved data
            $succeed = $user_answer === $correctAnswer; // Use strict comparison

            // Example: Save to the database
            Answer::create([
                'question' => $questionId,
                'letters' => $answerLetters,
                'correct_answer' => $correctAnswer,
                'user_answer' => $user_answer,
                'user_id' => $user->id
            ]);

            if ($succeed) {
                if ($user->progress >= 4) {
                    $user->level += 1;
                    $user->progress = 0;
                } else {
                    $user->progress += 1;
                }
                $user->save();
            }

            return response()->json(['succeed' => $succeed, 'level' => $user->level, 'progress' => $user->progress], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500); // Return the actual exception message for debugging
        }
    }
}
