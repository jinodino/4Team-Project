<?php

use Illuminate\Support\Facades\Mail;
use App\Mail\SesMailable;

Route::get("sample/ses/preview" ,function (){
    return new SesMailable;
});

// map테스트
Route::post('/map', 'mapController@map');

Route::get("sample/ses/send" , "MailHandler@testMailer");

//Index page
Route::get('/', function () { return view('welcome'); });

Route::resource('umc', 'UserManagementController');
Route::post('umc/find/id' ,"UserManagementController@findId");
Route::post('umc/find/pwd',"UserManagementController@findPassword");

// Mail Send
Route::post('/MailSned', 'MailHandler@mailHandlerMailHandler');
// Mail ConfirmConfirm
Route::get('/confirm/{secretdate?}/{classification?}', 'UrlHandler@confirmExpireDate');

// 기업 login 인증절차
Route::post('/me', 'AuthController@me');
/**
 * 학생 회원 등록시 사용 메서드
 * But ID 중복검사는 모든 유저 사용 가능
 */
// 학생 회원 가입시 기본 정보(Code, Name, Birth) 유효성 검사 -> professor_books Table
Route::post('/postmembercheck', 'UserManagementController@postMemberCheck');
// 학생 이메일 인증 메일 전송
Route::post('/postmailcheck/send', 'MailHandler@studentConfirmMailHandler');
// 학생 이메일 인증 코드 유효성 검사 
Route::post('/postmailcheck/mailconfirmcodecheck', 'UserManagementController@MailConfirmCodeCheck');
// ID 중복값 검사 (모든 유저)
Route::post('/postUserIdDuplicates', 'UserManagementController@postUserIdDuplicates');
// 학생 회원가입 -> users, students 값 넣어줘야함
Route::post('/postStudentEnter', 'UserManagementController@postStudentEnter');

/**
 * 학생 로그인 후 페이지 
 */
// 학생 프로필 출력



Route::post('/login' , 'AuthController@login');
Route::post('/logout' , 'AuthController@logout');
Route::resource('sdf', 'Item');

/**
 * 파일만들기 TEST용 - 손진호
 */
Route::get('/1' , function () {
    // 디렉토리명
    $directory = "Junsu";
    // 폴더 로컬에 생성하기
    echo storage_path();
   var_dump( Storage::disk('local')->makeDirectory('test/' . $directory));
   var_dump( Storage::disk('local')->put('test/' . $directory . '/' . 'test.csv', 'sef' ));
   var_dump( Storage::disk('local')->exists('test/' . $directory .'/'. 'test.csv'));
  
});

// Excel Test
Route::post('/import', 'ExcelController@postImport');
//Route::get('/export', 'ExcelController@getExport');
Route::post('/export', 'ExcelController@postExport');
/**
 *  Professor_Book student List
 */
Route::get('/professorStudnet', 'ExcelController@getStudent');


/** @author Student companyList 
 *  학생 -> 매칭 기업 리스트 출력 2018 04 28 작업
 */
Route::get('/companyList11', 'Student\CompanyListController@MatchingCompanyList');
Route::get('/companyListInfo1', 'Student\CompanyListController@companyInfo');
Route::get('/companyemployInfo1', 'Student\CompanyListController@detailEmploymentInfo');

/** @author Student Profile
 */

Route::get('/33', function () {
     phpinfo();
});
// 기업 -> 선택 학생 상세 정보 
Route::post('/anonStdInfo', 'Company\SchoolSerchController@detailsStudentInfo');
// 기업 정보, 채용정보, 채용여부 확인 /1/기업명
Route::post('/companyprofile', 'Company\CompanyInfoController@detailsCompanyInfo');
// 기업 -> 매칭 학교 리스트 출력 
Route::post('/schoolList', 'Company\SchoolSerchController@collegeList');
// 기업 -> 해당 학교 학생 리스트 출력
Route::post('/anonStdList', 'Company\SchoolSerchController@studentList');
// 기업 -> 당사 지원한 학생 리스트 출력
Route::post('/recruitStatus' , 'Company\SchoolSerchController@applyStudentList');
// 기업 -> 채용건 리스트 출력 
Route::post('/activateEmployment', 'Company\SchoolSerchController@matchingschoolList');
// 기업 -> 채용공고 상세 보기 
Route::post('/employment_details', 'Company\CompanyInfoController@employmentDetailInfo');
// 기업 -> 매칭 학교 채용의사 변경 알림
Route::post('/companyMatchingSwitchNotice', 'Company\CompanyNoticeController@companyMatchingSwitchNotice');
// 기업 -> 채용건 변경 알림 -> 지금 사용 X 
Route::post('/employmentSwitchNotice', 'Company\CompanyNoticeController@employmentSwitchNotice');
// 기업 -> 채용건 마감 알림
Route::post('/closeEmploymentNotice', 'Company\CompanyNoticeController@closeEmploymentNotice');
// 기업 -> 지원 학생 합불 여부 알림
Route::post('/studentInterviewResultDecisionNotice', 'Company\CompanyNoticeController@studentInterviewResultDecisionNotice');
// 기업 -> 지원 학생 합불 설정(업데이트)
Route::post('/interviewResultDecisionUpdate', 'Company\InterviewResultDecision@interviewResultDecisionUpdate');
// 기업 -> 프로필 기본 정보 3가지<이름, 카타카나, 이메일> 수정(업데이트)
Route::post('/profileInfoChange', 'Company\CompanyInfoController@profileInfoChange');
// 기업 -> 내정 관리 -> 채용공고 별 학생 수
Route::post('/nominatedInfo', 'Company\NominatedManagementController@nominatedInfo');
// 기업 -> 내정 관리 -> 채용공고 선택시 내정 내린 학생 및 내정 수락 학생 리스트
Route::post('/nominatedStdList', 'Company\NominatedManagementController@nominatedStdList');
// 기업 -> 해당 학교 매칭 채용건 해당 학교 매칭 채용건 정보
Route::post('/collegeMatchingEmploymentList', 'Company\CollegeMatchingEmploymentController@collegeMatchingEmploymentList');
// 기업 -> 해당 학교 매칭 채용건 리스트 출력
Route::post('/countryMatchingCollegeList', 'Company\CollegeMatchingEmploymentController@countryMatchingCollegeList');
// 기업 -> 다음 일정으로 넘기기
Route::post('/nextInterview', 'Company\NextInterviewController@nextInterview');
// 기업 -> 기업 캘린더 면접일정 보기
Route::post('/schedule', 'Company\CalenderController@schedule');
// 기업 -> 기업 캘린더 면접일정 학생 리스트
Route::get('/scheduleStudentList', 'Company\CalenderController@scheduleStudentList');
// 기업 -> 학교별 학생 비율 (인원수, 남녀, JLPT)
Route::get('/collegeStudentRatio', 'Company\CollegeStudentRatioController@collegeStudentRatio');
// 기업 -> 지원학생 전체 이력서 및 포폴 다운로드
Route::post('/studentEntrySheetDownload', 'Company\StudentEntrySheetDownloadController@studentEntrySheetDownload');
// 기업 -> 채용공고 지정 학생 상세보기
Route::post('/getStdDetails', 'Company\SchoolSerchController@selectDetailStudentInfo');

/**
 * 2018-06-23 진호 함수 테스팅
 */
// 테스팅 1 : 기업 -> 학교 리스트 뽑기
Route::post('/testcollegeList', 'Company\CollegeInfoController@collegeList');
Route::post('/testselectCollegeInfo', 'Company\CollegeInfoController@selectCollegeInfo');
Route::post('/testfacultyDetailInfo', 'Company\CollegeInfoController@facultyDetailInfo');




//프로필 이미지 업로드
Route::post('/img/store', 'ProfileImageController@store');

//프로필 이미지 삭제
Route::post('/img/remove', 'ProfileImageController@remove');


// ----------------------------- TEST -------------------------------
Route::post('richmessage','RichMessageControoler@index');



//레포지토리 
Route::post('/repository/store' , 'ProfileImageController@storeES');


//전체 회사 리스트업
Route::post('/test', 'Company_list_up@company_list_up_all');
//검색 회사 리스트업
Route::post('/test_search', 'Company_list_up@company_list_up_search');

//회사에 따른 채용건 리스트
Route::post('/selectedCompany_employment_info', 'StdManagement@selectedCompany_employment_info');

//교수 프로필
Route::post('/professor_profile', 'professor_profile@profile');

//교수 프로필 수정
Route::post('/professor_profile_save', 'professor_profile@profile_save');

//교수 내정관리
Route::post('/Professor_status_info', 'Professor_StatusManagement@status_info');

//교수 내정관리 - 회사 내정 현황
Route::post('/Professor_apply_progress', 'Professor_StatusManagement@apply_progress');

//교수가 관리하는 학생리스트
//학생리스트
Route::post('/Professor_std_listup', 'StdManagement@stdManagement');

//채용하러 오는 기업 리스트
Route::post('/Professor_company_list', 'StdManagement@professor_company_list');

//교수 기능 : 가입하지 않은 학생이 누군가, 가입한 학생은 몇명인가
Route::post('/Professor_calljoinStd', 'StdManagement@calljoinStd');

//학생 정보 수정
Route::post('/Professor_std_info_change', 'StdManagement@std_info_change');

//조 정보 리스트(조에 포함된 학생리스트)
Route::post('/Professor_group_list_std', 'StdManagement@group_list_std');

//조 정보 리스트
Route::post('/Professor_group_list', 'StdManagement@group_info_list');

//조 정보 수정
Route::post('/Professor_group_info_change', 'StdManagement@group_info_change');

//교수의 학부 정보 
Route::post('/Professor_callFacultyId', 'StdManagement@callFacultyId');
//학생 제거
Route::post('/Professor_std_delete', 'StdManagement@delete_student');

//학생 추가
Route::post('/Professor_std_add', 'StdManagement@add_std');

//학생 구직활동 종료
Route::post('/Professor_std_employ_ox', 'StdManagement@exploy_ox');

//학생의 이력서or포트폴리오 보여주기
Route::post('/studentPortfolio_list', 'Professor_StudentViewResumePortfolioController@studentPortfolio_list');

//학생의 PR페이지 동영상 보여주기
Route::post('/studentPRVideo', 'Professor_StudentViewResumePortfolioController@studentPRVideo');

//학생리스트
Route::post('/repository', 'Repository@repository');

//교수 그룹 추가
Route::post('/Professor_group_add', 'StdManagement@group_add');

//교수 그룹 삭제
Route::post('/Professor_group_delete', 'StdManagement@group_delete');

//교수 홈 학생 내정 정보
Route::post('/Professor_callStudent', 'Professor_home@callStudent');

//교수 홈 학생 내정 정보
Route::post('/Professor_home_schedule', 'Professor_home@professor_schedule');


//교수 홈 메인차트
Route::post('/professor_home_main_chart', 'Professor_home@mainChart');
/*
//교수 홈 면접일정
Route::post('/professor_home_interview_schedule', 'Professor_home@interview_schedule');

//교수 홈 학생 정보
Route::post('/professor_home_interview_StudentData', 'Professor_home@interviewStudentData');
*/

//교수 기업 리스트
Route::post('/professor_companylist', 'Professor_companyInfo@company_list');

//교수 기업 정보
Route::post('/professor_companyInfo', 'Professor_companyInfo@company_Info');

//교수 지원관리(차트)
Route::post('/professor_newChart_companyInfo', 'ApplyManagement@newChart');

//교수 지원관리(지원 승낙, 대기, 거절)
Route::post('/professor_apply_permission_change', 'ApplyManagement@accept_change');

//교수 학생 내정 확정
Route::post('/professor_acceptance_ox', 'StdManagement@stdFixCompany');

//교수 내정 현황
Route::post('/professor_statusManagement', 'ApplyManagement@newChart_acceptance');

//교수 내정 관리-면접 결과 / 검색
Route::post('/professor_interview_result_search', 'Professor_StatusManagement@interview_result_search');

//교수 학생 관리 이력서/포트폴리오 선택
Route::post('/professor_resum_portfolio', 'Professor_resum_portfolio@resum_portfolio_list');

//교수 면접 진행하는 회사별 학생 관리
Route::post('/Professor_totalCompanyList', 'ApplyManagement@Professor_totalCompanyList');

//교수 회사 지원 내역
Route::post('/Professor_ApplyInfo', 'ApplyManagement@professor_ApplyInfo');

// 교수 학생 이력서 OR 포트폴리오 다운로드
Route::post('/studentViewResumePortfolio', 'Professor_StudentViewResumePortfolioController@applyStudentResumeOrPortfolioDownload');

//교수 캘린더
Route::post('/Professor_calender_info', 'Professor_calender@professor_schedule');

// 교수 학생 지원 확정
Route::post('/Professor_real_accept', 'ApplyManagement@real_accept');

/****************************************************** 에이전트 *******************************************************/

//기업 대분류, 소분류,  업무, 복지, 인재상,  면접 내용 항목 관련
//항목 업로드
Route::post('/item/create' , 'ItemController@create');

//항목 삭제
Route::post('/item/delete', 'ItemController@delete');

//항목 조회
Route::post('/item/listUp', 'ItemController@listUp');

//항목 업데이트
Route::post('/item/update', 'ItemController@update');

//국가리스트
Route::post('/continent/getCountries', 'CountryController@getCountries');

//일본 행정 구역 리스트
Route::post('/japan/getTodouhuken', 'CountryController@getTodouhuken');


//올해 영업 체결 학교인지 체크
Route::post('/agent/isThisYearCollege', 'AgentController@isThisYearCollege');

//올해 학교 영업 체결 하기
Route::post('/agent/setThisYearCollege', 'AgentController@setThisYearCollege');

//올해 영업 체결 기업인지 체크
Route::post('/agent/isThisYearCompany', 'AgentController@isThisYearCompany');

//올해 학교 영업 체결 하기
Route::post('/agent/setThisYearCompany', 'AgentController@setThisYearCompany');

// 에이전트 - 프로필 호출
Route::post('/agent/getProfile', 'AgentController@getProfile');

// 에이전트 - 프로필 수정
Route::post('/agent/updateProfile', 'AgentController@updateProfile');

// 에이전트 - 프로필 수정
Route::post('/agent/updateOrgProfile', 'AgentController@updateOrgProfile');

//에이전트 프로필 사진 수정
Route::post('/agent/uploadImage', 'AgentController@uploadImage');

//에이전트 - 에이전트 생성
Route::post('/agent/create', 'AgentController@create');

//에이전트 - 회사 생성
Route::post('/company/create', 'CompanyController@create');

//에이전트 - 회사 정보 수정
Route::post('/company/update', 'CompanyController@update');

//에이전트 - 회사 정보 가져오기
Route::post('/company/getCompanyInfo', 'CompanyController@getCompanyInfo');

//에이전트 - 학교 생성
Route::post('/school/create', 'SchoolController@create');

//에이전트 - 학교 정보 수정
Route::post('/school/update', 'SchoolController@update');

//에이전트 - 학과 삭제
Route::post('/school/deleteFaculty', 'SchoolController@deleteFaculty');

//에이전트 - 학교 실적 정보 가져오기
Route::post('/agent/getCollegeInfo', 'AgentController@getCollegeInfo');

//에이전트 - 학교 정보 가져오기
Route::post('/school/getCollegeInfo', 'SchoolController@getCollegeInfo');

//에이전트 - 학과 실적 정보 가져오기
//Route::post('/school/getFacultyResultInfo', 'SchoolController@getFacultyResultInfo');

//에이전트 -학생 status 가져오기
Route::post('/agent/getStudentStatus', 'AgentController@getStudentStatus');

//에이전트 - 학교 ID에 따른 학과 리스트 가져오기
Route::post('/school/getfacultyList', 'SchoolController@getfacultyList');

//에이전트 - 학과 ID에 따른 학생 리스트 가져오기
Route::post('/school/getStudentList', 'SchoolController@getStudentList');

//에이전트 - 학생 추천 코멘트 기능
Route::post('/agent/updateRecommendationComment', 'AgentController@updateRecommendationComment');

//에이전트  - 학교/학과 검색
Route::post('/agent/searchCollege', 'AgentController@searchCollege');

//에이전트  - 기업 검색
Route::post('/agent/searchCompany', 'AgentController@searchCompany');

//에이전트 소속기관 가져오기
Route::post('/agent/getOrgs', 'AgentController@getOrgs');

//에이전트 아이디로 정보 가져오기
Route::post('/agent/getOrgAgentById', 'AgentController@getOrgAgentById');

//에이전트 - 학교 관계 정보 
Route::post('/agent/getOrgRelColInfo', 'AgentController@getOrgRelColInfo');

//에이전트 - 기업 관계 정보
Route::post('/agent/getOrgRelComInfo', 'AgentController@getOrgRelComInfo');

//에이전트 - 기업 담당자 주소록 리스트 
Route::post('/agent/getAgentBookCompanyList', 'AgentController@getAgentBookCompanyList');

//에이전트 - 기관 매칭
Route::post('/agent/orgMatching', 'AgentController@orgMatching');

//에이전트 - 기관 매칭 삭제
Route::post('/agent/deleteMatching', 'AgentController@deleteMatching');

//에이전트 - 기관 매칭 가져오기
Route::post('/agent/getMatching', 'AgentController@getMatching');

//에이전트 - 학교, 에이전트 아이디로 채용 목록 들고오기
Route::post('/agent/getEmploymentListByOrgCollegeId', 'AgentController@getEmploymentListByOrgCollegeId');

//에이전트 - 채용 id로 interview list 들고오기
Route::post('/agent/getInterviewList', 'AgentController@getInterviewList');

//에이전트 - 인터뷰 아이디로 지원 학생수 가져오기
Route::post('/agent/getApplyStudentListByInterviewId', 'AgentController@getApplyStudentListByInterviewId');

//에이전트 - 학생 면접 결과 입력
Route::post('/agent/setResult', 'AgentController@setResult');

//에이전트 - 불합격자 피드백 입력
Route::post('/agent/setFeedbackNoPassStudent', 'AgentController@setFeedbackNoPassStudent');

//에이전트 - 학생 합격
Route::post('/agent/setPassNextInterview', 'AgentController@setPassNextInterview');

//에이전트 - 내정 내기
Route::post('/agent/setAcceptance', 'AgentController@setAcceptance');

//에이전트 - 기관 매칭 아이디로 가져오기
Route::post('/agent/getMatchingById', 'AgentController@getMatchingById');

//에이전트, 기업 - 매칭에 따른 채용건 리스트 가져오기
Route::post('/company/getEmploymentList','CompanyController@getEmploymentList');

//에이전트 - 채용 결정/미결정
Route::post('/agent/setMatchingDecision', 'AgentController@setMatchingDecision');

//에이전트 - 채용 정보 생성
Route::post('/company/createRecruit', 'CompanyController@createRecruit');

//에이전트 - 채용 정보 수정
Route::post('/company/updateRecruit', 'CompanyController@updateRecruit');

//에이전트 - 채용 정보 삭제
Route::post('/company/deleteRecruit', 'CompanyController@deleteRecruit');

//에이전트 - 채용 정보 가져오기
Route::post('/company/getRecruit', 'CompanyController@getRecruit');


//에이전트 - 주소록 가져오기
//Route::post('/agent/getAddressBook', 'AgentController@getAddressBook');

//에이전트 - 주소록 등록
Route::post('/agent/createAddress', 'AgentController@createAddress');

//에이전트 - 주소록 삭제
Route::post('/agent/deleteAddress', 'AgentController@deleteAddress');

//에이전트 - 학교 학부 리스트 가져오기
Route::post('/agent/getFaculties', 'AgentController@getFaculties');

//에이전트 스케줄 등록
Route::post('/calendarFundamental_register', 'CalendarFundamental@interview_register');

//에이전트 스케줄 등록에서 회사 목록 가져오기
Route::post('/calendarFundamental_interview_company_list', 'CalendarFundamental@interview_company_list');

//에이전트 스케줄 등록에서 회사에 물린 학교 목록 가져오기
Route::post('/calendarFundamental_interview_college_list', 'CalendarFundamental@interview_college_list');

//에이전트 스케줄 등록대상이 될 채용 ID가져오기
Route::post('/calendarFundamental_interview_employment_id', 'CalendarFundamental@interview_employment_id');

//에이전트 스케줄 면접 전형 리스트 출력
Route::post('/calendarFundamental_interview_content_list', 'CalendarFundamental@interview_content_list');

//에이전트 스케줄 관련 학생 이름 출력
Route::post('/schedule_Registration_By_Student', 'CalendarFundamental@schedule_Registration_By_Student');

//에이전트 스케줄 면접 차수 출력
Route::post('/calendarFundamental_interview_count', 'CalendarFundamental@interview_count');

//에이전트 스케줄 수정(시간)
Route::post('/calendarFundamental_schedule_change', 'CalendarFundamental@interview_schedule_change');

//에이전트 스케줄 수정(날짜)
Route::post('/calendarFundamental_schedule_change_year', 'CalendarFundamental@interview_schedule_change_year');

//에이전트 스케줄 삭제
Route::post('/calendarFundamental_interview_schedule_delete', 'CalendarFundamental@interview_schedule_delete');

//에이전트 스케줄 수락
Route::post('/calendarFundamental_interview_schedule_agree', 'CalendarFundamental@interview_schedule_agree');

//에이전트 스케줄 가져오기
Route::post('/calendarFundamental_schedule_list', 'CalendarFundamental@interview_agent_list');

//에이전트 캘린더
Route::post('/agent_calender_info', 'AgentController@agent_calendar');

//교수 면접 장소 수정
Route::post('/calendarFundamental_interview_place_change', 'CalendarFundamental@interview_place_change');

//학생 면접 일정 출력
Route::post('/calendarFundamental_std_interview_schedule', 'CalendarFundamental@std_interview_schedule');

//학생 면접 시간 수정
Route::post('/calendarFundamental_std_addStdInterval', 'CalendarFundamental@addStdInterval');

//교수 지원관리(차트)
Route::post('/professor_newChart_companyInfo', 'ApplyManagement@newChart');
//교수 지원관리(면접 학생 리스트, 회사 면접 정보)
Route::post('/professor_interview_info_list', 'ApplyManagement@interview_info_list');





/****************************************************** 학생 *******************************************************/
//학생 소속 기관 아이디 얻기
Route::post('/student/getOrgId', 'StudentController@getOrgId');

//학생 정보 얻기
Route::post('/student/getStudentInfo', 'StudentController@getStudentInfo');


//학생 PR 비디오 주소 얻기
Route::post('/student/getVideoSrc', 'StudentController@getVideoSrc');

//학생 PR 비디오 주소 수정
Route::post('/student/updateVideoSrc', 'StudentController@updateVideoSrc');

//기본 정보 얻기
Route::post('/student/getProfileInfo', 'StudentController@getProfileInfo');

//기본 정보 수정
Route::post('/student/updateProfileInfo', 'StudentController@updateProfileInfo');

//소속 얻기
Route::post('/student/getAffiliationInfo', 'StudentController@getAffiliationInfo');


//공인 외국어 얻기
Route::post('/student/getLangInfo', 'StudentController@getLangInfo');

//공인 외국어 수정
Route::post('/student/updateLangInfo', 'StudentController@updateLangInfo');

//학생 기타 외국어 얻기
Route::post('/student/getLangMatchInfo', 'StudentController@getLangMatchInfo');

//학생 기타 외국어 수정
Route::post('/student/updateLangMatchInfo', 'StudentController@updateLangMatchInfo');



//스킬 항목 조회
Route::post('/item/skillListUp', 'ItemController@skillListUp');

//학생 스킬 얻기
Route::post('/student/getSkillMatchInfo', 'StudentController@getSkillMatchInfo');
//학생 스킬 수정
Route::post('/student/updateSkillMatchInfo', 'StudentController@updateSkillMatchInfo');


//학생 어필 문장 및 흥미 있는 분야 얻기
Route::post('/student/getAppealInfo', 'StudentController@getAppealInfo');

//학생 어필 문장 및 흥미 있는 분야 수정
Route::post('/student/updateAppealInfo', 'StudentController@updateAppealInfo');

//프로필 이미지 수정
Route::post('/student/updateProfileImage', 'StudentController@updateProfileImage');

//기업+채용정보 리스트업
Route::post('/student/getEmploymentList', 'StudentController@getEmploymentList');

//기업 채용정보 검색
Route::post('/student/searchCompanyEmploymentList', 'StudentController@searchCompanyEmploymentList');



//기업 지원 체크하기
Route::post('/student/createApplyCheck', 'StudentController@createApplyCheck');

//기업 지원하기
Route::post('/student/createApply', 'StudentController@createApply');

//지원 취소하기
Route::post('/student/deleteApply', 'StudentController@deleteApply');

//내정 수락
Route::post('/student/setAcceptance', 'StudentController@setAcceptance');

//내정 사퇴
Route::post('/student/setResignation', 'StudentController@setResignation');

//내정 수락 및 사퇴 결정 취소
Route::post('/student/removeDecision', 'StudentController@removeDecision');

//레포지토리 가져오기
Route::post('/student/getRepositoryInfo', 'StudentController@getRepositoryInfo');

//레포지토리 추가하기
Route::post('/student/createFolder', 'StudentController@createFolder');

//레포지토리 수정하기
Route::post('/student/updateFolder', 'StudentController@updateFolder');

//레포지토리 삭제하기
Route::post('/student/deleteFolder', 'StudentController@deleteFolder');


//학생 status 얻기
Route::post('/student/getStatus', 'StudentController@getStatus');


//푸시알림 토큰 저장
Route::post('/push_token_save', 'PushNotify@push_token_save');

//푸시알림 보내기 예시
Route::post('/push_send', 'PushNotify@push_send');

//알림
Route::post('/notify_list', 'Notify@notification_list');

//선택 알림 삭제
Route::post('/notify_select_delete', 'Notify@notification_select_delete');

//해당 유저의 누적알림 모두 지우기
Route::post('/notify_all_delete', 'Notify@notification_all_delete');
