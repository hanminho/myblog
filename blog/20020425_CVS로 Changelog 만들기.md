# CVS로 Changelog 만들기
###### 2002/04/25
  

change log 생성

```bash

cvs log [filename]
```

이건 이거고 헤더 주석에 줄줄 남기고 싶다면 헤더 주석을 이렇게 꾸며본다.

```c
/**
 * $Log: Option.h,v $* Revision 1.1 2006/09/27 06:36:36 dawnsea.shin
 * CVS 및 주석 추가
 * 디렉토리 구조는 개발 편의상 변경하지 않는다.
 *
 **/
```

핵심은 <code>$Log$</code>를 넣어두면 커밋 메시지가 자동으로 남는다는 것이다. 헤더가 너무 길어진다면 소스 하단에 넣어두는 것이 좋겠다.

원래는 더욱 더 거슬러 올라가 RCS기능이고 SVN에서는 안 된다.


