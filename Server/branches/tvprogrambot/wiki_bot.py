#!/usr/bin/env python
# -*- coding: utf-8 -*-
# To change this template, choose Tools | Templates
# and open the template in the editor.

__author__="sxin"
__date__ ="$2010-9-13 17:14:11$"

#import os
import sys
reload(sys)
sys.setdefaultencoding('utf8')
import os
import time
import signal
import string
import urllib2
import re
import Image
import simplejson as json
import BeautifulSoup

from TvProgramBot.site.base import SiteBase
from TvProgramBot.db.connection import getDb

from cStringIO import StringIO
from multiprocessing import freeze_support
from multiprocessing import Process, Queue, active_children

def _urllibRequest(url):
    """urllib下载函数"""
    req = urllib2.Request(url)
    req.add_header('User-Agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.0.7) Gecko/2009021910 Firefox/3.0.7')
    return urllib2.build_opener().open(req).read()

class SiteTvsouWiki(SiteBase):

    img_str_cache = {}

    def __init__(self, tvsou_id, name):
        self.tvsou_id = tvsou_id
        self.dir_id = self.getDirId()
        self.name = ''
        self.oname = ''

        name = name.decode('gb18030', 'ignore').encode('utf-8')
        names = name.split(',', 1)
        self.name = names[0].strip()
        if len(names) > 1:
            self.oname = names[1]

        self.db = getDb()
        self.wiki_id = '';

    def getDirId(self):
        dir_id = str(self.tvsou_id)
        dir_id_count = dir_id.count('')
        if dir_id_count == 4:
            dir_id = dir_id[:1]
        elif dir_id_count == 5:
            dir_id = dir_id[:2]
        elif dir_id_count == 6:
            dir_id = dir_id[:3]
        elif dir_id_count == 7:
            dir_id = dir_id[:4]
        elif dir_id_count == 8:
            dir_id = dir_id[:5]
        return dir_id

    def run(self):
#        wiki_url = "http://www.tvsou.com/intro.asp?id=%s" % id
#        self.tvsou_id = 301
#        self.dir_id = 3
        wiki_url = "http://jq.tvsou.com/introhtml/%s/index_%s.htm" % (self.dir_id, self.tvsou_id)
        self.wiki_url_t = wiki_url
#        print wiki_url
#        print self.name
        content = _urllibRequest(wiki_url)
        content = content.decode('gb18030', 'ignore').encode('utf-8')
        html_content = BeautifulSoup.BeautifulSoup(content, fromEncoding='utf-8')
        img_div = html_content.find('div', attrs={'id': 'M_title_s'})
        if img_div:
            img = img_div.find('img')
            src = img['src']
            #电视剧
            if src.endswith('icon_drama.gif'):
                table = html_content.find('div', attrs={'id': 'ratingdiv1'}).findNext('table')
#                print dao.string
#                yan = table.find(text=re.compile(u'主演：')).findNext('td')
#                yan_contents = yan.findAll('a')
#                for yan_content in yan_contents:
#                    yan_content.replaceWith('')
#                yanyuan = []
#                for y in yan.contents:
#                    yanyuan.append(y)                    
#                print yanyuan
# 
#                leixing = table.find(text=re.compile(u'类型：')).findNext('td')
#                print leixing.string

                juqing = self.getJuqing()
 
                self.save_wiki('tv', juqing)
                self.save_wiki_ext_relation()
                self.save_wiki_tag(33)
                self.getFengji()
                self.getFengjiCount()
     
            #电影
            elif src.endswith('icon_mov.gif'):
                juqing = self.getJuqing()
                self.save_wiki('movie', juqing)
                self.save_wiki_ext_relation()
                self.save_wiki_tag(27)

            ##基本信息部分
            table = html_content.find('div', attrs={'id': 'ratingdiv1'}).findNext('table')
            diqu = table.find(text=re.compile(u'地区：')).findNext('td')
            self.save_wiki_ext('地区', 'area', diqu.string)
           
            #导演
            dao = table.find(text=re.compile(u'导演：')).findNext('td')
            dao_a = dao.findAll('a')
            if dao_a:
                for a in dao_a:
                    a.replaceWith(a.string)
              
            if dao.contents:
                daoyan  = ''.join(dao.contents)
                daoyan = daoyan.split('/')
                daoyan = ','.join(daoyan)
                daoyan = daoyan.replace(' ', '')
                self.save_wiki_ext('导演', 'director', daoyan)       

            #主演部分
            yan = table.find(text=re.compile(u'主演：')).findNext('td')
            yan_a = yan.findAll('a')
            if yan_a:
                for a in yan_a:
                    a.replaceWith(a.string)
             
            if yan.contents:
                yanyuan = ''.join([str(yyy) for yyy in yan.contents])
                yanyuan = yanyuan.split('/')
                yanyuan = ','.join(yanyuan)
                yanyuan = yanyuan.replace(' ', '')

                self.save_wiki_ext('主演', 'starring', yanyuan)       

            #别名
            if self.oname:
               other_names = self.oname.split(',') 
               for other_name in other_names:
                   self.save_wiki_ext('别名', 'alias', other_name.strip())

    def save_wiki_tag(self, tag_id):
        self.db.query("INSERT INTO `wiki_tag`(`tag_id`, `wiki_id`, `created_at`, `updated_at`) \
            VALUES (%s, %s, now(),  now());",
            (tag_id, self.wiki_id))

    def save_wiki_ext_relation(self):
        self.db.query("INSERT INTO `wiki_ext`(`title`, `wiki_id`, `wiki_key`, `wiki_value`, `created_at`, `updated_at`) \
            VALUES (%s, %s, %s, %s,  now(),  now());",
            ('tvsou id', self.wiki_id, 'tvsou_id', self.tvsou_id))

    def save_wiki(self, type, content):
        try:
            db = self.db.query("INSERT INTO `wiki`(`title`, `style`, `content`, `created_at`, `updated_at`) \
                VALUES (%s, %s, %s, now(), now());",
                (self.name, type, content))
            self.wiki_id = db.lastrowid
        except Exception, e:
            print "SQL Error! URL: %s" % (self.tvsou_id)
   
    def save_wiki_ext(self, title, wiki_key, wiki_value, sort = 0):
        self.db.query("INSERT INTO `wiki_ext`(`title`, `wiki_id`, `wiki_key`, `wiki_value`, `sort`, `created_at`, `updated_at`) \
            VALUES (%s, %s, %s, %s, %s, now(), now());",
            (title, self.wiki_id, wiki_key, wiki_value, sort))

                    
    def replace_img(self, element):
        """替换内容中的图片为文字"""
        from TvProgramBot.pytesser import image_to_string

        images = element.findAll('img')
        if images:
            for image in images:
                image_src = image['src']
                if not image_src.startswith('http://'):
                    image_src = 'http://jq.tvsou.com' + image_src
                    #image_src = 'http://souepg.com' + image_src
                if self.img_str_cache.has_key(image_src):
                    img_str = self.img_str_cache[image_src]
                else:
                    image_data = StringIO(self.get_content(str(image_src)))
#                    try:
                    image_p = Image.open(image_data)
#                    except IOError, e:
#                        print 'error:', e
#                        print image_src
#                        print image_data
                    image_width, image_height = image_p.size

                    if image_width <= 10 and image_height <= 10:
                        image_width = 20
                        image_height = 20
                    image_resize = image_p.resize((image_width*2, image_height*2))

                    if image_src.startswith('http://epg.tvsou.com/imagesnum/'):
                        lang = 'eng'
                    else:
                        lang = 'song'
                    try:
                        img_str = image_to_string(image_resize, lang=lang).strip()
                    except Exception, e:
                        print "Tesser Error! URL: %s, image_url: %s, Info: %s" % (self.wiki_url_t, image_src, e)
                        sys.exit(1)
                    # 替换识别错误的中英文
                    if img_str == "体青":
                        img_str = "体育"
                    elif img_str == "中固":
                        img_str = "中国"
                    elif img_str == "直苫番":
                        img_str = "直播"
                    elif img_str == "置苫番":
                        img_str = "重播"
                    elif img_str == "臭运":
                        img_str = "奥运"
                    elif img_str == "诀嚣":
                        img_str = "决赛"
                    elif img_str == "联苫番":
                        img_str = "联播"
                    elif img_str == "早向":
                        img_str = "早间"
                    elif img_str == "晚向":
                        img_str = "晚间"
                    elif img_str == "时向":
                        img_str = "时间"
                    elif img_str == "夭气":
                        img_str = "天气"
                    elif img_str == "夭夭":
                        img_str = "天天"
                    elif img_str == "牛向":
                        img_str = "午间"
                    elif img_str == "王重续剧":
                        img_str = "连续剧"
                    elif img_str == '灾巨':
                        img_str = '是'
                    elif img_str == '亲世':
                        img_str = '她'
                    elif img_str == '在.':
                        img_str = '在'
                    elif img_str == '日芒]':
                        img_str = '的'
                    elif img_str == '王':
                        img_str = '是'
                    elif img_str == 'j':
                        img_str = '，'
                    elif img_str == 's':
                        img_str = '，'
                    elif img_str == ':4':
                        img_str = '，'
                    elif img_str == '':
                        img_str = '。'

                    img_str = img_str.decode('utf-8')
                    self.img_str_cache[image_src] = img_str
                image.replaceWith(img_str)

    def getJuqing(self):
        juqing_url = "http://jq.tvsou.com/introhtml/%s/1_%s.htm" % (self.dir_id, self.tvsou_id)
        content = _urllibRequest(juqing_url)
        try:
            content = content.decode('gb18030', 'ignore').encode('utf-8')
        except Exception, e:
            print juqing_url
            print e
#        content = content.decode('gb18030').encode('utf-8')

        html_content = BeautifulSoup.BeautifulSoup(content, fromEncoding='utf-8')
        juqing = html_content.find('td', attrs={'class': 'con_body'})

        #过滤剧情中的剧照
        juqing_img = juqing.findAll('img')
        for ju in juqing_img:
           if ju['src'].find('forenotice_images') >= 0:
               ju.replaceWith('')

        #替换文字图片
        self.replace_img(juqing)
        juqing = unicode(juqing)
        juqing = juqing.replace('<td class="con_body">', '')
        juqing = juqing.replace('</td>', '')
        return juqing.strip()

    def getFengji(self):
        page = 1
        while True:
            fengji_url = "http://jq.tvsou.com/introhtml/%s/11_%s_%s.htm" % (self.dir_id, self.tvsou_id, page)
            result = self.doFengji(fengji_url, page)
            if result == 'ok':
               return True
            page = page + 1
            

    def doFengji(self, fengji_url, page):
        i = ((page - 1) * 5) + 1
        try:
            content = _urllibRequest(fengji_url)
            content = content.decode('gb18030', 'ignore').encode('utf-8')

            content = BeautifulSoup.BeautifulSoup(content, fromEncoding='utf-8')
            juqing = content.findAll('td', attrs={'class': 'con_body'})
            for ju in juqing:
                self.replace_img(ju)

                ju = unicode(ju)
                ju = ju.replace('<td colspan="2" valign="top" style="padding: 2px;" class="con_body">', '')
                ju = ju.replace('</td>', '')

                content = {}
                content['title'] = "%s第%s集" % (self.name, i)
                content['msg'] = ju.strip()
                content = json.dumps(content)
                self.save_wiki_ext('剧情', 'drama', content, i)
                i = i + 1
        except urllib2.HTTPError, e:
            if e.code == 404:
                return 'ok'
                #raise
            else:
                time.sleep(3)
                self.doFengji(fengji_url, page)
                return None

    def getFengjiCount(self):
        fengjis = self.db.query('SELECT * FROM wiki_ext WHERE \
                  `wiki_id` = %s AND `wiki_key` = %s',
                  (self.wiki_id, 'drama')).fetchall()
        count = len(fengjis)
        #print count
        self.save_wiki_ext('集数', 'episodes', count, 0)
        
                
    
def run_task(qu):
    while 1:
        q = qu.get()
        id = q['id']
        name = q['name']
        
        s = SiteTvsouWiki(id, name)
        s.run()

def main():
    f = file('Forenotice.CSV', 'r')
    run_queue = Queue(50)
   
    for i in range(20):
        p = Process(target=run_task, args=(run_queue,))
        p.daemon = True
        p.start()
   
    i = 104
    while 1:
        while run_queue.full():
            time.sleep(3)
        
        line = f.readline()
        if not line:
            break
        id, name = line.split(',', 1)
        run_queue.put({'id': id, 'name': name}) 
        print "Queue: %s / %s" % (id, '73380')

    while 1:
        time.sleep(3)
        

if __name__ == "__main__":
    freeze_support()
    main()   

#    f = file('Forenotice.CSV', 'r')
#    while True:
#        line = f.readline()
#        if not line:
#            break
#        id, name = line.split(',', 1)
#        wikiBot = SiteTvsouWiki(id, name)
#        wikiBot.run()

