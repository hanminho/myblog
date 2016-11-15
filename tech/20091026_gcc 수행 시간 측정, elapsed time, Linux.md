# gcc 수행 시간 측정, elapsed time, Linux
###### 2009/10/26

일반적인 수행 시간 측정.

```c
struct timeval tv;
long t1, t2;
gettimeofday(&tv, NULL);
t1 = tv.tv_sec * 1000 + tv.tv_usec / 1000;
// 시작

gettimeofday(&tv, NULL);
t2 = tv.tv_sec * 1000 + tv.tv_usec / 1000;
// 끝
```
나머지는 지지고 볶고..
