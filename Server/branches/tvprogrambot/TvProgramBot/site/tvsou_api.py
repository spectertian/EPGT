# -*- coding: utf-8 -*-
# To change this template, choose Tools | Templates
# and open the template in the editor.

__author__="zhigang"
__date__ ="$2010-8-23 18:11:24$"

#from cStringIO import StringIO
#import calendar

from datetime import datetime
from datetime import timedelta
import time
import BeautifulSoup
from TvProgramBot.tool import unescape
from TvProgramBot.site.base import SiteBase
from TvProgramBot.db.skipWikis import getTagTables

tvsou_properties = getTagTables()

class SiteTvsouApi(SiteBase):

    def run(self):
        channel_id = self.config['tvsou']['channel_id']

        today = datetime.now()
        empty = 0
        for i in range(0, 7):
            if empty >= 3:
                break
            
            day = today + timedelta(days=i)
            programs = self.get_day(channel_id, day)

            if programs:
                self.save(programs)
            else:
                empty = empty + 1
        for i in range(0, 7):
            day = today + timedelta(days=i)
            riqi=day.strftime("%Y-%m-%d")
            self.update_program_endtime(riqi) 

    def get_day(self, channel_id, day):
        """抓取单天记录"""
        url = "http://hz.tvsou.com/jm/hw/hw8901.asp?id=%s&Date=%s" % (channel_id, day.strftime("%Y-%m-%d"))

        content = self.get_content(url)
        content = content.decode('gb18030').encode('utf-8')
        
        self.xml_content = BeautifulSoup.BeautifulStoneSoup(content, fromEncoding="utf-8")

        programs = []
        items = self.xml_content.findAll('c')
        for item in items:
            s_time = item.find('pt')
            program_title = item.find('pn')
            tvsou_tags = item.find('pp')
            fid2 = item.find('fid2').string
            fid = item.find('fid').string
            if fid2 and fid2 != '0':
                tvsou_wiki_id = fid2
            elif fid and fid != '0':
                tvsou_wiki_id = fid
            else:
                tvsou_wiki_id = '0';
               
            if program_title:
                s_time = s_time.string
                s_time = time.strptime(s_time, "%Y-%m-%d %H:%M:%S")
                program_title = program_title.string
                program_title = unescape(program_title)
                program_title = program_title.replace("(本节目表由搜视网提供)", "")
                program_title = program_title.strip() 

                # 转换 TVSOU 属性为汉字
                tvsou_tags = tvsou_tags.string
                tags = []
                if tvsou_tags:
                    tvsou_tags = tvsou_tags.split(',['']],[')
                    
                    for tvsou_tag in tvsou_tags:
                        tvsou_tag = tvsou_tag.strip()
                        if tvsou_properties.has_key(tvsou_tag):
                            tags.append(tvsou_properties[tvsou_tag])

                wiki = {}
                if tvsou_wiki_id and tvsou_wiki_id != '0':
                    wiki['tvsou_id'] = str(tvsou_wiki_id)

                program = {"stime": time.strftime("%H:%M", s_time),
                           "title": program_title.strip(),
                           "date": time.strftime("%Y-%m-%d", s_time),
                           "tags": tags,
                           "wiki": wiki
                           }
                programs.append(program)
        return programs
