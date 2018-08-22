var SidebarList = {

    users: [
        { title: 'student', value: 1 },
        { title: 'professor', value: 2 },
        { title: 'agent', value: 3 },
        { title: 'company', value: 4 },
    ],

    student: [
        // { title: '홈', icon: 'home', link: '/student' },
        { title: 'profile', icon: 'face', link: '/student/profile' },
        { title: 'レポジトリ', icon: 'folder', link: '/student/repository' },
        { title: 'PRページ', icon: 'folder', link: '/student/prPage' },
        { title: "企業リスト", icon: "insert_chart", link: '/student/companylist' },
        { title: "就活状況", icon: "insert_chart", link: '/student/status' },
        { title: '日程', icon: 'date_range', link: '/student/calendar' },
    ],

    professor: [
        { title: 'home', icon: 'grade', link: '/professor' },
        { title: 'profile', icon: 'perm_identity', link: '/professor/profile' },
        { title: 'stdManagement', icon: 'assignment_ind', link: '/professor/stdManagement' },
        { title: 'companyInfo', icon: 'account_balance', link: '/professor/companyList' },
        { title: 'calendar', icon: 'date_range', link: '/professor/calendar' },
        { title: 'applyManagement', icon: 'description', link: '/professor/applyManagement' },
        { title: 'statusManagement', icon: 'gavel', link: '/professor/statusManagement' },
    ],
    agent: [
        { title: 'profile', icon: 'face', link: '/agent/profile' },
        // { title: "회원 초대", icon: "group_add", link: '/agent/userinvite' },

        // { title: "에이전트 등록", icon: "group_add", link: '/agent/agentregist' },

        { title: '学校管理', icon: 'location_city', link: '/agent/schoollist' },
        { title: "企業管理", icon: "domain", link: '/agent/companylist' },
        { title: "学生管理", icon: "people", link: '/agent/stdmanage' },
        { title: "面接管理", icon: "feedback", link: '/agent/itvmanage' },
        { title: '日程管理', icon: 'date_range', link: '/agent/calendar' },
        { title: 'マッチング管理', icon: 'public', link: '/agent/relations' },
        { title: '環境設定', icon: 'settings', link: '/agent/listmanagement' },
    ],

    company: [
        { title: '企業情報', icon: 'face', link: "/company" },
        { title: '採用管理', icon: 'accessibility', link: "/company/recruitStatus" },
        { title: '内定管理', icon: 'check_circle', link: "/company/nominateManagement" },
        { title: '大学・学生リスト', icon: 'chat', link: "/company/schoolList" },
        { title: '日程管理', icon: 'date_range', link: "/company/schedule" },
    ],
}

export default SidebarList