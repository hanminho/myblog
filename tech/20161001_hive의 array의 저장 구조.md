#hive array의 데이터 저장 구조

##### 2016/10/01

하이브에서 지원하는 array형이 있다. 하이브와 하둡 스트리밍 MR 코드를 쓰까(?)쓰려면 하이브가 배열형을 실제로 어떻게 때려넣나를 살펴봐야 한다.

하이브에 배열형을 SQL로 넣으려면 좀 더럽다.  


https://community.hortonworks.com/questions/22262/insert-values-in-array-data-type-hive.html

일단 넣어둔 내용을 까서 확인해보면 익숙한 ㄱ모양의 아스키 특수 문자가 나온다. 이거 그냥 아스키 코드 001이다. 파이썬이나 awk등에서 표현하면 \001 되겠다.

001로 까먹으면 된다.
