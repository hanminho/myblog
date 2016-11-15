# /sbin/upstart와 init
###### 2016/11/02

init가 systemd로 바뀌고 멘붕에 빠진 개발자들이 많은 것으로 알고있따..

fork하면 부모가 원래는 init로 바뀌었다.  
현재는 /sbin/upstart로 간다.  


이렇게 된 이상 청와대로 간다.

... 헌데 알고보니 우분투 16.04는 아직 init를 쓰고 있다. 개별 사용자가 fork 하면 upstart가 부모가 된다. init.d/에 스크립트를 넣고 룰에 맞게 데몬으로 기동해야 ppid가 1로 가는 것 같다.
