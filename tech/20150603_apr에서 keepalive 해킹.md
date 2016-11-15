# apr에서 keepalive 해킹
###### 2015/06/03

먼저 TCP keepalive에 대해 알아보자

http://www.tldp.org/HOWTO/html_single/TCP-Keepalive-HOWTO/

|||
|-----|-----|
|keepalive를 켜면|소켓을 쓰고 나서 안 끊는다.3hs 비용을 절약하기 위함이다.|
|keepalive timeout| 이 시간 동안 통신이 음써도 안 끊는다.<br>리눅스 시스템 설정에서 만질 수도 있고, 개별 소켓 옵션에도 적용 할 수 있다.|
|keepalive interval| 이 시간 마다 탐침 패킷을 보내서 상대편이 죽었는지 본다.|
|keepalive probe | 이 만치 실패하면 상대편이 죽었다고 판단한다.|


이렇게 보자.

```bash
cat /proc/sys/net/ipv4/tcp_keepalive_

tcp_keepalive_intvl tcp_keepalive_probes tcp_keepalive_time
```


echo 파이프로 보내면 설정을 바꿀 수도 있다. sysctl에 적용하면 재부팅시에도 적용이 된다.


개별소켓에 적용할 때는 위 URL을 참고하고, 라이브러리로는 아래 것을 참고한다.

http://libkeepalive.sourceforge.net/

LD_PRELOAD를 쓰고 있다. .so에 있는 API를 래핑하여 선행 호출 할 수 있다.


APR에서는 하드코어 해킹을 할 수 있다. APR은 keepalive를 지원하지만, 타임아웃, 인터벌등은 지정할 수 없다. 예를 들어 소켓 옵션은 다음과 같이 지정한다.

```c
apr_socket_opt_set(s, APR_SO_NONBLOCK | APR_SO_KEEPALIVE
 | APR_SO_REUSEADDR, 1);
```

이때 설정 옵션에 keepalive timeout이 없다. 따라서 sysctl로 조정하는 것이 유일하지만, 바꾸고 싶다면 해킹을 좀 해야 한다.


다음은 일반적인 소켓 프로그래밍의 킵얼라이브 타이밍 조정 코드다.

```c
int optval = 100;
int optlen = sizeof(optval);

if(setsockopt(s, SOL_TCP, TCP_KEEPINTVL, &optval, optlen) < 0) { 
	print_error(setsockopt());
}
```

여기서 s는 소켓 번호인데, apr은 추상화를 거치면서 보이지가 않는다. s는 <code>apr_socket_t</code> 구조체 안에 있으나 이 구조체는 추상화를 통해 은닉되어 있어서 전체 구조를 볼 수 있는 헤더파일 조차 참조하지 않는다. (사상은 맘에 든다) 해당 헤더를 가져와서 인클루드 하기에는 덩치가 크고, 의존성 때문에 빌드 에러가 와장창 떨어진다. 원래의 모양은 복잡하지만 s만 쓰면 된다. 그래서 아래와 같이 정의한다.


```c
struct apr_socket_t { 
	apr_pool_t *pool; 
	int socketdes;  // 얘만 쓰면 됨. 
};
```

실제 구조체는 이 보다 훨씬 길다. 그런데 어차피 포인터의 오프셋만 알면 되기 때문에... 뒤는 안 써도 된다. 이렇게 s를 구해서 keepalive 해킹이 가능하다.


keepalive 를 쓰려면 <code>#include <netinet/tcp.h></code> 를 인클루드 해야 한다.

