<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function enrollCourse(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required|min:3',
            'description' => 'required|min:3',
            'total_videos' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ]);
        }
        $course = Course::create([
            'user_id' => auth()->user()->id,
            'title' => $input['title'],
            'description' => $input['description'],
            'total_videos' => $input['total_videos']
        ]);
        return response()->json([
            'success' => true,
            'messages' => 'Course enroll sucessfully',
            'course' => $course
        ]);
    }

    public function totalCourses()
    {
        $user_id = auth()->user()->id;
        $courses = User::find($user_id)->courses;
        return response()->json([
            'success' => true,
            'message' => 'Total user courses enrollment',
            'data' => $courses
        ]);
    }
}
