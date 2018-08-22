<h1>Git Cli Command</h1>

<h2> 원격 브랜지 접속 시 확인사항  </h2>
    
    1. git remote update
        원격 브랜치를 찾지 못해 발생하는 retal : Cannot update paths and switch to branch 
        'feature/rename' at the same time.오류 메세지 해결 
        
    2. git fetch 
        원격 branch 목록은 자동으로 최신 상태로 유지되지 않음.
        따라서 해당 명령어로 목록을 업데이트 할 필요가 있다.      
        
    3. 원격 저장소 branch  확인
        - git branch // 현재 브랜치 확인 
        - git branch -r  //원격 저장소 branch 목록 출력
        - git branch -a  //원격 / 로컬 저장소 branch 목록 모두 출력        
        
    4.원격 저장소 branch 가져오기 
        - git checkout -t origin/{branch name} 
        - 해당 명령어를 통해 로컬에 동일한 이름의 branch를 생성하면서 해당 branch로 checkout 하게 됨 
        - chekout 은 branch & tag 로 작업트리를 변경하는 명령어임 <br> 옵션 -b 도 있음


<h2> git cli command 절차 </h2>
<pre>    
    1. Init
        - 폴더 생성
        - git init // 통해 폴더 초기화
        - git remote update   
        - git fetch 
        
    2. Git remote Repository 등록
        - git remote add git@gitlab.com:misamatsuda/lightourfuture.git //
        
    3. 자신의 branch 가져오기
        - git branch -a // branch 목록 확인 
        - git checkout -t origin/{branch name}
        - -b 옵션을 주면 체크아웃 하려는 브랜치가 없을 시 master 기반으로 새로운 브랜치를 생성  
        - 또는 git clone 명령어를 통해서 project 자체를 복사한 후 해당 project 안에서 branch 이동
        - git clone git@gitlab.com:misamatsuda/lightourfuture.git
        // 이렇게 하면 전체를 받아 그 안에서 브랜치 이동이 자유로움 

    4. 작업 전 최신 사항 git pull 을 통해 내려받기     
    5. Coding
         
    6. 저장소에 push 하기
        - push 전에 항상 해야할 것이 있음 !!!! 이것을 지키지 않을 시 프로젝트가 박살날 지도 모름 
            1. push 전 항~~~~~상 pull 을 실행해 최신 코드 사항을 확인한 후 push 할 것 !!!!!
            2. pull 시 파일 내부에서 내가 수정한 부분과 다른사람이 수정한 부분이 겹칠 시 CONFLICT ERROR발생 
            이 때 CONFLICT(content) : merge conflict in lightourfuture/Status.md 이러한 
            error message 출력. 따라서 수작업으로 수정 후 다시 push!! 
            git status 명령어 해보면 더 잘보임 해당 file 열면 중첩 수정부분 다 보임 
            *** 안하면 push 안됨 ***
            - git add {수정된 파일명}
        - git status // 수정된 파일 스테이징 확인  
        - git commit -m "{massage}" // commit massage 작성
        - git push -u origin "branch name"
    7. Merge 
        1. 현재 브랜치의 작업 내용을 상위 또는 다른 브랜치에 병합시키는 과정
        2. git merge <{branch name}> 명령어를 사용 
            - merge 에는 3가지 방법이 있음 
                1. git merge (바로 합치기) : 하나의 브랜치와 다른 브랜치 변경 이력 전체 병합
                2. git merge --squash (커밋 합치기) : 하나의 브랜치의 이력을 압축해 다른 브랜치의 
                   최신 커밋 하나로 만드는 방법                   
                3. git cherry-pick {commit 명} (선택 합치기) : 다른 브랜치에서 하나의 커밋을 가져와 현재
                   브랜치에 적용하는 방법 
            ex) backend branch 의 개발이 끝나 develop 브랜치로 병합 
                - merge 받을 branch 로 checkout ! 
                - git merge backend (현재 develop branch)
                - develop 에서 merge 사항 commit / push 
                - frontend 단 작업중인 사람 => git pull origin/develop
    8. 참고 페이지
        - https://mylko72.gitbooks.io/git/content/restore/repos.html
            - Rebase 
            - Revert (파일 복원 && 전체 복원)
            - Reset 은 꼭 봐주시길 ~~!
            
</pre>
<hr>

<h2> Current Git Branch Structure </h2>
<pre>
    Master
        -develop
            -backend
                - 필요 시 사람 별 || 기능 별 branch 생성 예정
            -frontend  
                - 필요 시 사람 별 || 기능 별 branch 생성 예정
</pre>
<hr>
<h2> Occation </h2>
<pre>
    1. 소스 변경사항 확인 
        - 현재 {호형} backend 브랜치에서 작업 중 
        - {준수} 가 frontend 단 작업 완료 후 push / develop 로 merge
        - {호형} 은 현재 develop 에 등록된 최신 frontend 단의 작업 내용이 필요
        - {호형} => git pull origin develop 후 작업 재시작
        git push origin -f frontend_practice:frontend_JS
</pre>

