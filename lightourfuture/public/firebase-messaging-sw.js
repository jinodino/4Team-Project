// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here, other Firebase libraries
// are not available in the service worker.
importScripts("https://www.gstatic.com/firebasejs/4.8.1/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/4.8.1/firebase-messaging.js");

// Initialize the Firebase app in the service worker by passing in the
// messagingSenderId.
firebase.initializeApp({
  messagingSenderId: "368914381613"
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();

//fcm 서버에서 푸시 메시지가 오면 푸시창 띄우기
self.addEventListener("push", function (event) {
  //console.log("[Service Worker] Push Received.");

  var response_data = JSON.parse(event.data.text());
  let titleData = JSON.parse(response_data["notification"]["title"]);

  const title = titleData["title"]; //response_data["notification"]["title"];
  const options = {
    body: response_data["notification"]["body"],
    icon: "/KakaoTalk_20180618_213403704.png",
    badge: "/KakaoTalk_20180618_213403704.png",
    data: titleData["data"]
  };

  event.waitUntil(self.registration.showNotification(title, options));
});

//푸시알림 클릭시 진행하는 이벤트
self.addEventListener("notificationclick", function (event) {
  event.notification.close();

  clients.openWindow("https://lightourfuture.world");
  /*
  console.log(event.notification.data);
  //푸시 알림 클릭시 웹 페이지 이동
  if (event.notification.data == "Tester") {
    //테스트용
    event.waitUntil(
      //clients.openWindow("https://www.naver.com")
      clients.openWindow("https://lightourfuture.world")
    );
  } else if (event.notification.data == "student") {
    //각 유저 계층별 이동(학생)
    event.waitUntil(
      //clients.openWindow("https://lightourfuture.world/#/student")
      clients.openWindow("https://lightourfuture.world")
    );
  } else if (event.notification.data == "professor") {
    //각 유저 계층별 이동(교수)
    event.waitUntil(
      //clients.openWindow("https://lightourfuture.world/#/professor")
      clients.openWindow("https://lightourfuture.world")
    );
  } else if (event.notification.data == "company") {
    //각 유저 계층별 이동(회사)
    event.waitUntil(
      //clients.openWindow("https://lightourfuture.world/#/company")
      clients.openWindow("https://lightourfuture.world")
    );
  } else if (event.notification.data == "agent") {
    //각 유저 계층별 이동(에이전트)
    event.waitUntil(
      //clients.openWindow("https://lightourfuture.world/#/agent")
      clients.openWindow("https://lightourfuture.world")
    );
  }*/
});

console.log("서비스 워커 실행"); //서비스 워커가 실행되는지 확인하는 로그