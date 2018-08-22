<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PushNotify as PushNotify;

class StdManagement extends Controller
{
    //학생 리스트 출력
    public function stdManagement(Request $request){
        
        $professor_id = $request->get('professorId');
        $faculty_id = $request->get('faculty_id');
        $year = $request->get('year');

        $stdmanagement = DB::table('students as std')
                    ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
                    ->join('groups as g', 'g.group_id', '=', 'std.group_id')
                    ->whereRaw("std.employ_year = $year 
                                and pro.faculty_id in (select faculty_id from professors as pro where login_id = '$professor_id')")
                    ->select('std.*',
                            'g.*',
                            DB::raw("TRUNCATE((TO_DAYS(now())-(TO_DAYS(std.birth_date)))/365, 0) as age")
                            )
                    ->get();
                    
        foreach($stdmanagement as $key => $stdmanagements){
            $std_id = $stdmanagements->login_id;

            $stdmanagement["$key"]->portfolio_link = "/home/ubuntu/storage/repository/$std_id/portfolio";
            $stdmanagement["$key"]->personal_history = "/home/ubuntu/storage/repository/$std_id/personal_history";
        }
        
        
        //echo json_encode($stdmanagement);

        //교수 주소록에서 students 테이블에 있는 인원을 제외한 사람들 뽑기
        $professor_book = DB::table('professor_books as pb')
        ->select(   'pb.login_id as professor_books_id',
                    'pb.name', 
                    'pb.birth_date')
        ->whereRaw( "(pb.name, pb.birth_date) not in (select name, birth_date from students)
                    and pb.employ_year = '$year'
                    and pb.faculty_id in (select faculty_id from professors where login_id = '$professor_id')
        ")
        ->get();

        echo json_encode(array($stdmanagement, $professor_book));
        
        /*for($i = sizeOf($stdmanagement), $v = 0; $i<= (sizeOf($stdmanagement)+sizeOf($professor_book)); $i++, $v++){
            $stdmanagement[$i] = $professor_book[$v];
        }*/
        //echo sizeOf($stdmanagement)+sizeOf($professor_book);
        
/*
        //교수 주소록에서 students 테이블에 있는 인원을 제외한 사람들 뽑기
        $professor_book = DB::table('professor_books')
        ->join('professors', 'professors.login_id', '=', 'professor_books.login_id')
        ->select(   'professor_books.login_id as professor_books_id',
                    'professor_books.name', 
                    'professor_books.birth_date')
        ->whereRaw( "(professor_books.name not in (select name from students) or professor_books.birth_date not in (select birth_date from students))
                        and
                        professor_books.employ_year = '$year'
                        and professors.faculty_id in (select faculty_id from professors where login_id = '$professor_id')
        ")
        ->get();
        echo json_encode($professor_book);*/
/*
        //객체 배열로 변환
        $array_std = (array)$stdmanagement;
        $array_pro = (array)$professor_book;

        //임시로 값 저장
        $std_temp = array();
        $pro_temp = array();
        //학생, 교수주소록배열을 저장
        $result1 = array();
        $result2 = array();

        foreach($array_std as $value){
            $result1 = array_merge($std_temp, $value);
        }
        foreach($array_pro as $value){
            $result2 = array_merge($pro_temp, $value);
        }
        $result = array_merge($result1, $result2);

        echo json_encode($result);
    */      
        /*
        //교수과 관리하는 학생 리스트 출력
        $stdmanagement = DB::table('students as std')
        ->join('professors as pro', 'pro.login_id', '=', 'std.professor_login_id')
        ->join('groups', 'std.group_id', '=', 'groups.group_id')
        ->select('std.login_id',
                'std.name as name',
                'std.name_eng',
                'std.name_kanji',
                'std.name_kana',
                'std.student_no as stdNum',
                'std.birth_date',
                DB::raw('Floor((TO_DAYS(now())-(TO_DAYS(std.birth_date)))/365) as age'), 
                'std.army_ox as veteran',
                'groups.group_num as groupNum', 
                'std.JLPT as jlpt', 
                'std.JPT as jpt', 
                'std.major_grade as majorSkill', 
                'std.personality_grade as tenacity', 
                'std.sincerity_grade as sincerity',
                'std.TOEIC as regularToeic',
                'std.mock_TOEIC as mockToeic', 
                'std.email as email', 
                'std.phone as phoneNum', 
                'std.sub_phone', 
                'std.address as address',
                DB::raw("'true' as checkbox")
                )
        ->whereRaw("pro.faculty_id in (select faculties.faculty_id
                                            from faculties
                                            join professors on professors.faculty_id = faculties.faculty_id
                                            where professors.login_id = '$professor_id')
                    and std.employ_year = '$year'
                    ")
        ->groupBy('std.login_id')
        ->get();

        //학생이 지원한 회사 수
        $apply = DB::table('applies')
        ->join('students as std', 'applies.student_login_id', '=', 'std.login_id')
        ->join('professors as pro', 'pro.login_id', '=', 'std.professor_login_id')
        ->whereRaw("pro.faculty_id in (select faculties.faculty_id
                                            from faculties
                                            join professors on professors.faculty_id = faculties.faculty_id
                                            where professors.login_id = '$professor_id')
                    and std.employ_year = '$year'")
        ->select('applies.student_login_id', DB::raw('count(applies.apply_permission_ox) as appliedCompany'))
        ->groupBy('applies.student_login_id')
        ->get();

        
        //학생이 통과한 내정받은 회사 수
        $pass = DB::table('applies')
        ->join('students as std', 'applies.student_login_id', '=', 'std.login_id')
        ->join('professors as pro', 'pro.login_id', '=', 'std.professor_login_id')
        ->select('applies.student_login_id',
                DB::raw("count(case when acceptance_ox = 'o' then 1 end) as pass"))
        ->whereRaw("pro.faculty_id in (select faculties.faculty_id
                                            from faculties
                                            join professors on professors.faculty_id = faculties.faculty_id
                                            where professors.login_id = '$professor_id')
                    and std.employ_year = '$year'")
        ->groupBy('applies.student_login_id')
        ->get();

        //학생당 현재 취업활동중인 회사 수
        $progressingCompany = DB::table('applies')
        ->join('students as std', 'applies.student_login_id', '=', 'std.login_id')
        ->join('professors as pro', 'pro.login_id', '=', 'std.professor_login_id')
        ->select('applies.student_login_id',
                DB::raw("count(case when acceptance_ox = '' or acceptance_ox is null then 1 end) as progressingCompany"))
        ->whereRaw("pro.faculty_id in (select faculties.faculty_id
                                            from faculties
                                            join professors on professors.faculty_id = faculties.faculty_id
                                            where professors.login_id = '$professor_id')
                    and std.employ_year = '$year'")
        ->groupBy('applies.student_login_id')
        ->get();

        //학생당 현재 불합격 한 회사 수
        $fail = DB::table('applies')
        ->join('students as std', 'applies.student_login_id', '=', 'std.login_id')
        ->join('professors as pro', 'pro.login_id', '=', 'std.professor_login_id')
        ->select('applies.student_login_id',
                DB::raw("count(case when acceptance_ox = 'x' then 1 end) as fail"))
        ->whereRaw("pro.faculty_id in (select faculties.faculty_id
                                            from faculties
                                            join professors on professors.faculty_id = faculties.faculty_id
                                            where professors.login_id = '$professor_id')
                    and std.employ_year = '$year'")
        ->groupBy('applies.student_login_id')
        ->get();

        //학생당 내정 확정 회사
        $nominatedCompany = DB::table('applies')
        ->join('students as std', 'applies.student_login_id', '=', 'std.login_id')
        ->join('professors as pro', 'pro.login_id', '=', 'std.professor_login_id')
        ->join('employment_infos', 'applies.employment_id', '=', 'employment_infos.employment_id')
        ->join('org_matchings', 'org_matchings.org_matchings_id', '=', 'employment_infos.org_matching_foreign')
        ->join('org_companies', 'org_matchings.org_company_id', '=', 'org_companies.org_company_id')
        ->select('applies.student_login_id', 'org_companies.org_company_id', 'org_companies.org_name as nominatedCompany', 'org_companies.org_name_kana')
        ->whereRaw("pro.faculty_id in (select faculties.faculty_id
                                        from faculties
                                        join professors on professors.faculty_id = faculties.faculty_id
                                        where professors.login_id = '$professor_id')
                    and std.employ_year = '$year'
                    and (applies.acceptance_ox = 'o' and applies.student_acceptance_ox = 'o' and applies.professor_acceptance_ox = 'o')")
        ->get();
        
        foreach($stdmanagement as $key => $value){
            $passCom = array();
            foreach($apply as $applies){
                if($value->login_id == $applies->student_login_id){
                    $stdmanagement[$key]->appliedCompany = $applies->appliedCompany;
                    break;
                }
            }

            foreach($pass as $passes){
                if($value->login_id == $passes->student_login_id){
                    $stdmanagement[$key]->pass = $passes->pass;
                    break;
                }
            }
            foreach($progressingCompany as $kye => $progressingCompanies){
                if($value->login_id == $progressingCompanies->student_login_id){
                    $stdmanagement[$key]->progressingCompany = $progressingCompanies->progressingCompany;
                    break;
                }
            }
            foreach($fail as $key => $fails){
                if($value->login_id == $fails->student_login_id){
                    $stdmanagement[$key]->fail = $fails->fail;
                    break;
                }
            }
            foreach($nominatedCompany as $key => $nominatedCompanies){
                if($value->login_id == $nominatedCompanies->student_login_id){
                    $stdmanagement[$key]->org_company_id = $nominatedCompanies->org_company_id;
                    $stdmanagement[$key]->nominatedCompany = $nominatedCompanies->nominatedCompany;
                    $stdmanagement[$key]->org_name_kana = $nominatedCompanies->org_name_kana;
                    break;
                }
            }
            
        }
        */
        

        
        
    }

    public function delete_student(Request $request){
        
        //삭제할 학생 ID
        @$std_id          =   $request->get('std_id');

        //삭제할 학생이 아직 회원가입을 안했을 경우 생일,이름 넘기기
        @$std_birthday   =   $request->get('std_birth');
        @$std_name       =   $request->get('std_name');

        //학생이 소속된 교수 ID
        $pro_id          =   $request->get('professorId');
        
        //생일이 없는경우 유저테이블에서 학생 삭제
        //생일이 있는 경우 교수주소록테이블에서 학생 삭제
        foreach($std_birthday as $std_birthdaies){
            if($std_birthdaies == null){
                foreach($std_id as $std_ids){
                    
                    DB::table('students')
                    ->where('login_id', '=', $std_ids)
                    ->update(['employ_year' => null]);
                }

            }else{
                foreach($std_name as $std_names){
                    foreach($std_birthday as $std_birthdaies){
                        $test = DB::table('professor_books')->where([
                            ['login_id', '=', $pro_id],
                            ['name', '=', $std_names],
                            ['birth_date', '=', $std_birthdaies]
                        ])
                        ->update(['employ_year' => null]);
                        break;
                    }
                }
            }
        }
        echo 'success';
    }

    //학생 추가
    public function add_std(Request $request){
        $std_professor_id   =   $request->get('professorId');//교수 ID
        $std_name           =   $request->get('stdName');//학생 이름
        $std_birthday       =   $request->get('stdNum');//학생 생일
        $employ_year        =   $request->get('yearOfemployment');//취활 연도
        $stdEmail           =   $request->get('stdEmail');  //학생 이메일
        $faculty_id =  DB::table('professors')
        ->select('faculty_id')
        ->where('login_id', '=', $std_professor_id)
        ->get();

        // 학생 식별 값
        $rand = (String) floor(rand(10000000, 99999999));

        $query = DB::table('professor_books')
        ->insert([
            'login_id'          =>  $std_professor_id,
            'name'              =>  $std_name,
            'birth_date'        =>  date($std_birthday),
            'faculty_id'        =>  $faculty_id[0]->faculty_id,
            'email'             =>  $stdEmail,
            'certification_key' =>  $rand,
            'employ_year'       =>  $employ_year
        ]);
        
        if($query) return 'success';
    }

    public function professor_company_list(Request $request){
        //채용하러 오는 기업 리스트
        
        $professorId = $request->get('professorId');
        
        $company_list = DB::table('org_matchings')
        ->join('org_companies', 'org_matchings.org_company_id', '=', 'org_companies.org_company_id')
        ->join('org_colleges', 'org_matchings.org_college_id', '=', 'org_colleges.org_college_id')
        ->join('faculties', 'faculties.org_college_id', '=', 'org_colleges.org_college_id')
        ->select('org_companies.org_company_id', 'org_companies.org_name', 'org_companies.org_name_kana')
        ->whereRaw("faculties.faculty_id in (select faculty_id
                                                from professors
                                                where professors.login_id = '$professorId')")
        ->get();

        return $company_list;
    }

    //내정 확정
    public function stdFixCompany(Request $request){
        
        $professor_id   = $request->get('professorId');
        $std_id         = $request->get('studentId');
        $apply_id       = $request->get('NominatedCompany');
        //return $std_id . "#" . $apply_id;

        $update = DB::table('applies')
        ->where([
            ['employment_id', '=', $apply_id],
            ['student_login_id', '=', $std_id]
        ])
        ->update([
            'professor_acceptance_ox' => 'o'
        ]);
        
        $std_name = DB::table('students')
        ->where('login_id', '=',  $std_id)
        ->select('name')
        ->get();
        
        $pushNotify = new PushNotify();
        $apiKey = $request->get('apiKey');
        foreach($std_name as $name){
            $user_name = $name->name;
            $pushNotify->push_select_send($professor_id, $std_id, $apiKey, "교수님이 $user_name 님의 회사 선택에 대한 결과를 확인하셨습니다.");
        }

        $update = DB::table('students')
        ->where('login_id', '=', $std_id)
        ->update(['employ_ox' => 'x']);



        return 'success';
        
    }

    public function group_add(Request $request){
        //그룹 추가
        $professor_id   =   $request->get('professor_id');
        $group_info     =   $request->get('group');
        $group_num      =   $group_info["num"];
        $group_name     =   $group_info["name"];
        $explain        =   $group_info["explain"];
        $link           =   $group_info["link"];
        $showcase_year  =   date("Y");

        $faculty_id = DB::table('professors')
        ->where('login_id', '=', $professor_id)
        ->select('faculty_id')
        ->get();
        
        
        try{
            DB::table('groups')
            ->insert(
                [
                    'faculty_id'            =>  $faculty_id[0]->faculty_id,
                    'group_num'             =>  $group_num,
                    'group_name'            =>  $group_name,
                    'project_content'       =>  $explain,
                    'showcase_year'         =>  $showcase_year,
                    'project_video_url'     =>  $link 
                ]
            );
            
        }catch(\Illuminate\Database\QueryException $e){
            
            return "이미 존재하는 그룹입니다.";
        }
        return "그룹 생성 완료";
    }

    public function group_delete(Request $request){
        //그룹 삭제
        $professor_id   =   $request->get('professor_id');
        $group_data     =   $request->get('data');

        
        
        DB::table('groups')
        ->where('group_id', $group_data["group_id"])
        ->update(
            [
                'group_name'            =>  "삭제된 조 입니다.",
                'project_content'       => null,
                'showcase_year'         =>  null
            ]
        );
            
        
        return "그룹 삭제 완료";
    }
    //학생 정보 수정
    public function std_info_change(Request $request){
        $studentId = $request->get('studentId');
        $major_grade = $request->get('major_grade');
        $japanese_speaking_level = $request->get('japanese_speaking_level');
        $sincerity_grade = $request->get('sincerity_grade');
        $personality_grade = $request->get('personality_grade');


        DB::table('students')
        ->where('login_id', '=', $studentId)
        ->update(['major_grade' => $major_grade, //전공실력
                'japanese_speaking_level' => $japanese_speaking_level,  //회화능력
                'sincerity_grade' => $sincerity_grade,   //성실도
                'personality_grade' => $personality_grade]);   //인성
    
        
        return 'success';
    }
    //그룹 정보 출력(학생 포함)  => 현재 안쓰는거
    public function group_list_std(Request $request){
        $professorId = $request->get('professorId');

        $grouplist = DB::table('groups')
        ->select('group_id', 'faculty_id', 'group_num as group_num', 'group_name as name', 'project_title as title', 'project_content as contents', 'showcase_year')
        ->whereRaw("groups.faculty_id in (select faculty_id from professors where professors.login_id = '$professorId')")
        ->get();

        foreach($grouplist as $key => $grouplists){
            $group_id = $grouplists->group_id;

            $group_std = DB::table('students')
            ->select('login_id', 'name', 'name_eng', 'name_kanji', 'name_kana')
            ->where("group_id", "=", $group_id)
            ->get();

            $grouplist[$key]->std = $group_std;
        }

        return json_encode($grouplist);
    }

    //그룹 정보 출력(학생 포함)
    public function group_info_list(Request $request){
        $professorId = $request->get('professor_id');

        $grouplist = DB::table('groups')
        ->select('*')
        ->orderBy('group_num', 'asc')
        ->whereRaw("groups.faculty_id in (select faculty_id from professors where professors.login_id = '$professorId')
                    and (groups.showcase_year is not null or groups.showcase_year != '')")
        ->get();

        return $grouplist;
    }

    //그룹 정보 수정
    public function group_info_change(Request $request){
        $professorId = $request->get('professorId');
        $data = $request->get('data');
        
        foreach($data as $datas){
            DB::table('groups')
            ->where('group_id', '=', $datas["group_id"])
            ->update(['group_name' => $datas["group_name"],
                    'project_content' => $datas["project_content"],
                    'project_video_url' => $datas["project_video_url"],
                    ]);
        }
        return 'success';
        

        return $grouplist;
    }

    //교수의 학부 정보
    public function callFacultyId(Request $request){
        $professor_id = $request->get('professorId');

        return DB::table('professors')
        ->select('faculty_id')
        ->where('login_id', '=', $professor_id)
        ->get();
    }

    //회사에 따른 채용건 리스트
    public function selectedCompany_employment_info(Request $request){
        $professor_id = $request->get('professorId');
        $company_id = $request->get('company');

        $employ_info = DB::table("employment_infos as emp")
        ->select("emp.*")
        ->join('org_matchings as mat', 'emp.org_matching_foreign', '=', 'mat.org_matchings_id')
        ->join('org_colleges as col', 'mat.org_college_id', '=', 'col.org_college_id')
        ->join('faculties as fac', 'fac.org_college_id', '=', 'col.org_college_id')
        ->whereRaw("mat.org_company_id = '$company_id'
		            and fac.faculty_id in (select faculty_id from professors where login_id = '$professor_id')")
        ->get();

        foreach($employ_info as $key => $employ_infos){
            $employ_id = $employ_infos->employment_id;
            $employ_info[$key]->work_info = DB::table("work_matchings")
            ->join('work_infos', 'work_matchings.work_id', '=', 'work_infos.id')
            ->select('work_infos.content')
            ->where('work_matchings.employment_id', '=', $employ_id)
            ->get();
        }

        return json_encode($employ_info);
    }

    public function calljoinStd(Request $request){
        $professorId = $request->get('professorId');
        
        $year = date("Y");

        //가입한 학생의 숫자 뽑기
        $join_std_count = DB::table('students as std')
                    ->join('professors as pro', 'std.professor_login_id', '=', 'pro.login_id')
                    ->whereRaw("std.employ_year = $year 
                                and pro.faculty_id in (select faculty_id from professors where login_id = '$professorId')")
                    ->select(DB::raw("count(std.login_id) as join_std_count"))
                    ->get();
        

        //교수 주소록에서 students 테이블에 있는 인원을 제외한 사람들 뽑기
        $professor_book = DB::table('professor_books as pb')
        ->select(   'pb.name', 
                    'pb.birth_date')
        ->whereRaw( "(pb.name, pb.birth_date) not in (select name, birth_date from students)
                    and pb.employ_year = '$year'
                    and pb.faculty_id in (select faculty_id from professors where login_id = '$professorId')
        ")
        ->get();

        echo json_encode(array($join_std_count, $professor_book));
    }
}