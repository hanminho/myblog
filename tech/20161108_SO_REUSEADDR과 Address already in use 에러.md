# SO_REUSEADDR과 Address already in use 에러
###### 2016/11/08

소켓 프로그래밍 할 때, 프로그램이 죽었는데도 <code>Address already in use</code> 에러가 날 때가 있다. TIME_WAIT 단계로 남았기 때문인데, 자세한 내용은 구글링. 특히 존경하는 박상길님의 http://docs.likejazz.com/time-wait/ 글을 참고하자.

그래서 보편적인 해결방법은 SO_REUSEADDR을 쓰라는 것이다. TIME_WAIT의 목적 중 하나가 이것이다.

그런데 SO_REUSEADDR을 쓰고도 해결 못하는 경우가 있는데, 이것은 그냥 대부분 실수다. bind 하기 전에 설정해야 하는데, 오늘 본 어떤 예제 코드에서도 이것이 bind 뒤에 붙어 있었다. socket 생성하자 마자 설정해야 한다.

오늘의 리빙 포인트 : 바인드가 안 되면 코드를 깝시다.



