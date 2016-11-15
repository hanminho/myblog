# 초심플 self spin lock
###### 2015/05/27

이 글을 보는 분이라면 spin lock의 위험성은 다들 알고 있을 거심니다. (블로그 옮기고 일간 방문자가 평균 1명이네요. 글쓴이 본인... 지못미.. )


### 장점

반응 속도가 빠름.
락 타임이 짧다면 가장 효율적임.

### 단점

CPU 쳐먹음.
OS 스케쥴러 구현에 따라 병신같을 수 있음.


### 그런데?

요즘 CPU 코어 수 많음.
쓰레드 만들 때 CPU 지정 가능함.
스핀락에서 OS가 CSW를 하면서 CPU를 돌아다니지는 않음. 한 놈만 패. (...아닐지도..)
다음은 rb_tree 멀티쓰레드로 돌리다가 주섬주섬 여기저기 디벼서 시행착오로 이러쿵저러쿵 소스임. gcc에서만 될 듯. 다른 컴파일러 + 아키텍쳐엔 저 아토믹 펑션을 해 본 적이 엄서요..

```c
inline void tree_lock(volatile int *locked) 
{
	while (__sync_val_compare_and_swap(locked, 0, 1)); 
	asm volatile(lfence ::: memory); 
}


inline void tree_unlock(volatile int *locked) { 
	*locked = 0; 
	asm volatile(sfence ::: memory); 
}
```

자 이 스핀락이 오랫동안 홀드됐다면 그 CPU는 혼자 다 먹을꺼에요. OS가 중간중간 CSW는 하것지만.. 연산/분석이 중점인 서비스라면, 스파크 RDD 처럼 읽을 때는 멀티쓰레드에 락걸고 읽고, 그 다음에는 일체의 쓰기 없이 락없이 읽는 쪽이 좋을 것 같네요.


궁극적으로는 CAS rb tree를 써야겠지만.. 구현체는 못찾았고, 논문만 가지고는 이 몸의 실력으로는 도저히 구현 몬하겠네요.


