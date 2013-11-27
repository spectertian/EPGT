# -*- coding: utf-8 -*-
# To change this template, choose Tools | Templates
# and open the template in the editor.

__author__ = "pjl"
__date__ = "$2011-1-5 16:12:11$"

import sys
import re
reload(sys)
import string
import urllib2
from datetime import datetime
from datetime import timedelta
import time
import pytz
import random
sys.setdefaultencoding('utf8')
import BeautifulSoup
from pymongo import Connection
from pymongo.objectid import ObjectId
from creole import html2creole
from creole import creole2html
from TvProgramBot.db.connection import getDb
import Image
import base64
import mogilefs
try:
    from cStringIO import StringIO
except ImportError:
    from StringIO import StringIO


def _urllibRequest(url):
    """urllib下载函数"""
    req = urllib2.Request(url)
    req.add_header('User-Agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.0.7) Gecko/2009021910 Firefox/3.0.7')
    return urllib2.build_opener().open(req).read()

class SinaActorSpider():

    def __init__(self):
        self.db = getDb()
        self.init_mongo()

    def init_mongo(self):
        #self.mongo_conn = Connection("mongodb://epg:yExYv4ev6VBJsqSs@192.168.1.51:27017")
        self.mongo_conn = Connection("mongodb://192.168.1.32:27017")
        self.mongo_db = self.mongo_conn.epg
        self.mongo_wiki = self.mongo_db.wiki

    def run(self):
        tz = pytz.timezone("Asia/Taipei")
        #list_urls = self.getListUrls() #拼接明星列表页url

        #actor_ids = self.getActorIds(list_urls) #获取艺人ID号
        #ids = ','.join(actor_ids)
        #f = open('sina_actor_ids.txt', 'w+')
        #f.write(ids)
        #f.close
        #actor_ids = ['7915']
        
        f = open('sina_actor_ids.txt', 'r')
        actor_content = f.read()
        actor_ids = actor_content.split(',')
        f.close 
        for actor_id in actor_ids:
            actor_data = ''
            actor_data = self.getActorBaseInfo(actor_id)
            if actor_data:
                actor_data['model'] = 'actor'
                actor_data['created_at'] = datetime.now().replace(tzinfo=tz)
                actor_data['updated_at'] = datetime.now().replace(tzinfo=tz)
                actor_data['title'] = actor_data['title'].replace('-', '·')
                print actor_data
            else:
                print '跳过组合'
                continue
            
            actor_content = self.getActorContent(actor_id)
            print actor_content
            actor_data['content'] = html2creole(actor_content)
            #过滤掉自动生成的wiki链接
            actor_data['content'] = actor_data['content'].replace('[[', '')
            actor_data['content'] = actor_data['content'].replace(']]', '')
            
            actor_data['html_cache'] = creole2html(actor_data['content'])

            slug = proposal = re.sub("[^\x61-\xff\d\w]", "-", actor_data['title'].decode("utf-8").encode("gb18030", "ignore")).decode("gb18030", "ignore").encode("utf-8").strip("-")
            similarSlugs = []
            regex = re.compile("^%s" % slug)
            for result in self.mongo_wiki.find({"slug": regex}):
                similarSlugs.append(result['slug'])
            i = 1
            while slug in similarSlugs:
                i = i + 1
                slug = proposal + "-" + str(i)

            actor_data['slug'] = slug

            self.mongo_wiki.insert(actor_data) 

    def getActorContent(self, actor_id):
        url = 'http://data.ent.sina.com.cn/star/stardetail.php?id=%s&detail=intro' % actor_id
        try:
            content = _urllibRequest(url)
        except:
            print '跳过内容'
            return u''
        content = content.decode('gb18030', 'ignore').encode('utf-8')
        content = content.replace("\r", "\n")
        myMassage = [(re.compile('<style.*?>([\s\S]*?)</style>'), lambda x: ''),
                 (re.compile('<script.*?>([\s\S]*?)</script>'), lambda x: '')]
        html_content = BeautifulSoup.BeautifulSoup(content, fromEncoding='utf-8')#, markupMassage=myMassage)
        #div = html_content.find('div', attrs={'class':'blk_detail'})
        span_content = html_content.find('span', attrs={'style':''})
        span_content = str(span_content)
        span_content = span_content.replace('　　', '')
        span_content = span_content.replace('<span style="">', '')
        span_content = span_content.replace('</span>', '')
        span_content = span_content.replace('<br />', '<br /><br />')
        return span_content.decode('utf-8')

    def getActorBaseInfo(self, actor_id):
        info_fields = [
                        'title', 'nickname', 'english_name', 'sex', 'occupation', 'nationality', 
                        'region', 'birthday', 'faith', 'zodiac', 'birthplace', 'height', 'weight',
                        'blood_type'
                      ]

        url = 'http://data.ent.sina.com.cn/star/starone.php?id=%s&dpc=1' % actor_id
        print url
        try:
            content = _urllibRequest(url)
        except:
            print 'baseinfo错误, 跳过'
            return False

        content = content.decode('gb18030', 'ignore').encode('utf-8')
        if content.find(u'组合名称') != -1:
            return False
        #f = open('/tmp/sina_actor.tmp', 'w+')
        #f.write(content)
        #f.close()
        myMassage = [(re.compile('<style.*?>([\s\S]*?)</style>'), lambda x: ''),
                     (re.compile('<script.*?>([\s\S]*?)</script>'), lambda x: ''),
                     (re.compile('style="(.*?)"'), lambda x:''),
                     (re.compile('<tr(.*?)>'), lambda x:'')]
        html_content = BeautifulSoup.BeautifulSoup(content, fromEncoding='utf-8', markupMassage=myMassage)
        table = html_content.find('table', attrs={'class':'inf'})
        tds = table.findAll('td', limit=14)

        i = 0
        base_data = {}
        for td in tds:
            field_name = info_fields[i]
            match = re.compile('<.*?>')
            field_data = match.sub('', str(td))
            if field_data and field_data != u'暂无':
            	base_data[field_name] = field_data
            i = i + 1
        
        #处理照片
        photo_img = html_content.find('img', attrs={'rel':'v:photo'})
        photo_img_url = photo_img['src']
        if photo_img_url:
            try:
                source_img_data = _urllibRequest(photo_img_url)

                image_data = StringIO(source_img_data)
                #原图
                img_s = image_data
                #缩略图
                img_thumb = StringIO()
                im = Image.open(image_data)
                im.thumbnail((120, 120), Image.NEAREST)
                im.save(img_thumb, 'jpeg')
                #print img_thumb.getvalue()
                #exit()
                source_key = '%d%s' % (time.time(), random.randint(100, 999))
                thumb_key = '%d%s_120' % (time.time(), random.randint(100, 999))
                source_name = source_key + '.jpg'
                thumb_name = thumb_key + '.jpeg'

                #文件存储到mogilefs
                #c = mogilefs.Client(domain='5ifoto', trackers=['192.168.1.51:6001'])
                c = mogilefs.Client(domain='5itv', trackers=['192.168.1.31:6001'])
                c.send_file(source_name, img_s, 'image')
                c.send_file(thumb_name, img_thumb, 'image')

                #保存mysql文件系统数据
                thumb_value = '{"120":"%s"}' % thumb_name
                self.db.query("INSERT INTO `attachments`(`file_name`, `source_name`, `file_key`, `category_id`, `thumb`, `created_at`, `updated_at`) \
                                        VALUES (%s, %s, %s, '90', %s, now(),  now());",
                                        (source_name, source_name, source_key, thumb_value))

                base_data['cover'] = source_name
            except:
                print '跳过错误图片'
        return base_data

    #拼接明星列表页url
    def getListUrls(self):
        base_list_url = "http://data.ent.sina.com.cn/star/starlist.php?&initial=%s&tpl=0&dpc=1"
        list_keys = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', '1']

        list_urls = []
        for list_key in list_keys:
            list_url = base_list_url % (list_key)
            list_urls.append(list_url)
        return list_urls

    #获取艺人ID号
    def getActorIds(self, list_urls):
        actor_ids = []
        
        for list_url in list_urls:
            print list_url
            content = _urllibRequest(list_url)
            content = content.decode('gb18030', 'ignore').encode('utf-8')
            myMassage = [(re.compile('<style.*?>([\s\S]*?)</style>'), lambda x: ''),
                         (re.compile('<script.*?>([\s\S]*?)</script>'), lambda x: '')]
            html_content = BeautifulSoup.BeautifulSoup(content, fromEncoding='utf-8', markupMassage=myMassage)
            list_ul = html_content.find('ul', attrs={'class': 'listName'})
            list_a = list_ul.findAll('a')
            
            for a in list_a:
                actor_id = a['href'].replace('.html', '')
                actor_ids.append(actor_id)

        return actor_ids
        

if __name__ == "__main__":
    spider = SinaActorSpider()
    spider.run()
