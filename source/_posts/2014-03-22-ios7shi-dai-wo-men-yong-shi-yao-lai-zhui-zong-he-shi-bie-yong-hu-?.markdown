---
layout: post
title: "iOS7时代我们用什么来追踪和识别用户？"
date: 2014-03-22 17:07:11 +0800
comments: true
archives:
---
要识别用户，首先就是要选择一个标识符，通过这个标识符来识别这个用户的设备（而不是用户），这个标识符要能够保证一个设备上返回的值是一样的，并且在其他设备上不会出现相同的值。

在iOS7之前，曾经有过很多方法来识别用户的设备，从最原始的设备udid、mac地址，到被各广告统计平台广泛使用的开源方案OpenUDID。

但随着AppStore开始拒绝接受使用udid的应用，到mac地址在iOS7上所有设备上都返回相同的值，再到iOS7上对剪贴板的限制，导致OpenUDID无法被不同应用共享相同的值，注定了上面提到的这些id们不得不退出历史的舞台。

随着iOS7快速占据了半壁江山，设备id的控制权终于彻底回到了Apple手中，同时也让用户能够把控自己的隐私，充分表明了是苹果对用户隐私保护的决心。

好了，其实就一个问题：iOS7时代我们用什么来追踪和识别用户？

 

###先给结论
idfa: 适用于对外：例如广告推广，换量等跨应用的用户追踪等
idfv: 适用于对内：例如分析用户在应用内的行为等
PS：尘埃落定，跟着Apple走，大家不用再犹豫了。

 

###再给解释
####idfa
全名：advertisingIdentifier
代码：

`#import  
NSString *adId = [[[ASIdentifierManager sharedManager] advertisingIdentifier] UUIDString];`
  

来源：iOS6.0及以后

说明：直译就是广告id， 在同一个设备上的所有App都会取到相同的值，是苹果专门给各广告提供商用来追踪用户而设的，用户可以在 设置|隐私|广告追踪 里重置此id的值，或限制此id的使用，故此id有可能会取不到值，但好在Apple默认是允许追踪的，而且一般用户都不知道有这么个设置，所以基本上用来监测推广效果，是戳戳有余了。
注意：由于idfa会出现取不到的情况，故绝不可以作为业务分析的主id，来识别用户。
idfv
全名：identifierForVendor
代码：


  NSString *idfv = [[[UIDevice currentDevice] identifierForVendor] UUIDString];

来源：iOS6.0及以后

说明：顾名思义，是给Vendor标识用户用的，每个设备在所属同一个Vender的应用里，都有相同的值。其中的Vender是指应用提供商，但准确点说，是通过BundleID的DNS反转的前两部分进行匹配，如果相同就是同一个Vender，例如对于com.somecompany.appone,com.somecompany.apptwo 这两个BundleID来说，就属于同一个Vender，共享同一个idfv的值。和idfa不同的是，idfv的值是一定能取到的，所以非常适合于作为内部用户行为分析的主id，来标识用户，替代OpenUDID。
注意：如果用户将属于此Vender的所有App卸载，则idfv的值会被重置，即再重装此Vender的App，idfv的值和之前不同。
 
历史上的英雄们
UDID
设备唯一标识符（Unique Device Identifier）之前被各种国内外统计平台，应用开发商广泛使用，后Apple从2013年05月01日起拒绝接受使用UDID的应用后，立毙！

Mac地址
每一个网卡都有一个唯一的标识，即Mac地址，显然用来标识一个手机是绰绰有余的了，也有一些开源的方案也用到了它，国内UMTrack等也用它作为过主id，随着iOS7返回同样的值后，不得不退隐江湖。

OpenUDID
在Apple拒绝UDID后，OpenUDID作为独立于Apple的开源方案，被广大的开发者所接受，各大统计广告平台都从UDID等方案切换到OpenUDID的方案（看来大家都不想完全被Apple束缚啊），但不幸的事，同样由于iOS7对剪贴板的限制，导致同一个设备上应用间，无法再共享一个OpenUDID，即OpenUDID作为设备唯一标识的能力被大大削弱。也可以看到随着iOS7的来临，各广告平台都迅速更新自己的SDK，来切换到苹果的idfa的方案上来。

其他
CFUUID、NSUUID等自己生成，自己存储管理的就不细说啦。

DeviceToken
这是推送用的令牌，用户如果没开推送，或者拒绝了，这个就没有了！了！