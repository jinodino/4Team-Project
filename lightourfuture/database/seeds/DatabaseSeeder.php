<?php

use Illuminate\Database\Seeder;
use App\country_code;
use App\model\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(resign_infosSeeder::class);
        $this->call(major_idSeeder::class);
        $this->call(country_codeSeeder::class);
        $this->call(Skill_field_info_code::class);
        $this->call(Business_small_infoSeeder::class);
        $this->call(japan_regions::class);
        $this->call(Business_Big_infoSeeder::class);
        $this->call(japan_prefectures::class);
        $this->call(Skill_LevelSeeder::class);
        $this->call(Users::class);
        $this->call(Skill_info_codeSeeder::class);
        $this->call(Language_infoSeeder::class);
        $this->call(Language_LevelSeeder::class);
        $this->call(Interview_infoSeeder::class);
        $this->call(Work_infoSeeder::class);
        $this->call(Welfare_infoSeeder::class);
        $this->call(Desired_employment_infoSeeder::class);
        $this->call(org::class);
        $this->call(Org_companySeeder::class);
        $this->call(Org_agentSeeder::class);
        $this->call(Org_collegeSeeder::class);
        $this->call(Org_imgSeeder::class);
        $this->call(Business_big::class);
        $this->call(AgentSeeder::class);
        $this->call(agent_belong::class);
        $this->call(agent_college_matching::class);
        $this->call(agent_company_matching::class);
        $this->call(FacultySeeder::class);
        $this->call(Org_matchingSeeder::class);
        $this->call(ProfessorSeeder::class);
        $this->call(Agent_book::class);
        $this->call(Employment_infoSeeder::class);
        $this->call(Desired_employment::class);
        $this->call(Interview_schedule::class);
        $this->call(Company_agentSeeder::class);
        $this->call(Welfare::class);
        $this->call(Work_matchings::class);
        $this->call(GroupSeeder::class);
        $this->call(Professor_bookSeeder::class);
        $this->call(StudentSeeder::class);
        $this->call(LanguageSeeder::class);
        $this->call(SkillSeeder::class);
        $this->call(ApplySeeder::class);
        $this->call(Business_samllSeeder::class);
        $this->call(Interview_resultSeeder::class);
        $this->call(Student_interested_field::class);  
    }
}
