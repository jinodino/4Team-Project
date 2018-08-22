<?php
/*
 * Excel Controller
*/
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use App\Model\Professor_book;
use App\Model\Student;
use Storage;

class ExcelController extends Controller 
{

    public function postImport(Request $request) 
    {
        // 교수 ID
        //$professor_id = $request->get('professorId');
        // Faculty ID
        
        $data = request('data');
        // 엑셀 넣을 시 -> 교수의 아이디 고정 -> 받아와야함
        
        $a = $request->get('excelFile');
        $exploded = explode(',', $data);
        $decoded = base64_decode($exploded[1]);
        $fileName = $request->name; //.'.'.$extension;
        Storage::disk('public')->put('aaa.xlsx', $decoded);
        $professor_id = $request->get('professorId');
        
        
        Excel::load('aaa.xlsx', function($reader) use($professor_id)  {
            $results = $reader->all();            
            
            foreach($results as $key => $value) {
                $rand = (String) floor(rand(10000000, 99999999));
                // Excel에 넣지 않아도 될 경우 
                
                $faculty_id  = DB::table('professors')->where('login_id', '=', $professor_id)->pluck('faculty_id');
                
                //$date = date('Y');
                if($value->name != null) {
                    $insert = [
                        'login_id' => $professor_id,
                        'name' => $value->name,
                        'birth_date' => $value->birth_date,
                        'faculty_id' => $faculty_id[0],
                        'employ_year' => $value->employ_year,
                        'email'       => $value->email, 
                        'certification_key' => $rand,
                        'data_entry_time' => \Carbon\Carbon::now()
                    ];

                    
                    DB::table('professor_books')->insert($insert);
                }

                
            }
            
         }); 
         
         Storage::disk('public')->delete('aaa.xlsx');

         return 1;
    }
        

    public function postExport(){
        // 교수 아이디 -> request 받아와야함
        $professor_id =  request('professorId');
        
        // 학생 리스트 
        $stdList = DB::table('professor_books')
        ->select('name', 'email')
        ->where('login_id', '=', $professor_id)
        ->get();
        
        // // 선택 학생 리스트
        // $stdList = request('selectStdent');
        // // 지정 카테코리 리스트
        // $select = request('checkDownload');
        
        // $select_coulmn = [];
        
        // students Table 값을 담기 위한 배열
        $stdInfoList = [];
        foreach($stdList as $std) {
            
            // // 이름
            // array_push($select_coulmn,  'name');
            // foreach($select as $sl) {
                
            //     // name, jpt, jlpt, email, age, pass
            //     if($sl == 'veteran') $sl = 'army_ox';
            //     if($sl == 'stdNum' ) $sl = 'student_no';
            //     if($sl == 'birth' )  $sl = 'birth_date';
            //     if($sl == 'groupNum' )  $sl = 'group_id';
            //     if($sl == 'regularToeic' ) $sl = 'TOEIC';
            //     if($sl == 'sincerity' )  $sl = 'sincerity_grade';
            //     if($sl == 'tenacity' )  $sl = 'personality_grade';
            //     if($sl == 'majorSkill' )  $sl = 'major_grade';
            //     if($sl == 'mockToeic' )  $sl = 'mock_TOEIC';
            //     if($sl == 'phoneNum' )  $sl = 'phone';
            //     if($sl == 'address') $sl = 'email';
            //     if($sl == 'age') $sl = DB::raw('Floor((TO_DAYS(now())-(TO_DAYS(birth_date)))/365) as age');
            //     // 내정확정기업 /* oc.org_name_kana*/
            //     if($sl == 'pass' )  continue;//$sl = DB::raw("case when app.acceptance_ox = 'o' then oc.org_name end as passCompany");
            //     if($sl == 'appliedCompany') continue;//$sl = DB::raw("case when app.apply_permission_ox = 'o' then oc.org_name end as appliedCompany");
            //     if($sl == 'progressingCompany') continue;//DB::raw("case when app.acceptance_ox = null then oc.org_name end as progressingCompany");
            //     if($sl == 'fail') continue;//DB::raw("case when app.acceptance_ox = 'x' then oc.org_name end as failCompany");
            //     if($sl == 'nominatedCompany') continue;//$sl = DB::raw("case when app.acceptance_ox = 'o' AND app.professor_acceptance_ox = 'o' then oc.org_name end as nominatedCompany");
                
            //     // DB::raw("case when emp.apply_deadline_date > '$date' AND emp.apply_open_date < '$date' then 'OPEN'
            //     //     when emp.apply_deadline_date < '$date' then 'CLOSE'
            //     //     when emp.apply_open_date > '$date' then 'YET' end as date_result")
                
            //     array_push($select_coulmn,  $sl);
            // }
            
            // $stdmanagement = DB::table('students as std')
            // ->where([
            //     ['std.professor_login_id','=', $professor_id],
            //     ['std.name', '=', $std->name],
            //     ['std.email', '=', $std->email]
            //     // ['app.student_login_id', '=', $std['login_id']],
            //     // ['app.acceptance_ox', '=', 'o'],
            //     // ['app.professor_acceptance_ox', '=', 'o'],
            // ])
            // ->pluck('name');

            $stdmanagement = DB::table('students as std')
            //->select('name_eng', 'birth_date', 'email', 'JPT', 'JLPT')
            // -> 학생 정보 select 순서만 정해주면 됨 2018-06-20
            ->select(
                'std.name',
                'std.birth_date',
                'std.group_id',
                'std.group_part_content',
                'std.gender',
                'std.credit',
                'std.address',
                'std.phone',
                'std.major_grade',
                'std.sincerity_grade',
                'std.personality_grade',
                'std.japanese_speaking_level',
                'std.JPT',
                'std.JLPT',
                'std.JLPT_acquisition_date',
                'std.TOEIC',
                'std.TOEIC_acquisition_date',
                'std.mock_TOEIC',
                'std.mock_TOEIC_acquisition_date',
                'std.TOEFL',
                'std.TOEFL_acquisition_date'
            )
            // ->join('applies as app', 'std.login_id', '=', 'app.student_login_id')
            // ->join('interview_results as itr', 'std.login_id', '=', 'itr.student_login_id')
            // ->join('interview_schedules as is', 'is.interview_id', '=', 'itr.interview_id')
            // ->join('employment_infos as emp', 'is.employment_id', '=', 'emp.employment_id')
            //->join('interview_schedules as is', 'std.interview_id', '=', 'is.interview_id')
            //->join('employment_infos as emp', 'app.employment_id', '=', 'emp.employment_id')
            // ->join('org_matchings as orm', 'emp.org_matching_foreign', '=', 'orm.org_matchings_id')
            // ->join('org_companies as oc', 'orm.org_company_id', '=', 'oc.org_company_id')
            ->where([
                ['std.professor_login_id','=', $professor_id],
                ['std.name', '=', $std->name],
                ['std.email', '=', $std->email]
                // ['app.student_login_id', '=', $std['login_id']],
                // ['app.acceptance_ox', '=', 'o'],
                // ['app.professor_acceptance_ox', '=', 'o'],
            ])
            // ->orWhere([
            //     ['std.professor_login_id','=', $professor_id],
            //     ['std.name', '=', $std->name],
            //     ['std.login_id', '=', $std['login_id']],
            // ])
            //->groupBy('login_id')
            ->get();

            
            
            // value값이 존재할 시 배열에 값을 담아줌 -> 아니면 []값만 들어감
            if(isset($stdmanagement[0])){
                array_push($stdInfoList, $stdmanagement[0]);
            }
            
        }
        
        
        // 다중 배열로 묶인 student table value list들을 풀어서 담을 배열
        $starr = [];
        foreach($stdInfoList as $std) {
            array_push($starr, $std);
        }
        
        // 주소록 값 받아옴 -> student table에 중복되지 않는 값만 받아옴
        $professor_book = DB::table('professor_books')
             ->select('name', 'birth_date')
             ->whereRaw('professor_books.name not in (select students.name from students)')
             ->orwhereRaw(DB::raw('professor_books.birth_date not in (select students.birth_date from students)'))
             ->get();
        // 주소록 학생 값 담을 배열
        $professor_std = [];
        
        // 값 넣어줌
        foreach($professor_book as $prb){
            foreach($stdList as $std) {
                if($std->name == $prb->name) {
                    array_push($professor_std,  $prb);
                }
            }
        }
       
        // 결과값 담을 배열
        $result = [];
      
        // 각각의 값들을 결과값 배열에 담아줌
        foreach($starr as $std) {
            array_push($result, (array)$std);
        }
        
        foreach($professor_std as $prs) {
            array_push($result, (array)$prs);
        }
        
        // Excel로 변환
        Excel::create('ExportFile', function($excel) use($result) {
            $excel->sheet('Sheet 1', function($sheet) use($result) {
                $sheet->fromArray($result);                
            });
        })->download('xlsx');

        
    }

}

