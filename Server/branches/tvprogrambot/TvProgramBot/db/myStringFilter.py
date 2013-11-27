#!/usr/bin/env python26
# -*- coding: utf-8 -*-

__author__="superwen"
__date__ ="$2013-08-07 17:19:10$"

import re

def getFilterTitle(s):
    
    #print re.findall(r'\d{2,}',s)    
    s = s.strip()
    
    #一些忽略掉
    ignores = ['笑动*2013','快乐*365']
    count = 0 
    while count<len(ignores):
        if re.search(ignores[count], s):
            return s,''
        else :
            count = count + 1 

    #先将中文符号转化为英文符号
    signfs = [
        ['：', ':'],
        ['（', '('],
        ['）', ')'],
        ['Ⅱ','2']
    ]
    count = 0
    while count<len(signfs):
        s,d = re.subn(signfs[count][0], signfs[count][1], s)
        count = count + 1
    #print s,'\n'
    
    #直接过滤
    matchs = [
        ['^非常静距离:','非常静距离'],
        ['^经济信息联播:','经济信息联播'],
        ['^寰宇万象:','寰宇万象'],
        ['^快乐大本营:','快乐大本营'],
        ['^非常了得:','非常了得'],
        ['^寰宇视野:','寰宇视野'],
        ['^生活早参考:','生活早参考'],
        ['^国学堂:','国学堂'],
        ['^金牌调解:','金牌调解'],
        ['^传奇故事:','传奇故事'],
        ['^档案午间版:','档案午间版'],
        ['^天天高尔夫:','天天高尔夫'],
        ['^百家讲坛:','百家讲坛'],
        ['^收藏大讲堂:','收藏大讲堂'],
        ['^挑战名人墙:','挑战名人墙'],
        ['^艺眼看世界:','艺眼看世界'],
        ['^收藏马未都:','收藏马未都'],
        ['^军情第一线:','军情第一线'],
        ['^人文地理:','人文地理'],
        ['^探索·发现:','探索·发现'],
        ['^走近科学:','走近科学'],
        ['^快乐汉语:','快乐汉语'],
        ['^民族底片:','民族底片'],
        ['^文化大百科:','文化大百科'],
        ['^名段欣赏:','名段欣赏'],
        ['^绝版赏析:','绝版赏析'],
        ['^传奇人物:','传奇人物'],
        ['^终极探索:','终极探索'],
        ['^农业新天地:','农业新天地'],
        ['^调解面对面:','调解面对面'],
        ['^CCTV空中剧院:','CCTV空中剧院'],
        ['^致富经:','致富经'],
        ['^万象:','万象'],
        ['^每日农经:','每日农经'],
        ['^国宝档案:','国宝档案'],
        ['^书画人生:','书画人生'],
        ['^跟富老师学:',"跟富老师学"],
        ['^超级魔术师:','超级魔术师'],
        ['^环宇搜奇:','环宇搜奇'],
        ['^心在旅途:','心在旅途'],
        ['^中国旅游:','中国旅游'],
        ['^画中话:','画中话'],
        ['^旅游新天地:','旅游新天地'],
        ['^魅力世界:','魅力世界'],
        ['^想钓鱼跟我走:','想钓鱼跟我走'],
        ['^快意TALK吧:','快意TALK吧:'],
        ['^八卦车谈:','八卦车谈'],
        ['^藏友三家谭:','藏友三家谭'],
        ['^娱乐梦工厂:','娱乐梦工厂'],
        ['^钓赛进行时:','钓赛进行时'],
        ['^钓赛进行时:','钓赛进行时'],
        ['^户外生活:','户外生活'],
        ['^老梁观世界:','老梁观世界'],
        ['^爱情传送带:','爱情传送带'],
        ['^老梁故事汇:','老梁故事汇'],
        ['^芝麻开门:','芝麻开门'],
        ['^旅行者:','旅行者'],
        ['^纪录片编辑室:','纪录片编辑室'],
        ['^喜剧一箩筐:','喜剧一箩筐'],
        ['^电视书苑:','电视书苑'],
        ['^凡人大爱:','凡人大爱'],
        ['^故事里的事:','故事里的事'],
        ['^九州大戏台:','九州大戏台'],
        ['^明星车模:','明星车模'],
        ['^立辨真伪:','立辨真伪'],        
        ['^皇室追踪:','皇室追踪'],
        ['^梨园春秋:','梨园春秋'],        
        ['^综艺喜乐汇:','综艺喜乐汇'],
        ['^中国京剧音配像精粹:','中国京剧音配像精粹'],        
        ['^超级访问:','超级访问'],
        ['^非你莫属:','非你莫属'],        
        ['^金牌调解:','金牌调解'],
        ['^世界大力士中国争霸赛:','世界大力士中国争霸赛'],        
        ['^生活·帮:','生活·帮'],
        ['^财经郎眼:','财经郎眼'],       
        ['^我要上春晚:','我要上春晚'],      
        ['^中国梦之声:','中国梦之声'],      
        ['^杨澜访谈录:','杨澜访谈录'],      
        ['^非诚勿扰::','非诚勿扰'],      
        ['^曾经的财富传奇:','曾经的财富传奇'],      
        ['^首席夜话:','首席夜话'],      
        ['^非常静距离:','非常静距离'], 
        ['^星光大道:','星光大道'],      
        ['^谁是我家人:','谁是我家人'],      
        ['^波士堂:','波士堂'],      
        ['^购时尚:','购时尚'],      
        ['^大家说健康:','大家说健康'],      
        ['^高尔夫赛事集锦:','高尔夫赛事集锦'],      
        ['^谢天谢地，你来啦:','谢天谢地，你来啦'],      
        ['^男左女右:','男左女右'],      
        ['^快乐男声\S*:','快乐男声'],      
        ['^超级演说家:','超级演说家'],      
        ['^看电影学英语:','看电影学英语'],      
        ['^足球华彩:','足球华彩']
    ]           
    count = 0
    while count<len(matchs):
        if re.search(matchs[count][0], s):
            return matchs[count][1],''
        else :
            count = count + 1     

    #替换没有意义的字符
    regexs = [r'第\(\d+\)集', r'\(\d+\)集', r'\d+\―\d+', r'\(\d+\)', r'\d{2,3}$', r' \d+',
              r'\S+剧场(:|-)', '电视剧:', '电视剧', '第一动画乐园:', '\S+大剧院:',
              '前情提要', '《' ,'》', '<', '>', '动画片:',r'\S+剧苑:',
              '特别节目', r'\d+/\d+','转播','重播:', '重播', '中央台', r'\S+影院:', r'\(重播版\)',
              r'\(重播\)', r'大结局','精装版','精编版','精华版',
              r'\S+连续剧:','转播中央','电影没有界:',
              r'\(\d+月\d+日\)','香港电影:','儿童剧\-\-',
              '宣传片-']
    for regex in regexs :  
        s,d = re.subn(regex, '', s)
        
    return s.strip(),''