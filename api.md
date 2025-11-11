梦奈宝塔主机系统开发文档
梦奈宝塔主机系统又称 MNBT ，是一种将宝塔面板 (bt.cn) 转换为虚拟主机并且提供操作面板的系统 ，它由 PHP+MySQL 编写的后端 , 前端使用的为光年开源框架，由于其极快大响应速度和独特的功能深受用户的喜爱。

MNBT 官网： http://mf.mengnai.top/ 此开发文档对应 1.7 版本！ 点此下载Word文档

其请求全为 POST 提交，返回数据为 json ：

{

“ code ” :200,

” msg ” : ” 主机 开通成功！ ”

}

其中 cdoe 为请求状态 (200 为成功 ,100 为失败 ,300 为插件所支持版本为 MNBT 版本不匹配 ) ， msg 为提示信息· ( 失败后返回失败原因 , 成功后返回成功的信息 , 需要更新即返回 MNBT 版本与插件版本的信息和提示 )

每次请求必带参数 是所有功能 ( 除了一键登录和注销登录 ) 都必须要带上的，一键登录和一键注销 不能带上必带参数 ，因为这两个功能是会将参数暴露给用户的！一键登录可以用 POST 提交和 GET 提交这两种，其他功能都必须为 POST 请求。

 

 

每次请求必带参数

参数

示例

备注

mn_bh

fw12201

宝塔编号 , 在宝塔列表中查看

mn_key

suifghsyufgasyud

APi秘钥,在系统设置->APi设置里面查看/修改

mn_keye  
bfasbfuyhafbeaas
宝塔调用秘钥,在宝塔列表中查看
mn_vs
16
插件所支持的MNBT版本(15代表1.5版本)
 

 

测试连接 （ http://搭建MNBT地址/api/api.php?gn=cfif ）
参数

示例

备注

username
L ink
随意填写
 
 
开通主机 （ http://搭建MNBT地址/api/api.php?gn=kt ）
参数

示例

备注

username
U s1000
登录账号(也是FTP账号)
password
123456abc
登录密码(也是FTP密码)
webdx
100
网页空间最大值(填写数字,单位MB)
sqldx
50
数据库空间最大值(填写数字,单位MB
sizemax
60
最多可用多少流量(填写数字,单位G/月)
type
1
产品类型(1为CDN,2为主机)
ymbds
5
域名最多绑定数
dqtime
2022-5-20
到期时间(填0则永久)
 
主机续费 （ http://搭建MNBT地址 /api/api.php?gn=xf ）
参数

示例

备注

username
U s1000
要续费的用户账号
setdate  
2023-5-20
续费后的到期时间
 
删除主机 （ http://搭建MNBT地址 /api/api.php?gn=tz ）
参数

示例

备注

username
U s1000
要删除的主机的账号
 
 
暂停主机 （ http://搭建MNBT地址 /api/api.php?gn=zt ）
参数

示例

备注

username
U s1000
要暂停的主机的账号
 
 
 
解除暂停 （ http://搭建MNBT地址 /api/api.php?gn=jc ）
参数

示例

备注

username
U s1000
要解除暂停的主机的账号
 
重置密码 （ http://搭建MNBT地址 /api/api.php?gn=czmm ）
参数

示例

备注

username
U s1000
要重置密码的主机的账号
password
123456abc
重置后的密码
 
一键登录 （直接跳转） （ http://搭建MNBT地址 /user/idcdl.php?gn=logine ）
此功能 不能 带必带参数

参数

示例

备注

username
U s1000
登录账号
password
123456abc
登录密码
 
注销MNBT的上一次登录并且跳转至登录地址 ： （ http://搭建MNBT地址/user/idcdl.php?gn=xz ），此功能不带任何参数。
