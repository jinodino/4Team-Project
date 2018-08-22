"use strict"

/**
 * @author YJC 3WDJ CapstoneTeam 4
 * @argument CurrentUserClaasification
 * 
 */

/**
 * @description Global Components
 */
import Index from "../../shared/Index.vue"; //Index page 
import FindAccount from "../../shared/FindAccount.vue";
import NotFound from "../../shared/404p.vue";
import StdRegist from "../../shared/StdRegist.vue";
import CommonRegist from "../../shared/commonRegist.vue";
import NewIndex from "../..//shared/newIndex.vue";
import Main from "../../shared/Main.vue"
import Login from "../../shared/Login.vue";
import Recruit from "../../shared/Recruit.vue";
import CalendarFoudamental from "../../shared/CalendarFundamental.vue";
import SharedCompanyInfo from "../../shared/CompanyInfo.vue";
import SharedCollegeInfo from "../../shared/CollegeInfo.vue";

//import Calendar from "../../shared/Calendar.vue";


// ???? 
import Email from "../../shared/Email.vue";
import aaa from "../../shared/DragAndDrop.vue";

/**
 * @param {Classification : Professor}
 */
import ProfessorHome from "../../components/professor/Home.vue";
import ProfessorProfile from "../../components/professor/Profile.vue";
import StdManagement from "../../components/professor/StdManagement.vue";
import CompanyInfo from "../../components/professor/CompanyList.vue";
import ProfessorCalendar from "../../components/professor/Calendar.vue";
import ApplyManagement from "../../components/professor/ApplyManagement.vue";
import StatusManagement from "../../components/professor/StatusManagement.vue";
import Testt from "../../shared/CalendarFundamental.vue";

/**
 * @param {Classification : Company}
 */
// import CompanyHome from "../../components/company/DashBoard.vue";
import CompanyOrgProfile from "../../components/company/Profile.vue"
import CompanySchedule from "../../components/company/Schedule.vue"
import CompanySchoolList from "../../components/company/SchoolList.vue";
import RecruitStatus from "../../components/company/RecruitStatus.vue";
import NominateManagement from "../../components/company/NominateManagement.vue";

/**
 *  @param {Classification : Student}
 */
import StudentHome from "../../components/student/StudentHome.vue";
import StudentProfile from "../../components/student/StudentProfile.vue";
import StudentRepository from "../../components/student/StudentRepository.vue";
import StudentCompanyList from "../../components/student/StudentCompanyList.vue";
import StudentStatus from "../../components/student/StudentStatus.vue";
import StudentCalendar from "../../components/student/StudentCalendar.vue";
import StudentCompanyInfo from "../../components/student/CompanyEmploymentInfo.vue";
import StudentPR from "../../components/student/StudentPR.vue";

/**
 *  @param {Classification : Agent}
 */
// import AgentHome from "../../components/agent/AgentHome.vue";
import AgentProfile from "../../components/agent/AgentProfile.vue";
// import AgentOrgProfile from "../../components/agent/AgentOrgProfile.vue";
import AgentRelations from "../../components/agent/AgentRelations";
import UserInvite from "../../components/agent/UserInvite.vue";
import EmailRegist from "../../components/agent/EmailRegist.vue"
import AgentRegist from "../../components/agent/AgentRegist.vue"
import CollegeRegist from "../../components/agent/SchoolRegist.vue";
import AgentSchoolList from "../../components/agent/SchoolList.vue";
import CompanyRegist from "../../components/agent/CompanyRegist.vue";
import CompanyList from "../../components/agent/CompanyList.vue";
import AgentStdManage from "../../components/agent/AgentStdManage.vue";
import AgentItvManage from "../../components/agent/AgentItvManage.vue"
import AgentCalendar from "../../components/agent/AgentCalendar.vue";
import RecruitRegist from "../../components/agent/RecruitRegist.vue";
import ListManagement from "../../components/agent/Management.vue";
import AgentMatchingInfo from "../../components/agent/AgentMatchingInfo.vue";
import AgentCompanyInfo from "../../components/agent/AgentCompanyInfo.vue";
import AgentCollegeInfo from "../../components/agent/AgentCollegeInfo.vue";
import AgentRecruitInfo from "../../components/agent/AgentRecruitInfo.vue";

//Testing
import UserTest from "../../shared/UserTest.vue";
import ATagTest from "../../shared/HaloRoutingTest.vue";
import Test from "../../components/agent/test.vue";
import Excel from "../../shared/Excel.vue";
import testmap from "../../shared/testmap.vue";
import TesttingPage from "../../shared/TesttingPage.vue";
import a from "../../components/agent/SchoolListUp.1.vue";

//console.log("load : " + window.location.hash.split('#')[1]);\
export const ROUTING = [

    { path: "/", component: Index, name: "index" },
    { path: "*", component: NotFound, name: "notFound" },
    { path: "/stdRegist", component: StdRegist, name: "stdRegist" },
    { path: "/findAccount", component: FindAccount, name: "findAccount" },
    { path: "/newIndex", component: NewIndex, name: "newIndex" },
    { path: "/login", component: Login, name: "login" },
    { path: "/StdProfile", component: StudentProfile, name: "StdProfile", },
    { path: "/mail", component: Email, name: "email" },
    { path: "/atagTesting", component: ATagTest, name: "atagTest" },

    //testing 
    { path: "/userTest", component: UserTest, name: "usertest" },
    { path: "/regist/:date/:classification/:inclusion/:agent/:no", component: CommonRegist, name: "commonRegist" },
    { path: "/test", component: Test, name: 'test' },
    { path: "/excel", component: Excel, name: "excel" },
    { path: "/testmap", component: testmap, name: "testmap" },
    { path: "/TesttingPage", component: TesttingPage, name: "TesttingPage" },
    { path: "/a", component: a, name: "a" },

    {
        path: "/professor",
        component: Main,
        children: [{
            path: "/professor",
            components: {
                ProfessorHome: ProfessorHome
            }
        }],
    },

    {
        path: "/professor/profile",
        component: Main,
        children: [{
            path: "/professor/profile",
            components: {
                ProfessorProfile: ProfessorProfile,
            }
        }],
    },

    {
        path: "/professor/stdManagement",
        component: Main,
        children: [{
            path: "/professor/stdManagement",
            components: {
                StdManagement: StdManagement,
            }
        }],
    },

    {
        path: "/professor/companyList",
        component: Main,
        children: [{
            path: "/professor/companyList",
            components: {
                CompanyInfo: CompanyInfo,
            }
        }],
    },

    {
        path: "/professor/companyInfo",
        component: Main,
        children: [{
            name: 'profCompanyInfo',
            props: true,
            path: "/professor/companyInfo",
            component: SharedCompanyInfo,
        }],
    },


    {
        path: "/professor/applyManagement",
        component: Main,
        children: [{
            path: "/professor/applyManagement",
            components: {
                ApplyManagement: ApplyManagement,
            }
        }],
    },

    {
        path: "/professor/calendar",
        component: Main,
        children: [{
            path: "/professor/calendar",
            components: {
                ProfessorCalendar: ProfessorCalendar,
            }
        }],
    },

    {
        path: "/professor/statusManagement",
        component: Main,
        children: [{
            path: "/professor/statusManagement",
            components: {
                StatusManagement: StatusManagement,
            }
        }],
    },

    {
        path: "/professor/calendar",
        component: Main,
        children: [{
            path: "/professor/calendar",
            components: {
                ProfessorCalendar: ProfessorCalendar,
            }
        }],
    },

    {
        path: "/professor/testt",
        component: Testt
    },

    {
        path: "/professor/statusManagement",
        component: Main,
        children: [{
            path: "/professor/statusManagement",
            components: {
                StatusManagement: StatusManagement,
            }
        }],
    },

    {
        path: "/student",
        component: Main,
        children: [{
            path: "/student",
            components: {
                StudentHome: StudentHome,
            }
        }],
    },

    {
        path: "/student/prPage",
        component: Main,
        children: [{
            path: "/student/prPage",
            components: {
                StudentPR: StudentPR,
            }
        }],
    },

    {
        path: "/student/profile",
        component: Main,
        children: [{
            path: "/student/profile",
            components: {
                StudentProfile: StudentProfile,
            }
        }],
    },

    {
        path: "/student/repository",
        component: Main,
        children: [{
            path: "/student/repository",
            components: {
                StudentRepository: StudentRepository,
            }
        }],
    },

    {
        path: "/student/companylist",
        component: Main,
        children: [{
            path: "/student/companylist",
            components: {
                StudentCompanyList: StudentCompanyList,
            }
        }],
    },

    {
        path: '/student/companyInfo',
        component: Main,
        children: [{
            name: 'studentCompanyInfo',
            props: true,
            path: '/student/companyInfo',
            component: StudentCompanyInfo,
        }],
    },

    {
        path: "/student/status",
        component: Main,
        children: [{
            path: "/student/status",
            components: {
                StudentStatus: StudentStatus,
            }
        }],
    },
    {
        path: "/student/calendar",
        component: Main,
        children: [{
            path: "/student/calendar",
            components: {
                StudentCalendar: StudentCalendar,
            }
        }],
    },

    /**
     * @param { Company }
     */

    {
        path: "/company",
        component: Main,
        children: [{
            path: "/company",
            components: {
                CompanyOrgProfile: CompanyOrgProfile,
            }
        }]
    },
    
    {
        path: "/company/recruitStatus",
        component: Main,
        children: [{
            path: "/company/recruitStatus",
            components: {
                RecruitStatus: RecruitStatus,
            }
        }]
    },
    {
        path: "/company/schoolList",
        component: Main,
        children: [{
            path: "/company/schoolList",
            components: {
                CompanySchoolList: CompanySchoolList,
            }
        }]
    },
    {
        path: "/company/schedule",
        component: Main,
        children: [{
            path: "/company/schedule",
            components: {
                CompanyCalendar: CompanySchedule,
            }
        }]
    },
    {
        path: "/company/nominateManagement",
        component: Main,
        children: [{
            path: "/company/nominateManagement",
            components: {
                NominateManagement: NominateManagement,
            }
        }]
    },

    {
        path: "/agent",
        component: Main,
        children: [{
            path: "/agent",
            components: {
                AgentProfile: AgentProfile,
            }
        }],
    },
    {
        path: '/agent/profile',
        component: Main,
        children: [{
            path: '/agent/profile',
            components: {
                AgentProfile: AgentProfile,
            }
        }],
    },

    {
        path: '/agent/userinvite',
        component: Main,
        children: [{
            path: '/agent/userinvite',
            components: {
                UserInvite: UserInvite,
            }
        }],
    },
    {
        path: '/agent/emailregist',
        component: Main,
        children: [{
            path: '/agent/emailregist',
            components: {
                EmailRegist: EmailRegist
            }
        }],
    },
    {
        path: '/agent/agentregist',
        component: Main,
        children: [{
            path: '/agent/agentregist',
            components: {
                AgentRegist: AgentRegist
            }
        }],
    },
    {
        path: '/agent/collegeregist',
        component: Main,
        children: [{
            name: 'collegeregist',
            path: '/agent/collegeregist',
            props: true,
            component: CollegeRegist,
        }],
    },

    {
        path: '/agent/collegeUpdate',
        component: Main,
        children: [{
            name: 'collegeUpdate',
            path: '/agent/collegeUpdate',
            props: true,
            component: CollegeRegist,
        }],
    },

    {
        path: '/agent/schoollist',
        component: Main,
        children: [{
            path: '/agent/schoollist',
            components: {
                SchoolList: AgentSchoolList,
            }
        }],
    },

    {
        path: '/agent/companyregist',
        component: Main,
        children: [{
            name: 'companyregist',
            path: '/agent/companyregist',
            props: true,
            component: CompanyRegist
        }],
    },

    {
        path: '/agent/companyUpdate',
        component: Main,
        children: [{
            name: 'companyUpdate',
            path: '/agent/companyUpdate',
            props: true,
            component: CompanyRegist
        }],
    },

    {
        path: '/agent/companylist',
        component: Main,
        children: [{
            path: '/agent/companylist',
            components: {
                CompanyList: CompanyList,
            }
        }],
    },

    {
        path: '/agent/matchingInfo',
        component: Main,
        children: [{
            name: 'agentMatchingInfo',
            props: true,
            path: '/agent/matchingInfo',
            component: AgentMatchingInfo,
        }],
    },

    //채용정보 등록
    //    { path: "/agent/recruitregist", component: RecruitRegist, name: 'recruitregist' },

    {
        path: '/agent/recruitregist',
        // props: true,
        // name: 'recruitregist',
        component: Main,
        children: [{
            name: 'recruitregist',
            props: true,
            path: '/agent/recruitregist',
            component: RecruitRegist,
        }],
    },

    {
        path: '/agent/recruitUpdate',
        component: Main,
        children: [{
            name: 'recruitUpdate',
            path: '/agent/recruitUpdate',
            props: true,
            component: RecruitRegist
        }],
    },

    {
        path: '/agent/recruit',
        component: Main,
        children: [{
            name: 'recruit',
            props: true,
            path: '/agent/recruit',
            component: AgentRecruitInfo,
        }],
    },

    {
        path: '/agent/companyInfo',
        component: Main,
        children: [{
            name: 'agentCompanyInfo',
            props: true,
            path: '/agent/companyInfo',
            component: AgentCompanyInfo,
        }],
    },

    {
        path: '/agent/collegeInfo',
        component: Main,
        children: [{
            name: 'collegeInfo',
            props: true,
            path: '/agent/collegeInfo',
            component: AgentCollegeInfo,
        }],
    },


    {
        path: '/agent/stdmanage',
        component: Main,
        children: [{
            path: '/agent/stdmanage',
            components: {
                AgentStdManage: AgentStdManage,
            }
        }],
    },

    {
        path: '/agent/itvmanage',
        component: Main,
        children: [{
            path: '/agent/itvmanage',
            components: {
                AgentItvManage: AgentItvManage,
            }
        }],
    },

    {
        path: '/agent/calendar',
        component: Main,
        children: [{
            path: '/agent/calendar',
            components: {
                AgentCalendar: AgentCalendar,
            }
        }],
    },
    {
        path: '/agent/belongs',
        component: Main,
        children: [{
            path: '/agent/relations',
            components: {
                AgentRelations: AgentRelations,
            }
        }],
    },
    {
        path: '/agent/listmanagement',
        component: Main,
        children: [{
            path: '/agent/listmanagement',
            components: {
                ListManagement: ListManagement,
            }
        }],
    },
];