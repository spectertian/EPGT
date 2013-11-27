# To change this template, choose Tools | Templates
# and open the template in the editor.


import sys
reload(sys)
sys.setdefaultencoding('utf8')
import os
import time
import signal
import string
import urllib2
import re
import BeautifulSoup

def _urllibRequest(url):
    req = urllib2.Request(url)
    req.add_header('User-Agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.0.7) Gecko/2009021910 Firefox/3.0.7')
    return urllib2.build_opener().open(req).read()

def getDirId(tvsou_id):
        dir_id = str(tvsou_id)
        dir_id_count = dir_id.count('')
        if(dir_id_count == 4):
            dir_id = dir_id[:1]
        elif(dir_id_count == 5):
            dir_id = dir_id[:2]
        elif(dir_id_count == 6):
            dir_id = dir_id[:3]
        elif(dir_id_count == 7):
            dir_id = dir_id[:4]
        elif(dir_id_count == 8):
            dir_id = dir_id[:5]
        return dir_id


if __name__ == "__main__":
    i = 73381

    while 1:
        print i
        if i > 74796:
            break
        dir_id = getDirId(i)
        url = "http://jq.tvsou.com/introhtml/%s/index_%s.htm" % (dir_id, i)
        try:
            content = _urllibRequest(url)
        except urllib2.HTTPError, e:
            if(e.code == 404):
                print '%s 404' % i
                i = i + 1
                continue
         
        content = content.decode('gb18030').encode('utf-8')
        html_content = BeautifulSoup.BeautifulSoup(content, fromEncoding='utf-8')
        span = html_content.find('span', attrs={'class': 'm1_title1_1'})
        
        if span:
            print "%s,%s\n" % (i, span.string)
            f = file('tmp.csv', 'a+')
            f.write("%s,%s\n" % (i, span.string))
            f.close
        
            i = i + 1
        else:
            print '%s pass' % i
            i = i + 1
            continue

