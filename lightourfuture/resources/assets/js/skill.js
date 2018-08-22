var skill = {
    skillList: [{
            key: 'OS',
            text: '운영체제',
            children: [{
                    key: 'Linux',
                    value: 0
                },
                {
                    key: 'Windows',
                    value: 0
                },
                {
                    key: 'Android',
                    value: 0
                }
            ]
        },
        {
            text: "컴퓨터 언어",
            key: 'conputer_lang',
            children: [{
                    key: 'c',
                    value: 0
                },
                {
                    key: 'c++',
                    value: 0
                },
                {
                    key: 'html',
                    value: 0
                },
                {
                    key: 'java',
                    value: 0
                },
                {
                    key: 'javascript',
                    value: 0
                },
                {
                    key: 'php',
                    value: 0
                },
                {
                    key: 'css',
                    value: 0
                }
            ]
        },
        {
            text: '데이터 베이스',
            key: 'db',
            children: [{
                    key: 'oracle',
                    value: 0
                },
                {
                    key: 'mysql',
                    value: 0
                }
            ]
        },
        {
            text: '서버',
            key: 'server',
            children: [{
                    key: 'apachi',
                    value: 0
                },
                {
                    key: 'tomcat',
                    value: 0
                },
                {
                    key: 'nodejs',
                    value: 0
                }
            ]
        },
    ],
    skillLevel: [{
            text: '最上',
            value: 1
        },
        {
            text: '上',
            value: 2
        },
        {
            text: '中',
            value: 3
        },
        {
            text: '下',
            value: 3
        },
    ]
}


export default skill;