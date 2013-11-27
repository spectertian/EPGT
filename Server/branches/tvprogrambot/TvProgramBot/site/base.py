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
from TvProgramBot.db.connectionMongo import getMongo
from TvProgramBot.db.connectionMemcache import getCache
from TvProgramBot.db.skipWikis import skipWikis
from pymongo.objectid import ObjectId
from TvProgramBot.db.myStringFilter import getFilterTitle

# 自动跳过之维基列表
skip_wiki_id = skipWikis()

class SiteBase(object):

    def __init__(self, channel_code, config, type, province, city):
        self.channel_code = channel_code
        self.config = config
        self.channel_type = []
        if type:
            self.channel_type.append(type)
        if province:
            self.channel_type.append(province)
        if city:
            self.channel_type.append(city)
        self.db = getDb()
        self.init_mongo()

    def init_mongo(self):
        self.mongo_conn = getMongo()
        self.mongo_db = self.mongo_conn.epg
        self.mongo_program = self.mongo_db.program
        self.mongo_wiki = self.mongo_db.wiki
        self.mongo_television = self.mongo_db.television
        self.mongo_editmemory = self.mongo_db.editor_memory
        #mongo_tosoumatchwiki add by gaobo
        self.mongo_tvsoumatchwiki = self.mongo_db.tvsou_match_wiki
    
    def get_content(self, url):
        try:
            content = request(url)
        except Exception, e:
            print "Request Error! URL: %s, Message: %s" % (url, e)
            return ''
        return content
        
    def isset(v):
        try:
            type (eval(v))
        except:
            return 0
        else:
            return 1
            
    def save(self, programs):
        tz = pytz.timezone("Asia/Taipei")
        if programs:
            #删除当天原有数据
            self.mongo_program.remove({"channel_code": self.channel_code, "date": programs[0]['date']})
            insert_programs = []
            wiki_ok = 0
            zong = 0
            for program in programs:
                tvsouid = ''
                p = {}
                p['channel_code'] = self.channel_code
                p['channel_type'] = self.channel_type
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
                if program['wiki'].has_key("tvsou_id"):
                    p['tvsou_id'] = program['wiki']['tvsou_id']
                    #add by gaobo
                    if p['tvsou_id'] != '':
                        #tvsouid['tvsou_id'] = p['tvsou_id']
                        tvsouid = str(p['tvsou_id'])
                    wiki = self.get_wiki_from_tvsou(program['wiki']['tvsou_id'])
                    if wiki:
                        wiki_title = wiki['title']
                        if len(wiki_title) > 1:
                            p['wiki_id'] = str(wiki['_id'])	
                        if wiki.has_key('tags'):
                            p['tags'] = wiki['tags']
                if not p.has_key("wiki_id"):
                    wiki = self.get_wiki_from_edit_memory(p['name'],p['channel_code'])
                    #if not wiki:
                        #wiki = self.get_wiki_from_time(p['name'], p['date'], p['time'])
                    if wiki:
                        p['wiki_id'] = str(wiki['_id'])
                        if wiki.has_key('tags'):
                            p['tags'] = wiki['tags']
                # 跳过严重错误之维基
                if p.has_key("wiki_id") and p['wiki_id'] in skip_wiki_id:
                    del p['wiki_id']
                    del p['tags']
                if p.has_key("wiki_id"):
                    recommend_wikis = self.get_recommend_wikis()
                    if(p['wiki_id'] in recommend_wikis):
                        p['sort'] = 1;
                #else:
                insert_programs.append(p)
                tvsoumatch = None
                if tvsouid is not '':
                    tvsoumatch = self.mongo_tvsoumatchwiki.find_one({"tvsou_id": tvsouid})
                    if tvsoumatch is None:
                        self.mongo_tvsoumatchwiki.insert({"tvsou_id": tvsouid,"created_at": p['created_at']})
            if insert_programs:
                self.mongo_program.insert(insert_programs)
                #self.update_program_endtime(programs[0]['date'])
                
    def get_wiki_from_tvsou(self, tvsou_id):
        """获取 tvsou Wiki，操作简单但准确率低"""
        wiki = self.mongo_wiki.find_one({"tvsou_id": tvsou_id})
        return wiki

    def get_wiki_from_edit_memory(self, program_name, channel_code):
        """从编辑的编辑记录库中查询wiki"""
        mm = ''
        program_name,mm = getFilterTitle(program_name.encode('utf-8'))
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
                
    def get_recommend_wikis(self):
        mc = getCache()
        mkey = "wiki_liverecommend"
        wikis = mc.get(mkey)
        if not wikis :
            wikis = []
            wiki_liverecommend = self.mongo_db.wiki_liverecommend
            wiki_list = wiki_liverecommend.find().limit(100)
            for wiki in wiki_list:
                wikis.append(wiki['wiki_id'])
        if wikis:
            mc.set(mkey,wikis)
            return wikis
        else:
            return []