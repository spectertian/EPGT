# -*- coding: utf-8 -*-
# To change this template, choose Tools | Templates
# and open the template in the editor.

__author__="zhigang"
__date__ ="$2010-5-31 17:51:32$"

#import sys
#import xmlrpclib
from datetime import datetime
from datetime import timedelta
import time
import pytz

from TvProgramBot.request import request

from TvProgramBot.db.connection import getDb

from pymongo import Connection
from pymongo.objectid import ObjectId
# 自动跳过之维基列表
skip_wiki_id = ["4d0087522f2a241bd700c5c5", # 电影《女座头市》 有别名：市
                "4d007e472f2a241bd7000bdc", # 电视剧《接触》
                "4d007e742f2a241bd700106f", # 动画版《隋唐英雄传》
                "4d007e142f2a241bd700063a", # 电视剧《零距离》
                "4d0086cf2f2a241bd700c0d9", # 电视剧《家》
                "4d0085e82f2a241bd700b770", # 汉城生死情 有别名“朋友”关联错误
                "4d00860c2f2a241bd700b8b7", # 电影《信》
                "4d007de32f2a241bd700018a", # 电视剧《风云》
                "4d007ef22f2a241bd7002483", # 电影《一天》
                "4d007e4c2f2a241bd7000c52", # 电视剧《英雄》
                "4d00860e2f2a241bd700b8c9", # 电影《战争》
                "4d007e4d2f2a241bd7000c7d", # 电视剧《天下》
                "4d00820e2f2a241bd7007a94", # 电影《中国》
                "4d0085612f2a241bd700b21c", # 电视剧《百姓》
                "4d0085c32f2a241bd700b5f5", # 美剧《流言》
                "4d00872d2f2a241bd700c46c", # 电视剧《明天》
                "4d0087cc2f2a241bd700ca0f", # 电视剧《阿秀》
                "4d00872a2f2a241bd700c447", # 电视剧《子夜》
                "4d007e132f2a241bd700061b", # 电视剧《秘密》
                "4d007dec2f2a241bd7000254", # 电视剧《完美》
                "4d007e552f2a241bd7000d39", # 电视剧《真相》
                "4d0087f42f2a241bd700cb64", # 电视剧《幸福》
                "4d0087742f2a241bd700c6fa", # 电视剧《天地人》
                "4d007e0e2f2a241bd7000590", # 电视剧《再见》
                "4d0085662f2a241bd700b24e", # 电视剧《乒乓》
                "4d007e5f2f2a241bd7000e45", # 电视剧《风云争霸》
                "4d007e662f2a241bd7000ef7", # 电视剧《办公室》
                "4d007e662f2a241bd7000f0a", # 电视剧《航海王》
                "4d00855e2f2a241bd700b1fe", # 电视剧《故事》
                "4d007e0c2f2a241bd700053d", # 美剧《魅力第一部》
                "4d0086692f2a241bd700bc99", # 电视剧《一千零一夜》
                "4d0086ea2f2a241bd700c1ef", # 电视剧《幸福》
                "4d0086892f2a241bd700bde2", # 电视剧《大舞台》
                "4d00855d2f2a241bd700b1f6", # 日剧《美人》
                "4d0086432f2a241bd700bb10", # 电视剧《步步为赢》
                "4d00864e2f2a241bd700bb81", # 电视剧《拯救》
                "4d007e1f2f2a241bd700078c", # 电视剧《挑战》
                "4d0086eb2f2a241bd700c1f8", # 电视剧《黄金线》
                "4d0086cb2f2a241bd700c09e", # 电视剧《茶馆》
                "4d0087aa2f2a241bd700c8c7", # 电视剧《英雄》
                "4d0086172f2a241bd700b92e", # 电视剧《爱情男女》 有别名《夺标》
                "4d0088412f2a241bd700ce43", # 电视剧《生活》
                "4d0086d72f2a241bd700c124", # 电视剧《回家》
                "4d0086e52f2a241bd700c1b9", # 韩剧《我爱你》
                "4d0085a52f2a241bd700b4d7", # 电视剧《在一起》
                "4d007dd52f2a241bd7000049", # 美剧《反恐24小时(第一季)》
                "4d0086192f2a241bd700b943", # 日剧《life 人生》
                "4d007de82f2a241bd70001fa", # 电视剧《蒲公英》
                "4d0085cd2f2a241bd700b663", # 电视剧《光荣》
                "4d0087ab2f2a241bd700c8d4", # 电视剧《梦想》
                "4d0087da2f2a241bd700ca82", # 韩剧《名家》
                "4d007e312f2a241bd70009b3", # 电视剧《中国故事》 别名《往事》
                "4d0086d42f2a241bd700c104", # 电视剧《旗舰》
                "4d0085972f2a241bd700b448", # 韩剧《谢谢》
                "4d007dec2f2a241bd7000251", # 电视剧《情侣》
                "4d0085a52f2a241bd700b4d3", # 韩剧《真实》
                "4d007e952f2a241bd70013c5", # 电视剧《都是爱情惹的祸》别名《成都》
                "4d007dd52f2a241bd700005b", # 电视剧《无知县令》别名《拍案惊奇》
                "4d007e072f2a241bd70004cc", # 韩剧《宫》
                "4d007e592f2a241bd7000dae", # 美剧《爆Show特警组》 别名：现场直播
                ]

class SiteBase(object):

    def __init__(self, channel_code, config):
        self.channel_code = channel_code
        self.config = config
        self.db = getDb()
        self.init_mongo()

    def init_mongo(self):
        self.mongo_conn = Connection("mongodb://192.168.10.71:27017")
        self.mongo_db = self.mongo_conn.epg
        self.mongo_program = self.mongo_db.program
        self.mongo_wiki = self.mongo_db.wiki
        self.mongo_television = self.mongo_db.television
        self.mongo_editmemory = self.mongo_db.editor_memory
    
    def get_content(self, url):
        try:
            content = request(url)
        except Exception, e:
            print "Request Error! URL: %s, Message: %s" % (url, e)
            return ''
        return content

    def save(self, programs):
        tz = pytz.timezone("Asia/Taipei")
        if programs:
	    #删除当天原有数据
	    self.mongo_program.remove({"channel_code": self.channel_code, "date": programs[0]['date']})
            insert_programs = []
            wiki_ok=0
            zong=0
            for program in programs:
                p = {}
                p['channel_code'] = self.channel_code
                p['name'] = program['title']
                p['publish'] = True
                p['time'] = program['stime']
                p['date'] = program['date']
                start_time = time.strptime("%s %s" % (p['date'], p['time']), "%Y-%m-%d %H:%M")
                p['start_time'] = datetime(*start_time[:6]).replace(tzinfo=tz)
                p['created_at'] = datetime.now().replace(tzinfo=tz)
                if program.has_key('tags'):
                    p['tags'] = program['tags']
                #print 'start search %s from edit_memory' % (p['name'])
		#editor:lifucang更改执行顺序
                print '*************start************'    
                if program['wiki'].has_key("tvsou_id"):
		    p['tvsou_id'] = program['wiki']['tvsou_id']
                    wiki = self.get_wiki_from_tvsou(program['wiki']['tvsou_id'])
                    print '1 get_wiki_from_tvsou'
                    if wiki:
                        wiki_title = wiki['title']
                        if len(wiki_title) > 1:
                            p['wiki_id'] = str(wiki['_id'])	
                            p['tags'] = wiki['tags']							
		if not p.has_key("wiki_id"):
                    wiki = self.get_wiki_from_edit_memory(p['name'],p['channel_code'])
                    print '2 edit_memory'
                    if not wiki:
                        wiki = self.get_wiki_from_time(p['name'], p['date'], p['time'])
                        print '3 get_wiki_from_time'
                    if wiki:
                        p['wiki_id'] = str(wiki['_id'])
                        p['tags'] = wiki['tags']
                # 跳过严重错误之维基
                if p.has_key("wiki_id") and p['wiki_id'] in skip_wiki_id:
                    del p['wiki_id']
                    del p['tags']
                else:
                    insert_programs.append(p)
                if wiki:
                    print '##########ok wiki'
                    wiki_ok=wiki_ok+1
                else:
                    print 'not wiki'
                zong=zong+1    
                print '*************end***********'
            print zong
            print wiki_ok
            if insert_programs:
                self.mongo_program.insert(insert_programs)
                #self.update_program_endtime(programs[0]['date'])
    def get_wiki_from_tvsou(self, tvsou_id):
        """获取 tvsou Wiki，操作简单但准确率低"""
        wiki = self.mongo_wiki.find_one({"tvsou_id": tvsou_id})
        return wiki

    def get_wiki_from_edit_memory(self, program_name, channel_code):
        """从编辑的编辑记录库中查询wiki"""
        editmemory = self.mongo_editmemory.find_one({"program_name": program_name,"channel_code": channel_code})
        if editmemory:
            wiki = self.mongo_wiki.find_one({"_id": ObjectId(editmemory['wiki_id'])})
            return wiki
        else:
            return False
        
    def get_wiki_from_time(self, name, date, play_time):
        """由时间、日期获取Wiki，主要用于固定时间播放之节目"""
        # 兼容 Python 2.4 strptime 方法
        date_to_time = time.strptime("%s %s" % (date, play_time), "%Y-%m-%d %H:%M")
        d = datetime(*date_to_time[:6])#.strptime(date, "%Y-%m-%d")
        week_day = d.weekday()
        # Python中星期一为0
        week_day = week_day + 1
        # 时间范围冗余上下各10分钟
        #play_time = time.strptime(play_time, "%H:%M")
        s_time = (d - timedelta(minutes=10)).strftime("%H:%M")
        e_time = (d + timedelta(minutes=10)).strftime("%H:%M")
        television = self.mongo_television.find({"channel_code": self.channel_code,
                                                 "week_day": str(week_day),
                                                 "play_time": {"$gte": s_time, "$lte": e_time}})
        for t in television:
            wiki_title = t['wiki_title']
            try:
                if not t['wiki_id']:
                    continue
                name.index(wiki_title)
                wiki = self.mongo_wiki.find_one({"_id": ObjectId(t['wiki_id'])})
                return wiki
            except ValueError, e:
                pass

        return False

    def update_program_endtime(self, riqi):
        program_list = self.mongo_program.find({"channel_code": self.channel_code, "date": riqi}).sort("time")
        if program_list:
            prev_program = ''
            for program in program_list:
                if not prev_program:
                    prev_program=program
                    continue
                # prev_program.update({'_id':prev_program['_id']},{'$set':{'end_time':program['start_time']}})
                prev_program['end_time']=program['start_time']
                self.mongo_program.save(prev_program)
                prev_program=program
            nextday = datetime.strptime(riqi,'%Y-%m-%d') + timedelta(days=1)
            next_day = datetime.strftime(nextday,'%Y-%m-%d')
            #print nextday
            #print next_day
            next_programs = self.mongo_program.find({"channel_code": self.channel_code, "date": next_day}).limit(1).sort("time")
            #print next_program
            #if next_program[0]['start_time']:
            for next_program in next_programs:
                #prev_program.update({'_id':prev_program['_id']},{'$set':{'end_time':next_program['start_time']}})
                prev_program['end_time']=next_program['start_time']
                self.mongo_program.save(prev_program)
