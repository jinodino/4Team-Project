<?php

namespace App\Http\Controllers\Professor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
class StudentManagementController extends Controller
{
    public function ProfessorBookListUp(Request $request) {
        // 교수 아이디 
        $professor_id = $request->professor_id;

        // Professor_books Table - Students Table
        // Q1. 학생 이름을 한국어로 할 것인가 외국어로 할 것인가
        // 학생 이름 유무 판단 -> professor_id 필수
        

        // 값이 없을 경우 -> Professor_books Table
        // 값이 있을 경우 -> Students Table
        if(!$student_id_check) {
            
        }else {

        }
    }
}
