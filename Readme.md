<h1> 필독 사항 </h1>

<ol>
    <h2> Git Command.md 필독! </h2>
    <li> PHP Version : 7.0.0  </li>
    <li> Lravel Version : 5.5 </li>   
         저장소 clone && pull 받은 후 반드시 
        <pre> Composer install && npm install </pre>
        실행 후 작업 시작할 것 !<br> .gitignore 파일에 node_modules 랑 vender 파일이 들어가 있기 때문에 라이브러리 등을 새롭게 다운로드 해주어야 함
    </li>
</ol>

<h2> File Structure </h2>
<pre>  Git Hub 특성 상 폴더에 아무 파일도 없으면 안올라가니 이 구조 꼭 참고해서 없는 폴더는 만들어 작업할 것 !! 
        - resource -> assets -> js
        - components
            - professor
            - student
            - agent
            - company 
        - config
            - router : view router
            -  
        - shared : 모든 계층에서 공통적으로 사용될 components 
        - static
            - css
            - image
            - language : 언어 팩 설정 
</pre>
  