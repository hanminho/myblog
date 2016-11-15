# 와꾸잡힌 memcached는 엄마도 못 바꾼다.
###### 2016/02/15

## 실험에 사용한 버전.

memcached 1.4.20, 로컬에서 빌드함.  


## 실행 옵션

```
./memcached -m 10M -o lru_crawler
```
10메가 사용에 LRU 크롤러 활성화  


## 초기 상황  

```
  #  Item_Size  Max_age   Pages   Count   Full?  Evicted Evict_Time OOM
```


## 10바이트 10개, 10초 짜리 삽입 후

```
  #  Item_Size  Max_age   Pages   Count   Full?  Evicted Evict_Time OOM
  1      96B        64s       1      10     yes        0        0    0
```

96바이트 슬랩에 10개가 들어갔음.


## 10바이트 100만개, 10초 짜리 삽입 후

메모리 full로 이빅션이 발생 하기 시작함.

```
  #  Item_Size  Max_age   Pages   Count   Full?  Evicted Evict_Time OOM
  1      96B         6s      10  109220     yes   296227        6    0
```


## 백만바이트, 1개, 60초 삽입

```
#  Item_Size  Max_age   Pages   Count   Full?  Evicted Evict_Time OOM
  1      96B        65s      10  109220     yes   533055       11    0
 42  1024.0K        32s       1       1     yes        0        0    0
```

1개 잘 들어갔음.


## 백만바이트, 2개, 60초 삽입

넣자마자 이빅션 됨. 즉 expired가 짧은 96바이트 slab에서 이빅션이 발생하지 않음.
```
#  Item_Size  Max_age   Pages   Count   Full?  Evicted Evict_Time OOM
  1      96B       139s      10  109220     yes   533055       11    0
 42  1024.0K         4s       1       1     yes        2        0    0
```


## 시간이 좀 더 흘러 50만 바이트 2개 삽입

짜투리 공간에 1개는 들어갔으나 1개는 이빅션 됨.
```
#  Item_Size  Max_age   Pages   Count   Full?  Evicted Evict_Time OOM
  1      96B       274s      10  109220     yes   533055       11    0
 40   602.5K         4s       1       1     yes        1        0    0
 42  1024.0K       139s       1       1     yes        2        0    0
```


## 시간이 좀 더 흘러 70만 바이트 1개 삽입

이빅션이 발생하진 않음.
```
#  Item_Size  Max_age   Pages   Count   Full?  Evicted Evict_Time OOM
  1      96B       321s      10  109220     yes   533055       11    0
 40   602.5K        51s       1       1     yes        1        0    0
 41   753.1K         3s       1       1     yes        0        0    0
 42  1024.0K       186s       1       1     yes        2        0    0
```


## 시간이 좀 더 흘러 70만 바이트 2개 삽입

2개가 바로 이빅션 됨.
```
#  Item_Size  Max_age   Pages   Count   Full?  Evicted Evict_Time OOM
  1      96B       113s      10  109220     yes    90780        5    0
 40   602.5K        59s       1       1     yes        1        0    0
 41   753.1K         1s       1       1     yes        2        0    0
 42  1024.0K        80s       1       1     yes        2        0    0
```


## 시간이 좀 더 흘러 900K 2개 삽입

1024 슬랩에서 이빅션이 1개 증가함. expire 슬롯을 재활용한 것으로 판단.
```
#  Item_Size  Max_age   Pages   Count   Full?  Evicted Evict_Time OOM
  1      96B       199s      10  109220     yes    90780        5    0
 40   602.5K       145s       1       1     yes        1        0    0
 41   753.1K        87s       1       1     yes        2        0    0
 42  1024.0K         2s       1       1     yes        3        0    0
```

## 시간이 좀 더 흘러 300K 20개 삽입

3개는 들어가고 17개가 이빅션 됨

```
#  Item_Size  Max_age   Pages   Count   Full?  Evicted Evict_Time OOM
  1      96B       262s      10  109220     yes    90780        5    0
 37   308.5K         2s       1       3     yes       17        0    0
 40   602.5K       208s       1       1     yes        1        0    0
 41   753.1K       150s       1       1     yes        2        0    0
 42  1024.0K        65s       1       1     yes        3        0    0
```

## 결론

- 초반에 멤캐시 와꾸가 잡히면 안 바뀐다.
- 몇 시간 후에 추이를 다시 볼 예정임.
- 이빅션 경쟁은 동일 크기 slab 공간에서만 발생한다.
- 즉, 다른 공간에 expired 데이터가 존재하여 유휴 메모리가 있다하여도, 이를 유휴 메모리로 판단할 수 없다.
- 이미 충분히 page를 차지하고 있다면, 신규 인입에 대해서는 page 1개와 1개의 아이템만 추가된다.
