<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Company extends Model
{
    protected $table = 'org_companies';
    
    protected $fillable = [
        'org_company_id',           //회사 ID
        'manager_login_id',         //담당자 ID
        'org_name_kanji',           //조직이름 한자
        'org_name_kana',            //조직이름 후리가나
        'org_name_eng',             //조직이름 영어
        'country_code',             //국가코드
        'photo_url',                //로고사진URL
        'address_to',               //일본 주소 토
        'address_dou',              //일본 주소 도
        'address_hu',               //일본 주소 부
        'address_ken',              //일본 주소 현
        'address',                  //일본 주소 전체
        'homepage_url',             //홈페이지 URL
        'establish_date',           //설립년도
        'ceo_name_kanji',           //대표자 이름 한자
        'ceo_name_kana',            //대표자 이름 후리가나
        'worker_count',             //사원수
        'capital',                  //자본금
        'business_content',         //사업내용(500자)
        'company_atmosphere',       //사풍, 분위기(500자)
        'recommendation_comment',   //에이전트 추천 코멘트
        'employment_decision_ox',   //채용 참가 확정 여부
        'listed_company_ox'         //상장 여부
        ];

    protected $hidden = [
        'org_company_id'            //회사 ID
    ];
}
